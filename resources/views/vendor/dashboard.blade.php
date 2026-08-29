@extends('layouts.vendor')

@section('title', 'PharmaGestion - Dashboard Vendeuse')

@section('content')
<!-- KPI Grid (Screenshot 4) -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <!-- Card 1: Ventes du jour -->
    <div class="bg-surface-container-lowest border border-outline-variant p-4 rounded-xl shadow-sm flex flex-col gap-2">
        <div class="flex justify-between items-center">
            <p class="font-label-md text-label-md text-on-surface-variant uppercase font-bold">Ventes du jour</p>
            <span class="material-symbols-outlined text-primary">receipt_long</span>
        </div>
        <p class="font-display-lg text-display-lg text-on-surface font-bold">{{ $todaySalesCount ?? 42 }}</p>
    </div>

    <!-- Card 2: Montant Total (Highlighted Teal) -->
    <div class="bg-primary-container text-on-primary-container border border-primary p-4 rounded-xl shadow-sm flex flex-col gap-2 relative overflow-hidden">
        <div class="flex justify-between items-center relative z-10">
            <p class="font-label-md text-label-md uppercase opacity-90 font-bold">Montant Total</p>
            <span class="material-symbols-outlined">account_balance_wallet</span>
        </div>
        <p class="font-display-lg text-display-lg relative z-10 font-bold">
            {{ number_format($todayTotalAmount ?? 145500, 0, ',', ' ') }} <span class="font-title-lg text-title-lg">FCFA</span>
        </p>
    </div>

    <!-- Card 3: Articles Vendus -->
    <div class="bg-surface-container-lowest border border-outline-variant p-4 rounded-xl shadow-sm flex flex-col gap-2">
        <div class="flex justify-between items-center">
            <p class="font-label-md text-label-md text-on-surface-variant uppercase font-bold">Articles Vendus</p>
            <span class="material-symbols-outlined text-secondary">medication</span>
        </div>
        <p class="font-display-lg text-display-lg text-on-surface font-bold">{{ $todayItemsCount ?? 118 }}</p>
    </div>

    <!-- Card 4: Retours / Annul. -->
    <div class="bg-surface-container-lowest border border-outline-variant p-4 rounded-xl shadow-sm flex flex-col gap-2">
        <div class="flex justify-between items-center">
            <p class="font-label-md text-label-md text-on-surface-variant uppercase font-bold">Retours / Annul.</p>
            <span class="material-symbols-outlined text-error">assignment_return</span>
        </div>
        <p class="font-display-lg text-display-lg text-error font-bold">{{ $returnsCount ?? 2 }}</p>
    </div>
</div>

<!-- Main Content Area -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-lg">
    <!-- Chart Area (Screenshot 4) -->
    <div class="lg:col-span-2 bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm p-4 flex flex-col h-80">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-title-lg text-title-lg text-on-surface font-bold">Évolution des ventes (7 jours)</h3>
            <button class="p-2 rounded-lg hover:bg-surface-container-low transition-colors">
                <span class="material-symbols-outlined text-on-surface-variant">more_vert</span>
            </button>
        </div>
        <div class="flex-1 w-full flex items-end justify-between px-4 pb-4 gap-2 border-b border-outline-variant relative">
            <!-- Y Axis Labels -->
            <div class="absolute left-0 top-0 h-full flex flex-col justify-between text-xs text-on-surface-variant opacity-50 pb-4">
                <span>200k</span>
                <span>150k</span>
                <span>100k</span>
                <span>50k</span>
            </div>
            <div class="w-8 ml-8"></div>
            <!-- Bar Chart Representation -->
            <div class="w-full flex justify-between items-end h-full gap-4">
                <div class="w-full bg-primary-fixed opacity-60 rounded-t-sm h-[30%]"></div>
                <div class="w-full bg-primary-fixed opacity-60 rounded-t-sm h-[50%]"></div>
                <div class="w-full bg-primary-fixed opacity-60 rounded-t-sm h-[40%]"></div>
                <div class="w-full bg-primary-fixed opacity-60 rounded-t-sm h-[70%]"></div>
                <div class="w-full bg-primary-fixed opacity-60 rounded-t-sm h-[60%]"></div>
                <div class="w-full bg-primary-fixed opacity-60 rounded-t-sm h-[85%]"></div>
                <div class="w-full bg-primary rounded-t-sm h-[95%]"></div>
            </div>
        </div>
        <div class="flex justify-between px-4 mt-2 text-xs text-on-surface-variant ml-8">
            <span>Mer</span><span>Jeu</span><span>Ven</span><span>Sam</span><span>Dim</span><span>Lun</span><span class="font-bold text-primary">Mar</span>
        </div>
    </div>

    <!-- Recent Activity / Table (Screenshot 4) -->
    <div class="lg:col-span-1 bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden flex flex-col h-80">
        <div class="p-4 border-b border-outline-variant flex justify-between items-center bg-surface">
            <h3 class="font-title-lg text-title-lg text-on-surface font-bold">Dernières ventes</h3>
            <a class="font-label-md text-label-md text-primary hover:underline font-bold" href="{{ route('vendor.sales') }}">Voir tout</a>
        </div>
        <div class="flex-1 overflow-y-auto">
            <table class="w-full text-left border-collapse">
                <tbody>
                    @forelse($recentSales as $sale)
                        <tr class="border-b border-outline-variant hover:bg-surface-container-low transition-colors">
                            <td class="p-3">
                                <div class="font-body-sm text-body-sm font-bold text-on-surface">{{ $sale->reference }}</div>
                                <div class="text-xs text-on-surface-variant">{{ $sale->created_at->format('H:i') }}</div>
                            </td>
                            <td class="p-3 font-currency-md text-currency-md text-right font-bold">
                                {{ number_format($sale->total_amount, 0, ',', ' ') }}
                            </td>
                            <td class="p-3 text-right">
                                @if($sale->status === 'cancelled')
                                    <span class="inline-flex items-center gap-1 bg-error-container text-on-error-container text-[10px] font-bold px-2 py-1 rounded-full">
                                        <span class="w-2 h-2 rounded-full bg-error"></span> Annulé
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-surface-container-highest text-primary text-[10px] font-bold px-2 py-1 rounded-full">
                                        <span class="w-2 h-2 rounded-full bg-primary"></span> Payé
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="p-4 text-center text-on-surface-variant">Aucune vente enregistrée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
