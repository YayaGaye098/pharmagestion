<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockEntryController extends Controller
{
    public function index()
    {
        $entries = StockMovement::with('medication', 'user')
            ->where('type', 'entrée')
            ->latest()
            ->get();

        $medications = Medication::orderBy('name')->get();

        return view('entries.index', compact('entries', 'medications'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'medication_id' => 'required|exists:medications,id',
            'quantity' => 'required|integer|min:1',
            'supplier' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $medication = Medication::findOrFail($validated['medication_id']);
        
        // Increase stock quantity
        $medication->increment('stock_quantity', $validated['quantity']);

        // Log movement
        StockMovement::create([
            'medication_id' => $medication->id,
            'type' => 'entrée',
            'quantity' => $validated['quantity'],
            'user_id' => auth()->id(),
            'performed_by_name' => auth()->user()->name ?? 'Agent',
            'notes' => ($validated['supplier'] ? "Fournisseur: {$validated['supplier']} - " : '') . ($validated['notes'] ?? ''),
        ]);

        return redirect()->route('entries.index')->with('success', 'Entrée de stock enregistrée et stock mis à jour !');
    }
}
