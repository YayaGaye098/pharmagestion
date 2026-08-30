<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LandingController extends Controller
{
    /**
     * Page d'accueil publique : si déjà connecté, redirige directement selon le rôle
     */
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->isVendor()) {
                return redirect()->route('vendor.dashboard');
            }
            return redirect()->route('dashboard');
        }

        return view('landing');
    }
}
