<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Handle an incoming request.
     * Usage: middleware('role:admin') or middleware('role:admin,pharmacist')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user || !in_array($user->role, $roles)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Accès refusé : permissions insuffisantes.'], 403);
            }

            if ($user && $user->isVendor()) {
                return redirect()->route('vendor.dashboard')->with('error', 'Accès refusé : cette section est réservée à l\'administrateur.');
            }

            if ($user && ($user->isAdmin() || $user->isPharmacist())) {
                return redirect()->route('dashboard')->with('error', 'Le guichet de vente est réservé aux vendeuses. L\'administrateur assure la supervision.');
            }

            abort(403, 'Accès refusé : vous n\'avez pas les permissions nécessaires.');
        }

        return $next($request);
    }
}
