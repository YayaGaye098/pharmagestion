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
        ]);

        $medication->update(['unit_price' => $validated['unit_price']]);

        return redirect()->route('prices.index')->with('success', "Prix mis à jour pour {$medication->name}: {$validated['unit_price']} FCFA.");
    }
}
