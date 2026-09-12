<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use App\Models\StockMovement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockEntryController extends Controller
{
    /**
     * Affiche l'historique des réceptions avec calcul des marges pour l'administrateur
     */
    public function index(Request $request)
    {
        $query = StockMovement::with(['medication.category', 'user'])
            ->where('type', 'entrée');

        // Filtre de recherche par nom, code, n° commande ou fournisseur
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('reference_no', 'like', "%{$search}%")
                  ->orWhere('supplier', 'like', "%{$search}%")
                  ->orWhereHas('medication', function ($medQ) use ($search) {
                      $medQ->where('name', 'like', "%{$search}%")
                           ->orWhere('code', 'like', "%{$search}%");
                  });
            });
        }

        // Filtre par date
        if ($request->filled('date_from')) {
            $query->whereDate('movement_date', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('movement_date', '<=', $request->input('date_to'));
        }

        $entries = $query->orderByDesc('movement_date')
            ->orderByDesc('id')
            ->get();

        // Calcul des indicateurs financiers prévisionnels
        $totalPurchases = $entries->sum(function ($entry) {
            return $entry->total_purchase;
        });

        $totalExpectedSales = $entries->sum(function ($entry) {
            return $entry->total_selling;
        });

        $totalProjectedMargin = $totalExpectedSales - $totalPurchases;
        $averageMarginPercentage = ($totalPurchases > 0)
            ? round(($totalProjectedMargin / $totalPurchases) * 100, 1)
            : 0;

        $medications = Medication::with('category')
            ->orderBy('name')
            ->get()
            ->map(function ($med) {
                return [
                    'id' => $med->id,
                    'code' => $med->code,
                    'name' => $med->name,
                    'dosage' => $med->dosage,
                    'form' => $med->form,
                    'packaging_unit' => $med->packaging_unit ?? '',
                    'stock_quantity' => (int) $med->stock_quantity,
                    'purchase_price' => (float) ($med->purchase_price ?? 0),
                    'unit_price' => (float) $med->unit_price,
                    'unit_margin' => (float) $med->unit_margin,
                    'margin_percentage' => (float) $med->margin_percentage,
                ];
            });

        return view('entries.index', compact(
            'entries',
            'medications',
            'totalPurchases',
            'totalExpectedSales',
            'totalProjectedMargin',
            'averageMarginPercentage'
        ));
    }

    /**
     * Enregistre une entrée de stock avec calcul de marge et synchronisation des prix
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'medication_id' => 'required|exists:medications,id',
            'quantity' => 'required|integer|min:1',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'movement_date' => 'nullable|date',
            'reference_no' => 'nullable|string|max:100',
            'supplier' => 'nullable|string|max:255',
            'packaging_unit' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $movementDate = !empty($validated['movement_date'])
            ? Carbon::parse($validated['movement_date'])->toDateString()
            : now()->toDateString();

        $supplier = !empty($validated['supplier'])
            ? trim($validated['supplier'])
            : 'District Sanitaire / PNA';

        $packagingUnit = !empty($validated['packaging_unit'])
            ? trim($validated['packaging_unit'])
            : null;

        $referenceNo = !empty($validated['reference_no'])
            ? strtoupper(trim($validated['reference_no']))
            : null;

        DB::transaction(function () use ($validated, $movementDate, $supplier, $packagingUnit, $referenceNo) {
            $medication = Medication::lockForUpdate()->findOrFail($validated['medication_id']);

            // Augmenter le stock en rayon
            $medication->increment('stock_quantity', (int) $validated['quantity']);
            $medication->refresh();

            // Mettre à jour les prix d'achat, de vente et l'UC du médicament
            $medicationUpdate = [
                'status' => $medication->computed_status,
                'purchase_price' => $validated['purchase_price'],
                'unit_price' => $validated['selling_price'],
            ];

            if ($packagingUnit) {
                $medicationUpdate['packaging_unit'] = $packagingUnit;
            }

            $medication->update($medicationUpdate);

            // Enregistrer la traçabilité complète de l'entrée avec les marges
            StockMovement::create([
                'medication_id' => $medication->id,
                'reference_no' => $referenceNo,
                'movement_date' => $movementDate,
                'supplier' => $supplier,
                'packaging_unit' => $packagingUnit ?? $medication->packaging_unit,
                'type' => 'entrée',
                'quantity' => (int) $validated['quantity'],
                'purchase_price' => $validated['purchase_price'],
                'selling_price' => $validated['selling_price'],
                'user_id' => auth()->id(),
                'performed_by_name' => auth()->user()->name ?? 'Administrateur',
                'notes' => $validated['notes'] ?? null,
            ]);
        });

        $marginUnit = (float) $validated['selling_price'] - (float) $validated['purchase_price'];
        $totalMargin = $marginUnit * (int) $validated['quantity'];

        $marginMsg = ($totalMargin >= 0)
            ? "Marge brute prévisionnelle : +" . number_format($totalMargin, 0, ',', ' ') . " FCFA."
            : "Attention : Marge négative constatée (" . number_format($totalMargin, 0, ',', ' ') . " FCFA).";

        return redirect()->route('entries.index')->with(
            'success',
            "Réception enregistrée avec succès ! Le stock et les prix ont été actualisés. {$marginMsg}"
        );
    }
}
