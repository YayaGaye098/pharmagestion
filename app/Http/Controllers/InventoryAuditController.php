<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use App\Models\StockMovement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $result = DB::transaction(function () use ($validated, $medication) {
            $lockedMedication = Medication::lockForUpdate()->findOrFail($medication->id);
            $previousStock = (int) $lockedMedication->stock_quantity;
            $physicalStock = (int) $validated['physical_stock'];
            $diff = $physicalStock - $previousStock;

            $lockedMedication->update([
                'stock_quantity' => $physicalStock,
                'status' => $lockedMedication->stockStatusFor($physicalStock),
            ]);

            if ($diff !== 0) {
                $reason = trim($validated['reason'] ?? '');
                $notes = "Ajustement inventaire. Stock théorique: {$previousStock}, stock physique: {$physicalStock}, écart: {$diff}.";

                if ($reason !== '') {
                    $notes .= " Motif: {$reason}";
                }

                StockMovement::create([
                    'medication_id' => $lockedMedication->id,
                    'type' => $diff > 0 ? 'entrée' : 'sortie',
                    'quantity' => abs($diff),
                    'user_id' => auth()->id(),
                    'performed_by_name' => auth()->user()->name ?? 'Agent',
                    'notes' => $notes,
                ]);
            }

            return [
                'medication_name' => $lockedMedication->name,
                'diff' => $diff,
            ];
        });

        return redirect()->route('inventories.index')->with('success', "Inventaire ajusté pour {$result['medication_name']}. Écart: {$result['diff']}.");
    }

    public function downloadSheet()
    {
        $medications = Medication::orderBy('name')->get();
        $pdf = Pdf::loadView('pdf.inventory_sheet', compact('medications'));
        return $pdf->download("fiche_comptage_inventaire_" . date('Y_m_d') . ".pdf");
    }
}
