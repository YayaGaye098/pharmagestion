<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMedications = Medication::count();
        $totalStock = Medication::sum('stock_quantity');
        
        $stockValue = Medication::select(DB::raw('SUM(stock_quantity * unit_price) as total_val'))
            ->value('total_val') ?? 0;

        $outOfStockCount = Medication::where('stock_quantity', '<=', 0)->count();
        $lowStockCount = Medication::where('stock_quantity', '>', 0)
            ->whereColumn('stock_quantity', '<=', 'min_threshold')
            ->count();

        $criticalAlerts = Medication::where('stock_quantity', '<=', 0)
            ->orWhereColumn('stock_quantity', '<=', 'min_threshold')
            ->take(5)
            ->get();

        $recentMovements = StockMovement::with('medication', 'user')
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard', compact(
            'totalMedications',
            'totalStock',
            'stockValue',
            'outOfStockCount',
            'lowStockCount',
            'criticalAlerts',
            'recentMovements'
        ));
    }
}
