<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class VendorDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Personal sales movements of the current seller
        $myMovements = StockMovement::where('user_id', $user->id)
            ->where('type', 'sortie')
            ->with('medication')
            ->latest()
            ->get();

        $todaySalesCount = $myMovements->where('created_at', '>=', now()->today())->count();
        $todayItemsCount = $myMovements->where('created_at', '>=', now()->today())->sum('quantity');

        // Estimate sales total FCFA today
        $todayRevenue = $myMovements->where('created_at', '>=', now()->today())
            ->reduce(function ($carry, $m) {
                return $carry + ($m->quantity * ($m->medication->unit_price ?? 0));
            }, 0);

        return view('vendor.dashboard', compact('myMovements', 'todaySalesCount', 'todayItemsCount', 'todayRevenue'));
    }

    public function sales()
    {
        $user = auth()->user();
        $mySales = StockMovement::where('user_id', $user->id)
            ->where('type', 'sortie')
            ->with('medication')
            ->latest()
            ->get();

        return view('vendor.sales', compact('mySales'));
    }

    public function medications()
    {
        $medications = Medication::with('category')->where('stock_quantity', '>', 0)->orderBy('name')->get();
        return view('vendor.medications', compact('medications'));
    }

    public function reports()
    {
        $user = auth()->user();
        $mySales = StockMovement::where('user_id', $user->id)
            ->where('type', 'sortie')
            ->with('medication')
            ->latest()
            ->get();

        $totalRevenue = $mySales->reduce(function ($carry, $m) {
            return $carry + ($m->quantity * ($m->medication->unit_price ?? 0));
        }, 0);

        return view('vendor.reports', compact('mySales', 'totalRevenue'));
    }
}
