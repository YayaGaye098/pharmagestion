<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Medication;
use Illuminate\Http\Request;

class MedicationController extends Controller
{
    public function index(Request $request)
    {
        $query = Medication::with('category');

        // Search by name or code
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category') && $request->input('category') !== 'Toutes') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('name', $request->input('category'));
            });
        }

        // Filter by form
        if ($request->filled('form') && $request->input('form') !== 'Toutes') {
            $query->where('form', $request->input('form'));
        }

        $medications = $query->orderBy('name')->get();
        $categories = Category::all();
        $forms = Medication::select('form')->distinct()->pluck('form');

        return view('medications.index', compact('medications', 'categories', 'forms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:medications,code',
            'name' => 'required|string|max:255',
            'dosage' => 'required|string|max:100',
            'form' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'stock_quantity' => 'required|integer|min:0',
            'min_threshold' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'expiration_date' => 'nullable|date',
        ]);

        Medication::create($validated);

        return redirect()->route('medications.index')->with('success', 'Médicament ajouté avec succès !');
    }
}
