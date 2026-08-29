<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'facility_name' => 'Poste de Santé de Dakar',
            'region' => 'Dakar, Sénégal',
            'currency' => 'FCFA',
            'alert_email' => auth()->user()->email ?? 'admin@pharmacie.sn',
        ];

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        return redirect()->route('settings.index')->with('success', 'Paramètres mis à jour avec succès !');
    }
}
