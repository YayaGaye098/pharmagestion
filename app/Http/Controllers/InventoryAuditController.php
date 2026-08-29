<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InventoryAuditController extends Controller
{
    public function index()
    {
        $medications = Medication::with('category')->orderBy('name')->get();
        return view('inventories.index', compact('medications'));
    }

    public function updateStock(Request $request, Medication $medication)
    {
        $validated = $request->validate([
            'physical_stock' => 'required|integer|min:0',
            'reason' => 'nullable|string',
        ]);

        $diff = $validated['physical_stock'] - $medication->stock_quantity;
        $medication->update(['stock_quantity' => $validated['physical_stock']]);

        return redirect()->route('inventories.index')->with('success', "Inventaire ajusté pour {$medication->name}. Écart: {$diff}.");
    }

    public function downloadSheet()
    {
        $medications = Medication::orderBy('name')->get();
        $pdf = Pdf::loadView('pdf.inventory_sheet', compact('medications'));
        return $pdf->download("fiche_comptage_inventaire_" . date('Y_m_d') . ".pdf");
    }
}
