<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use Illuminate\Http\Request;

class TraceabilityController extends Controller
{
    public function index()
    {
        $movements = StockMovement::with('medication', 'user')
            ->latest()
            ->paginate(20);

        return view('traceability.index', compact('movements'));
    }
}
