<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function index()
    {
        $outOfStock = Medication::where('stock_quantity', '<=', 0)->get();
        $lowStock = Medication::where('stock_quantity', '>', 0)
            ->whereColumn('stock_quantity', '<=', 'min_threshold')
            ->get();

        $nearExpiration = Medication::whereNotNull('expiration_date')
            ->where('expiration_date', '<=', now()->addMonths(6))
            ->orderBy('expiration_date', 'asc')
            ->get();

        return view('alerts.index', compact('outOfStock', 'lowStock', 'nearExpiration'));
    }
}
