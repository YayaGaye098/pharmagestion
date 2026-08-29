<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Medication;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = Medication::with('category');

        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'rupture') {
                $query->where('stock_quantity', '<=', 0);
            } elseif ($status === 'faible') {
                $query->where('stock_quantity', '>', 0)
                      ->whereColumn('stock_quantity', '<=', 'min_threshold');
            } elseif ($status === 'ok') {
                $query->whereColumn('stock_quantity', '>', 'min_threshold');
            }
        }

        $medications = $query->orderBy('stock_quantity', 'asc')->get();

        return view('stock.index', compact('medications'));
    }
}
