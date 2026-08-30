<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Medication;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorDashboardController extends Controller
{
    /**
     * Dashboard d'accueil de la vendeuse
     */
    public function index()
    {
        $user = auth()->user();
        $today = Carbon::today();

        // Ventes du jour de la vendeuse
        $todaySales = Sale::where('user_id', $user->id)
            ->whereDate('created_at', $today)
            ->where('status', 'paid')
            ->get();

        $todaySalesCount = $todaySales->count();
        $todayTotalAmount = $todaySales->sum('total_amount');

        // Nombre d'articles vendus aujourd'hui
        $todaySaleIds = $todaySales->pluck('id');
        $todayItemsCount = SaleItem::whereIn('sale_id', $todaySaleIds)->sum('quantity');

        // Panier moyen
        $avgTicket = $todaySalesCount > 0 ? round($todayTotalAmount / $todaySalesCount) : 0;

        // Ventes récentes (dernières 10 transactions)
        $recentSales = Sale::where('user_id', $user->id)
            ->with(['items.medication'])
            ->latest()
            ->take(10)
            ->get();

        // Données des 7 derniers jours pour le graphique
        $days = [];
        $weeklyAmounts = [];
        $maxWeeklyAmount = 1;

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dayName = $date->translatedFormat('D'); // Lun, Mar, Mer...
            $dayTotal = Sale::where('user_id', $user->id)
                ->whereDate('created_at', $date)
                ->where('status', 'paid')
                ->sum('total_amount');

            $days[] = ucfirst($dayName);
            $weeklyAmounts[] = $dayTotal;
            if ($dayTotal > $maxWeeklyAmount) {
                $maxWeeklyAmount = $dayTotal;
            }
        }

        // Répartition par mode de paiement aujourd'hui
        $cashAmount = $todaySales->where('payment_method', 'espèces')->sum('total_amount');
        $waveAmount = $todaySales->where('payment_method', 'wave')->sum('total_amount');
        $omAmount = $todaySales->where('payment_method', 'orange_money')->sum('total_amount');
        $otherAmount = $todaySales->whereNotIn('payment_method', ['espèces', 'wave', 'orange_money'])->sum('total_amount');

        return view('vendor.dashboard', compact(
            'todaySalesCount',
            'todayTotalAmount',
            'todayItemsCount',
            'avgTicket',
            'recentSales',
            'days',
            'weeklyAmounts',
            'maxWeeklyAmount',
            'cashAmount',
            'waveAmount',
            'omAmount',
            'otherAmount'
        ));
    }

    /**
     * Historique détaillé des ventes de la vendeuse
     */
    public function sales(Request $request)
    {
        $user = auth()->user();

        $query = Sale::where('user_id', $user->id)
            ->with(['items.medication']);

        // Recherche par référence ou nom patient
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhere('patient_name', 'like', "%{$search}%");
            });
        }

        // Filtre par méthode de paiement
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filtre par date
        if ($request->filled('filter_date')) {
            if ($request->filter_date === 'today') {
                $query->whereDate('created_at', Carbon::today());
            } elseif ($request->filter_date === 'week') {
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            } elseif ($request->filter_date === 'month') {
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
            }
        }

        $mySales = $query->latest()->paginate(15)->withQueryString();

        $totalSalesCount = Sale::where('user_id', $user->id)->count();
        $totalSalesRevenue = Sale::where('user_id', $user->id)->where('status', 'paid')->sum('total_amount');

        return view('vendor.sales', compact('mySales', 'totalSalesCount', 'totalSalesRevenue'));
    }

    /**
     * Consultation des médicaments et prix publics au comptoir
     */
    public function medications(Request $request)
    {
        $categories = Category::orderBy('name')->get();

        $query = Medication::with('category')
            ->where('stock_quantity', '>', 0);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('dosage', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $medications = $query->orderBy('name')->paginate(20)->withQueryString();

        return view('vendor.medications', compact('medications', 'categories'));
    }

    /**
     * Rapports et synthèse de vente de la vendeuse
     */
    public function reports()
    {
        $user = auth()->user();

        $allSales = Sale::where('user_id', $user->id)->where('status', 'paid')->get();
        $totalRevenue = $allSales->sum('total_amount');
        $totalSalesCount = $allSales->count();

        $todayRevenue = Sale::where('user_id', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->where('status', 'paid')
            ->sum('total_amount');

        $monthRevenue = Sale::where('user_id', $user->id)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->where('status', 'paid')
            ->sum('total_amount');

        $topMedications = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('medications', 'sale_items.medication_id', '=', 'medications.id')
            ->where('sales.user_id', $user->id)
            ->where('sales.status', 'paid')
            ->select('medications.name', 'medications.dosage', DB::raw('SUM(sale_items.quantity) as total_qty'), DB::raw('SUM(sale_items.subtotal) as total_amount'))
            ->groupBy('medications.id', 'medications.name', 'medications.dosage')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        return view('vendor.reports', compact(
            'totalRevenue',
            'todayRevenue',
            'monthRevenue',
            'totalSalesCount',
            'topMedications'
        ));
    }
}
