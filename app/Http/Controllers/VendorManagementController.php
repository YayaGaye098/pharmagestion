<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class VendorManagementController extends Controller
{
    /**
     * Liste des comptes vendeuses avec leurs performances
     */
    public function index()
    {
        $vendors = User::whereIn('role', ['vendor', 'agent'])->latest()->get();

        foreach ($vendors as $v) {
            $v->sales_count = Sale::where('user_id', $v->id)->count();
            $v->sales_total = Sale::where('user_id', $v->id)->where('status', 'paid')->sum('total_amount');
            $v->today_sales_total = Sale::where('user_id', $v->id)->whereDate('created_at', today())->where('status', 'paid')->sum('total_amount');
        }

        return view('admin.vendors.index', compact('vendors'));
    }

    /**
     * Création d'un nouveau compte vendeuse par l'Admin
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:50',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => 'vendor',
            'status' => 'active',
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.vendors.index')->with('success', "Compte vendeuse pour '{$user->name}' créé avec succès ! Identifiants prêts à l'emploi.");
    }

    /**
     * Mise à jour d'un compte vendeuse
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:50',
            'password' => 'nullable|string|min:6',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('admin.vendors.index')->with('success', "Informations de la vendeuse '{$user->name}' mises à jour !");
    }

    /**
     * Bascule le statut Actif / Inactif
     */
    public function toggleStatus(User $user)
    {
        $newStatus = $user->status === 'active' ? 'inactive' : 'active';
        $user->update(['status' => $newStatus]);

        $label = $newStatus === 'active' ? 'activé' : 'désactivé';
        return redirect()->route('admin.vendors.index')->with('success', "Le compte de {$user->name} a été {$label}.");
    }

    /**
     * Suppression d'un compte vendeuse
     */
    public function destroy(User $user)
    {
        // Si la vendeuse a des ventes, on empêche la suppression physique pour garder l'intégrité comptable
        $hasSales = Sale::where('user_id', $user->id)->exists();
        if ($hasSales) {
            $user->update(['status' => 'inactive']);
            return redirect()->route('admin.vendors.index')->with('success', "Cette vendeuse a des ventes associées. Le compte a été désactivé pour conserver l'historique comptable.");
        }

        $user->delete();
        return redirect()->route('admin.vendors.index')->with('success', "Compte vendeuse supprimé avec succès.");
    }
}
