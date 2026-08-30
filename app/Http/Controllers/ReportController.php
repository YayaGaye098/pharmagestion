<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Medication;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Affiche le rapport complet d'activité avec filtres hebdomadaires, mensuels et personnalisés
     */
    public function index(Request $request)
    {
        $periodData = $this->resolveDateRange($request);
        $startDate = $periodData['start'];
        $endDate = $periodData['end'];
        $periodLabel = $periodData['label'];
        $periodKey = $periodData['key'];

        // 1. Chiffre d'affaires et statistiques de ventes sur la période
        $salesQuery = Sale::where('status', 'paid')->whereBetween('created_at', [$startDate, $endDate]);
        $totalRevenue = (float) $salesQuery->sum('total_amount');
        $totalSalesCount = $salesQuery->count();

        // Mode de règlement répartition
        $cashTotal = (float) Sale::where('status', 'paid')->whereBetween('created_at', [$startDate, $endDate])->where('payment_method', 'espèces')->sum('total_amount');
        $waveTotal = (float) Sale::where('status', 'paid')->whereBetween('created_at', [$startDate, $endDate])->where('payment_method', 'wave')->sum('total_amount');
        $omTotal = (float) Sale::where('status', 'paid')->whereBetween('created_at', [$startDate, $endDate])->where('payment_method', 'orange_money')->sum('total_amount');
        $otherTotal = (float) Sale::where('status', 'paid')->whereBetween('created_at', [$startDate, $endDate])->whereNotIn('payment_method', ['espèces', 'wave', 'orange_money'])->sum('total_amount');

        // Nombre total d'articles délivrés au guichet sur la période
        $totalItemsSold = (int) DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->where('sales.status', 'paid')
            ->whereBetween('sales.created_at', [$startDate, $endDate])
            ->sum('sale_items.quantity');

        // 2. Mouvements de stock sur la période (Entrées / Sorties)
        $entriesUnits = (int) StockMovement::where('type', 'entrée')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('quantity');

        $exitsUnits = (int) StockMovement::where('type', 'sortie')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('quantity');

        // 3. Valorisation actuelle globale du stock
        $totalStockValue = Medication::select(DB::raw('SUM(stock_quantity * unit_price) as total_val'))->value('total_val') ?? 0;
        $totalStockUnits = Medication::sum('stock_quantity');
        $totalReferences = Medication::count();

        // 4. Récapitulatif d'activité par vendeuse sur la période
        $vendors = User::whereIn('role', ['vendor', 'agent'])->get();
        $vendorActivity = [];
        foreach ($vendors as $v) {
            $vSalesCount = Sale::where('user_id', $v->id)->where('status', 'paid')->whereBetween('created_at', [$startDate, $endDate])->count();
            $vSalesRevenue = (float) Sale::where('user_id', $v->id)->where('status', 'paid')->whereBetween('created_at', [$startDate, $endDate])->sum('total_amount');
            $vShare = ($totalRevenue > 0) ? round(($vSalesRevenue / $totalRevenue) * 100, 1) : 0;

            $vendorActivity[] = [
                'user' => $v,
                'sales_count' => $vSalesCount,
                'sales_revenue' => $vSalesRevenue,
                'share' => $vShare,
            ];
        }

        // Trier par CA décroissant
        usort($vendorActivity, fn($a, $b) => $b['sales_revenue'] <=> $a['sales_revenue']);

        // 5. Top 10 des médicaments les plus vendus sur la période
        $topMedications = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('medications', 'sale_items.medication_id', '=', 'medications.id')
            ->where('sales.status', 'paid')
            ->whereBetween('sales.created_at', [$startDate, $endDate])
            ->select(
                'medications.code',
                'medications.name',
                'medications.dosage',
                'medications.form',
                'medications.stock_quantity',
                DB::raw('SUM(sale_items.quantity) as total_qty'),
                DB::raw('SUM(sale_items.subtotal) as total_amount')
            )
            ->groupBy('medications.id', 'medications.code', 'medications.name', 'medications.dosage', 'medications.form', 'medications.stock_quantity')
            ->orderByDesc('total_qty')
            ->take(10)
            ->get();

        // 6. Derniers mouvements de stock sur la période
        $recentMovements = StockMovement::with('medication')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->take(15)
            ->get();

        // Catégories stats
        $categoryStats = Category::withCount('medications')->get();

        return view('reports.index', compact(
            'periodKey',
            'periodLabel',
            'startDate',
            'endDate',
            'totalRevenue',
            'totalSalesCount',
            'totalItemsSold',
            'cashTotal',
            'waveTotal',
            'omTotal',
            'otherTotal',
            'entriesUnits',
            'exitsUnits',
            'totalStockValue',
            'totalStockUnits',
            'totalReferences',
            'vendorActivity',
            'topMedications',
            'recentMovements',
            'categoryStats'
        ));
    }

    /**
     * Télécharge le rapport d'activité officiel en PDF
     */
    public function downloadPdf(Request $request)
    {
        $periodData = $this->resolveDateRange($request);
        $startDate = $periodData['start'];
        $endDate = $periodData['end'];
        $periodLabel = $periodData['label'];

        // Ventes sur la période
        $totalRevenue = (float) Sale::where('status', 'paid')->whereBetween('created_at', [$startDate, $endDate])->sum('total_amount');
        $totalSalesCount = Sale::where('status', 'paid')->whereBetween('created_at', [$startDate, $endDate])->count();
        $totalItemsSold = (int) DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->where('sales.status', 'paid')
            ->whereBetween('sales.created_at', [$startDate, $endDate])
            ->sum('sale_items.quantity');

        $entriesUnits = (int) StockMovement::where('type', 'entrée')->whereBetween('created_at', [$startDate, $endDate])->sum('quantity');
        $exitsUnits = (int) StockMovement::where('type', 'sortie')->whereBetween('created_at', [$startDate, $endDate])->sum('quantity');

        $medications = Medication::with('category')->orderBy('name')->get();
        $totalMedications = $medications->count();
        $totalStock = $medications->sum('stock_quantity');
        $stockValue = Medication::select(DB::raw('SUM(stock_quantity * unit_price) as total_val'))->value('total_val') ?? 0;

        // Vendeuses
        $vendors = User::whereIn('role', ['vendor', 'agent'])->get();
        $vendorActivity = [];
        foreach ($vendors as $v) {
            $vSalesCount = Sale::where('user_id', $v->id)->where('status', 'paid')->whereBetween('created_at', [$startDate, $endDate])->count();
            $vSalesRevenue = (float) Sale::where('user_id', $v->id)->where('status', 'paid')->whereBetween('created_at', [$startDate, $endDate])->sum('total_amount');
            $vendorActivity[] = [
                'name' => $v->name,
                'sales_count' => $vSalesCount,
                'sales_revenue' => $vSalesRevenue,
            ];
        }

        // Top médicaments
        $topMedications = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('medications', 'sale_items.medication_id', '=', 'medications.id')
            ->where('sales.status', 'paid')
            ->whereBetween('sales.created_at', [$startDate, $endDate])
            ->select('medications.code', 'medications.name', 'medications.dosage', DB::raw('SUM(sale_items.quantity) as total_qty'), DB::raw('SUM(sale_items.subtotal) as total_amount'))
            ->groupBy('medications.id', 'medications.code', 'medications.name', 'medications.dosage')
            ->orderByDesc('total_qty')
            ->take(10)
            ->get();

        $pdf = Pdf::loadView('pdf.stock_report', compact(
            'periodLabel',
            'startDate',
            'endDate',
            'totalRevenue',
            'totalSalesCount',
            'totalItemsSold',
            'entriesUnits',
            'exitsUnits',
            'medications',
            'totalMedications',
            'totalStock',
            'stockValue',
            'vendorActivity',
            'topMedications'
        ));

        return $pdf->download("rapport_activite_" . strtolower(str_replace(' ', '_', $periodLabel)) . "_" . date('Ymd') . ".pdf");
    }

    /**
     * Résout la plage de dates en fonction du filtre demandé
     */
    private function resolveDateRange(Request $request): array
    {
        $period = $request->input('period', 'month');

        if ($period === 'today') {
            return [
                'key' => 'today',
                'start' => Carbon::today()->startOfDay(),
                'end' => Carbon::today()->endOfDay(),
                'label' => "Aujourd'hui (" . Carbon::today()->translatedFormat('d F Y') . ")",
            ];
        }

        if ($period === 'week') {
            return [
                'key' => 'week',
                'start' => Carbon::now()->startOfWeek(),
                'end' => Carbon::now()->endOfWeek(),
                'label' => "Cette Semaine (du " . Carbon::now()->startOfWeek()->translatedFormat('d M') . " au " . Carbon::now()->endOfWeek()->translatedFormat('d M Y') . ")",
            ];
        }

        if ($period === 'last_week') {
            $lastWeek = Carbon::now()->subWeek();
            return [
                'key' => 'last_week',
                'start' => $lastWeek->copy()->startOfWeek(),
                'end' => $lastWeek->copy()->endOfWeek(),
                'label' => "Semaine Dernière (du " . $lastWeek->copy()->startOfWeek()->translatedFormat('d M') . " au " . $lastWeek->copy()->endOfWeek()->translatedFormat('d M Y') . ")",
            ];
        }

        if ($period === 'last_month') {
            $lastMonth = Carbon::now()->subMonth();
            return [
                'key' => 'last_month',
                'start' => $lastMonth->copy()->startOfMonth(),
                'end' => $lastMonth->copy()->endOfMonth(),
                'label' => "Mois Dernier (" . $lastMonth->translatedFormat('F Y') . ")",
            ];
        }

        if ($period === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
            $start = Carbon::parse($request->input('start_date'))->startOfDay();
            $end = Carbon::parse($request->input('end_date'))->endOfDay();
            return [
                'key' => 'custom',
                'start' => $start,
                'end' => $end,
                'label' => "Période du " . $start->translatedFormat('d/m/Y') . " au " . $end->translatedFormat('d/m/Y'),
            ];
        }

        // Par défaut : Ce mois-ci
        return [
            'key' => 'month',
            'start' => Carbon::now()->startOfMonth(),
            'end' => Carbon::now()->endOfMonth(),
            'label' => "Ce Mois-ci (" . Carbon::now()->translatedFormat('F Y') . ")",
        ];
    }
}
