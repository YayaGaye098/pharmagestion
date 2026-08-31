<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->isVendor()) {
                return redirect()->route('vendor.dashboard');
            }
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $user = Auth::user();

            // Vérification du statut du compte
            if (isset($user->status) && $user->status === 'inactive') {
                Auth::logout();
                return back()->withErrors(['email' => 'Votre compte a été désactivé par l\'administrateur.']);
            }

            $request->session()->regenerate();

            // Message de bienvenue personnalisé
            $hour = now()->format('H');
            if ($hour < 12) {
                $greeting = 'Bonjour';
            } elseif ($hour < 18) {
                $greeting = 'Bon après-midi';
            } else {
                $greeting = 'Bonsoir';
            }
            $welcomeMessage = "$greeting, {$user->name} ! Bienvenue sur PharmaGestion 👋";

            // Redirection dédiée selon le rôle du compte connecté
            if ($user->isVendor()) {
                return redirect()->route('vendor.dashboard')->with('welcome', $welcomeMessage);
            }

            return redirect()->route('dashboard')->with('welcome', $welcomeMessage);
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'firstName' => 'required|string|max:100',
            'lastName' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:50',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['firstName'] . ' ' . $validated['lastName'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => 'admin',
            'status' => 'active',
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Compte Administrateur créé avec succès !');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
