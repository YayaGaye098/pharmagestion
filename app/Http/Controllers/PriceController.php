<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function index()
    {
        $medications = Medication::orderBy('name')->get();
        return view('prices.index', compact('medications'));
    }

    public function updatePrice(Request $request, Medication $medication)
    {
        $validated = $request->validate([
            'unit_price' => 'required|numeric|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
        ]);

        $updateData = [
            'unit_price' => $validated['unit_price'],
        ];

        if (array_key_exists('purchase_price', $validated)) {
            $updateData['purchase_price'] = $validated['purchase_price'];
        }

        $medication->update($updateData);

        return redirect()->route('prices.index')->with(
            'success',
            "Tarifs actualisés pour {$medication->name} : Prix Vente {$medication->unit_price} FCFA (Marge unitaire : +{$medication->unit_margin} FCFA)."
        );
    }
}
