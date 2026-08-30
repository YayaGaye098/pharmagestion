<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Medication;
use App\Models\SaleItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicationController extends Controller
{
    /**
     * Liste et inventaire des médicaments avec recherche et filtres
     */
    public function index(Request $request)
    {
        $query = Medication::with('category');

        // Recherche par nom, code ou dosage
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('dosage', 'like', "%{$search}%");
            });
        }

        // Filtre par catégorie
        if ($request->filled('category') && $request->input('category') !== 'Toutes') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('name', $request->input('category'));
            });
        }

        // Filtre par forme
        if ($request->filled('form') && $request->input('form') !== 'Toutes') {
            $query->where('form', $request->input('form'));
        }

        // Filtre par statut (stock)
        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'rupture') {
                $query->where('stock_quantity', '<=', 0);
            } elseif ($status === 'faible') {
                $query->where('stock_quantity', '>', 0)->whereRaw('stock_quantity <= min_threshold');
            } elseif ($status === 'ok') {
                $query->whereRaw('stock_quantity > min_threshold');
            }
        }

        $medications = $query->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $forms = Medication::select('form')->distinct()->whereNotNull('form')->pluck('form');

        // Statistiques globales du catalogue
        $totalReferences = Medication::count();
        $totalStockUnits = Medication::sum('stock_quantity');
        $outOfStockCount = Medication::where('stock_quantity', '<=', 0)->count();
        $lowStockCount = Medication::where('stock_quantity', '>', 0)->whereRaw('stock_quantity <= min_threshold')->count();
        $totalStockValue = Medication::select(DB::raw('SUM(stock_quantity * unit_price) as val'))->value('val') ?? 0;

        return view('medications.index', compact(
            'medications',
            'categories',
            'forms',
            'totalReferences',
            'totalStockUnits',
            'outOfStockCount',
            'lowStockCount',
            'totalStockValue'
        ));
    }

    /**
     * Enregistrement d'un nouveau médicament par le gérant
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:medications,code',
            'name' => 'required|string|max:255',
            'dosage' => 'required|string|max:100',
            'form' => 'required|string|max:100',
            'category_id' => 'nullable|exists:categories,id',
            'new_category' => 'nullable|string|max:100',
            'stock_quantity' => 'required|integer|min:0',
            'min_threshold' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'expiration_date' => 'nullable|date',
        ]);

        // Gestion d'une nouvelle catégorie créée à la volée
        if (!empty($validated['new_category'])) {
            $cat = Category::firstOrCreate(['name' => trim($validated['new_category'])]);
            $categoryId = $cat->id;
        } else {
            $categoryId = $validated['category_id'] ?? Category::firstOrCreate(['name' => 'Général'])->id;
        }

        // Calcul du statut de stock
        $qty = (int) $validated['stock_quantity'];
        $threshold = (int) $validated['min_threshold'];
        $status = ($qty <= 0) ? 'rupture' : (($qty <= $threshold) ? 'faible' : 'ok');

        $medication = Medication::create([
            'code' => strtoupper(trim($validated['code'])),
            'name' => trim($validated['name']),
            'dosage' => trim($validated['dosage']),
            'form' => trim($validated['form']),
            'category_id' => $categoryId,
            'stock_quantity' => $qty,
            'min_threshold' => $threshold,
            'unit_price' => $validated['unit_price'],
            'expiration_date' => $validated['expiration_date'] ?? null,
            'status' => $status,
        ]);

        // Si une quantité initiale a été renseignée, on enregistre une entrée de stock initiale pour la traçabilité
        if ($qty > 0) {
            StockMovement::create([
                'medication_id' => $medication->id,
                'type' => 'entrée',
                'quantity' => $qty,
                'user_id' => auth()->id(),
                'performed_by_name' => auth()->user()->name ?? 'Gérant',
                'notes' => 'Stock initial lors de la création de la fiche médicament.',
            ]);
        }

        return redirect()->route('medications.index')->with('success', "Le médicament '{$medication->name}' ({$medication->code}) a été ajouté avec succès au catalogue !");
    }

    /**
     * Mise à jour des informations d'un médicament
     */
    public function update(Request $request, Medication $medication)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:medications,code,' . $medication->id,
            'name' => 'required|string|max:255',
            'dosage' => 'required|string|max:100',
            'form' => 'required|string|max:100',
            'category_id' => 'nullable|exists:categories,id',
            'new_category' => 'nullable|string|max:100',
            'min_threshold' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'expiration_date' => 'nullable|date',
        ]);

        if (!empty($validated['new_category'])) {
            $cat = Category::firstOrCreate(['name' => trim($validated['new_category'])]);
            $categoryId = $cat->id;
        } else {
            $categoryId = $validated['category_id'] ?? $medication->category_id;
        }

        $qty = (int) $medication->stock_quantity;
        $threshold = (int) $validated['min_threshold'];
        $status = ($qty <= 0) ? 'rupture' : (($qty <= $threshold) ? 'faible' : 'ok');

        $medication->update([
            'code' => strtoupper(trim($validated['code'])),
            'name' => trim($validated['name']),
            'dosage' => trim($validated['dosage']),
            'form' => trim($validated['form']),
            'category_id' => $categoryId,
            'min_threshold' => $threshold,
            'unit_price' => $validated['unit_price'],
            'expiration_date' => $validated['expiration_date'] ?? null,
            'status' => $status,
        ]);

        return redirect()->route('medications.index')->with('success', "Médicament '{$medication->name}' mis à jour avec succès !");
    }

    /**
     * Suppression d'un médicament du catalogue
     */
    public function destroy(Medication $medication)
    {
        // Vérification de sécurité : si le médicament a des ventes associées
        $hasSales = SaleItem::where('medication_id', $medication->id)->exists();
        if ($hasSales) {
            return back()->with('error', "Impossible de supprimer '{$medication->name}' car il figure dans des tickets de vente déjà validés. Vous pouvez ajuster son stock à 0.");
        }

        $medication->delete();
        return redirect()->route('medications.index')->with('success', "Le médicament a été supprimé du catalogue.");
    }
}
