<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Medication;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PosController extends Controller
{
    /**
     * Affiche l'interface Guichet / Point de Vente (POS)
     */
    public function index()
    {
        $categories = Category::orderBy('name')->get();
        
        $medications = Medication::with('category')
            ->where('stock_quantity', '>', 0)
            ->orderBy('name')
            ->get()
            ->map(function ($med) {
                return [
                    'id' => $med->id,
                    'code' => $med->code,
                    'name' => $med->name,
                    'dosage' => $med->dosage,
                    'form' => $med->form,
                    'category_id' => $med->category_id,
                    'category_name' => $med->category->name ?? 'Général',
                    'stock_quantity' => (int) $med->stock_quantity,
                    'min_threshold' => (int) $med->min_threshold,
                    'unit_price' => (float) $med->unit_price,
                    'status' => $med->computed_status,
                ];
            });

        $recentSales = Sale::with(['items.medication'])
            ->where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        return view('pos.index', compact('categories', 'medications', 'recentSales'));
    }

    /**
     * Enregistre une nouvelle vente au guichet
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_name' => 'nullable|string|max:255',
            'payment_method' => 'required|string|in:espèces,wave,orange_money,cmu,autre',
            'paid_amount' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.medication_id' => 'required|exists:medications,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            $sale = DB::transaction(function () use ($validated) {
                $user = auth()->user();
                $totalAmount = 0;
                $itemsToProcess = [];

                // 1. Vérification stricte des stocks disponibles
                foreach ($validated['items'] as $itemData) {
                    $medication = Medication::lockForUpdate()->findOrFail($itemData['medication_id']);
                    $requestedQty = (int) $itemData['quantity'];

                    if ($medication->stock_quantity < $requestedQty) {
                        throw new \Exception("Stock insuffisant pour le médicament '{$medication->name}' (Demandé: {$requestedQty}, Disponible: {$medication->stock_quantity}).");
                    }

                    $unitPrice = (float) $medication->unit_price;
                    $subtotal = $unitPrice * $requestedQty;
                    $totalAmount += $subtotal;

                    $itemsToProcess[] = [
                        'medication' => $medication,
                        'quantity' => $requestedQty,
                        'unit_price' => $unitPrice,
                        'subtotal' => $subtotal,
                    ];
                }

                // 2. Calcul du montant versé et rendu de monnaie
                $paidAmount = !empty($validated['paid_amount']) ? (float) $validated['paid_amount'] : $totalAmount;
                $changeAmount = ($paidAmount >= $totalAmount) ? ($paidAmount - $totalAmount) : 0;

                // 3. Génération d'une référence unique de vente
                $reference = 'VNT-' . date('Ymd') . '-' . strtoupper(Str::random(5));

                // 4. Création de la Vente
                $sale = Sale::create([
                    'reference' => $reference,
                    'total_amount' => $totalAmount,
                    'payment_method' => $validated['payment_method'],
                    'paid_amount' => $paidAmount,
                    'change_amount' => $changeAmount,
                    'user_id' => $user->id,
                    'patient_name' => !empty($validated['patient_name']) ? trim($validated['patient_name']) : 'Client Comptoir',
                    'status' => 'paid',
                ]);

                // 5. Enregistrement des lignes, décrémentation des stocks et traçabilité
                foreach ($itemsToProcess as $processed) {
                    $med = $processed['medication'];
                    $qty = $processed['quantity'];

                    // Ligne de vente
                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'medication_id' => $med->id,
                        'quantity' => $qty,
                        'unit_price' => $processed['unit_price'],
                        'subtotal' => $processed['subtotal'],
                    ]);

                    // Décrémentation du stock
                    $med->decrement('stock_quantity', $qty);

                    // Traçabilité mouvement de stock
                    StockMovement::create([
                        'medication_id' => $med->id,
                        'type' => 'sortie',
                        'quantity' => $qty,
                        'user_id' => $user->id,
                        'performed_by_name' => $user->name,
                        'notes' => "Vente Guichet N°{$sale->reference}" . ($sale->patient_name ? " (Bénéficiaire: {$sale->patient_name})" : ""),
                    ]);
                }

                return $sale;
            });

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Vente validée avec succès !",
                    'sale_id' => $sale->id,
                    'reference' => $sale->reference,
                    'total_amount' => $sale->total_amount,
                    'paid_amount' => $sale->paid_amount,
                    'change_amount' => $sale->change_amount,
                    'ticket_url' => route('sales.ticket', $sale->id),
                    'pdf_url' => route('sales.pdf', $sale->id),
                ]);
            }

            return redirect()->route('sales.pos')->with('success', "Vente {$sale->reference} enregistrée avec succès !");
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }

            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Affiche la vue ticket thermique imprimable
     */
    public function ticket($id)
    {
        $sale = Sale::with(['items.medication', 'user'])->findOrFail($id);

        // Sécurité : une vendeuse ne peut voir que ses ventes, un admin peut tout voir
        if (!auth()->user()->isAdmin() && $sale->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé à cette vente.');
        }

        return view('pdf.sale_ticket', compact('sale'));
    }

    /**
     * Télécharge le reçu de vente au format PDF
     */
    public function receiptPdf($id)
    {
        $sale = Sale::with(['items.medication', 'user'])->findOrFail($id);

        if (!auth()->user()->isAdmin() && $sale->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé à ce reçu.');
        }

        $pdf = Pdf::loadView('pdf.sale_ticket', compact('sale'));
        return $pdf->download("recu_vente_{$sale->reference}.pdf");
    }
}
