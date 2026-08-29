<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class VendorManagementController extends Controller
{
    public function index()
    {
        $vendors = User::whereIn('role', ['vendor', 'agent'])->get();

        foreach ($vendors as $v) {
            $v->sales_count = StockMovement::where('user_id', $v->id)->where('type', 'sortie')->count();
            $v->sales_total = StockMovement::where('user_id', $v->id)->where('type', 'sortie')->with('medication')->get()->reduce(function ($carry, $m) {
                return $carry + ($m->quantity * ($m->medication->unit_price ?? 0));
            }, 0);
        }

        return view('admin.vendors.index', compact('vendors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:50',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => 'vendor',
            'status' => 'active',
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.vendors.index')->with('success', "Compte vendeuse '{$validated['name']}' créé avec succès !");
    }

    public function toggleStatus(User $user)
    {
        $newStatus = $user->status === 'active' ? 'inactive' : 'active';
        $user->update(['status' => $newStatus]);

        return redirect()->route('admin.vendors.index')->with('success', "Le statut de {$user->name} a été mis à jour en '{$newStatus}'.");
    }
}
