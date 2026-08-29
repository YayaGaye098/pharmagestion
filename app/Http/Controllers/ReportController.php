<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Medication;
use App\Models\StockMovement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $totalValuation = Medication::select(DB::raw('SUM(stock_quantity * unit_price) as total_val'))
            ->value('total_val') ?? 0;

        $categoryStats = Category::withCount('medications')->get();

        $monthlyEntriesCount = StockMovement::where('type', 'entrée')->whereMonth('created_at', now()->month)->sum('quantity');
        $monthlyExitsCount = StockMovement::where('type', 'sortie')->whereMonth('created_at', now()->month)->sum('quantity');

        return view('reports.index', compact('totalValuation', 'categoryStats', 'monthlyEntriesCount', 'monthlyExitsCount'));
    }

    public function downloadPdf()
    {
        $medications = Medication::with('category')->orderBy('name')->get();
        $totalMedications = $medications->count();
        $totalStock = $medications->sum('stock_quantity');
        $stockValue = Medication::select(DB::raw('SUM(stock_quantity * unit_price) as total_val'))->value('total_val') ?? 0;

        $pdf = Pdf::loadView('pdf.stock_report', compact('medications', 'totalMedications', 'totalStock', 'stockValue'));
        return $pdf->download("rapport_stock_" . date('Y_m_d') . ".pdf");
    }
}
