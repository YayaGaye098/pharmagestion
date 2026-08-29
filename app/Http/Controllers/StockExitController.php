<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use App\Models\StockMovement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class StockExitController extends Controller
{
    public function index()
    {
        $exits = StockMovement::with('medication', 'user')
            ->where('type', 'sortie')
            ->latest()
            ->get();

        $medications = Medication::where('stock_quantity', '>', 0)->orderBy('name')->get();

        return view('exits.index', compact('exits', 'medications'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'medication_id' => 'required|exists:medications,id',
            'quantity' => 'required|integer|min:1',
            'patient_or_service' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $medication = Medication::findOrFail($validated['medication_id']);

        if ($medication->stock_quantity < $validated['quantity']) {
            return back()->withErrors(['quantity' => 'La quantité demandée dépasse le stock disponible.']);
        }

        $medication->decrement('stock_quantity', $validated['quantity']);

        $movement = StockMovement::create([
            'medication_id' => $medication->id,
            'type' => 'sortie',
            'quantity' => $validated['quantity'],
            'user_id' => auth()->id(),
            'performed_by_name' => auth()->user()->name ?? 'Agent',
            'notes' => ($validated['patient_or_service'] ? "Bénéficiaire: {$validated['patient_or_service']} - " : '') . ($validated['notes'] ?? ''),
        ]);

        return redirect()->route('exits.index')->with('success', 'Sortie enregistrée ! Bon N°' . $movement->id);
    }

    public function downloadReceipt($id)
    {
        $movement = StockMovement::with('medication')->findOrFail($id);
        $pdf = Pdf::loadView('pdf.receipt', compact('movement'));
        return $pdf->download("recu_sortie_{$movement->id}.pdf");
    }
}
