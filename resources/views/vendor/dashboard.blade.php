@extends('layouts.vendor')

@section('title', 'PharmaGestion - Tableau de Bord Vendeuse')

@section('content')
<!-- Bannière d'accès rapide au Guichet -->
<div class="bg-gradient-to-r from-primary to-[#008080] rounded-2xl p-6 text-white shadow-md flex flex-col md:flex-row items-center justify-between gap-4">
    <div class="space-y-1 text-center md:text-left">
        <h2 class="text-xl font-extrabold">Prête pour les ventes au comptoir ?</h2>
        <p class="text-sm text-white/90">Accédez directement à l'interface Guichet avec recherche ultra-rapide et impression immédiate des tickets.</p>
    </div>
    <a href="{{ route('sales.pos') }}" class="px-6 py-3.5 bg-white text-primary font-extrabold text-sm rounded-xl shadow hover:bg-surface-low transition-all flex items-center gap-2 shrink-0">
        <span class="material-symbols-outlined text-primary text-[22px]">point_of_sale</span>
        <span>Ouvrir le Guichet de Vente</span>
    </a>
</div>

<!-- Grille des 4 KPIs du Jour -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    
    <!-- KPI 1 : Ventes du Jour -->
    <div class="bg-surface-lowest border border-outline-variant/80 p-5 rounded-2xl shadow-xs flex flex-col justify-between">
        <div class="flex items-center justify-between">
            <span class="text-xs uppercase font-bold text-gray-500">Ventes du jour</span>
            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-[20px]">receipt_long</span>
            </div>
        </div>
        <div class="mt-3">
            <h3 class="text-2xl font-extrabold text-gray-900">{{ number_format($todaySalesCount) }}</h3>
            <p class="text-xs text-gray-400 mt-0.5">Tickets enregistrés aujourd'hui</p>
        </div>
    </div>

    <!-- KPI 2 : Chiffre d'Affaires du Jour -->
    <div class="bg-primary text-white p-5 rounded-2xl shadow-md flex flex-col justify-between relative overflow-hidden">
        <div class="flex items-center justify-between relative z-10">
            <span class="text-xs uppercase font-bold text-white/80">Recette du jour</span>
            <div class="w-8 h-8 rounded-lg bg-white/20 text-white flex items-center justify-center">
                <span class="material-symbols-outlined text-[20px]">payments</span>
            </div>
        </div>
        <div class="mt-3 relative z-10">
            <h3 class="text-2xl font-extrabold">{{ number_format($todayTotalAmount, 0, ',', ' ') }} <span class="text-sm font-normal">FCFA</span></h3>
            <p class="text-xs text-white/80 mt-0.5">Total encaissé par vous</p>
        </div>
        <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
    </div>

    <!-- KPI 3 : Articles Délivrés -->
    <div class="bg-surface-lowest border border-outline-variant/80 p-5 rounded-2xl shadow-xs flex flex-col justify-between">
        <div class="flex items-center justify-between">
            <span class="text-xs uppercase font-bold text-gray-500">Articles Vendus</span>
            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-[20px]">medication</span>
            </div>
        </div>
        <div class="mt-3">
            <h3 class="text-2xl font-extrabold text-gray-900">{{ number_format($todayItemsCount) }}</h3>
            <p class="text-xs text-gray-400 mt-0.5">Boîtes / unités délivrées</p>
        </div>
    </div>

    <!-- KPI 4 : Panier Moyen -->
    <div class="bg-surface-lowest border border-outline-variant/80 p-5 rounded-2xl shadow-xs flex flex-col justify-between">
        <div class="flex items-center justify-between">
            <span class="text-xs uppercase font-bold text-gray-500">Panier Moyen</span>
            <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-[20px]">trending_up</span>
            </div>
        </div>
        <div class="mt-3">
            <h3 class="text-2xl font-extrabold text-gray-900">{{ number_format($avgTicket, 0, ',', ' ') }} <span class="text-xs font-bold text-gray-500">FCFA</span></h3>
            <p class="text-xs text-gray-400 mt-0.5">Montant moyen par client</p>
        </div>
    </div>
</div>

<!-- Graphique d'évolution & Répartition des encaissements -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Graphique 7 jours -->
    <div class="lg:col-span-2 bg-surface-lowest border border-outline-variant/80 rounded-2xl p-6 shadow-xs flex flex-col justify-between">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-bold text-base text-gray-900">Évolution de vos Ventes (7 derniers jours)</h3>
                <p class="text-xs text-gray-400">Recette journalière en FCFA générée par votre compte</p>
            </div>
            <a href="{{ route('vendor.reports') }}" class="text-xs font-bold text-primary hover:underline flex items-center gap-1">
                <span>Rapports</span>
                <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
            </a>
        </div>

        <!-- Barres CSS Chart -->
        <div class="h-48 flex items-end justify-between gap-3 pt-6 pb-2 border-b border-gray-100">
            @foreach($days as $idx => $dayLabel)
                @php
                    $amt = $weeklyAmounts[$idx] ?? 0;
                    $percent = $maxWeeklyAmount > 0 ? max(8, round(($amt / $maxWeeklyAmount) * 100)) : 8;
                    $isToday = ($idx === count($days) - 1);
                @endphp
                <div class="flex-1 flex flex-col items-center gap-2 h-full justify-end group relative">
                    <!-- Tooltip hover -->
                    <div class="opacity-0 group-hover:opacity-100 absolute -top-8 bg-gray-900 text-white text-[10px] font-bold py-1 px-2 rounded pointer-events-none transition-opacity z-20 whitespace-nowrap">
                        {{ number_format($amt, 0, ',', ' ') }} FCFA
                    </div>
                    <!-- Barre -->
                    <div 
                        style="height: {{ $percent }}%;" 
                        class="w-full max-w-[48px] rounded-t-lg transition-all {{ $isToday ? 'bg-primary shadow-sm' : 'bg-primary/30 hover:bg-primary/60' }}">
                    </div>
                    <!-- Label jour -->
                    <span class="text-xs font-medium {{ $isToday ? 'font-bold text-primary' : 'text-gray-400' }}">{{ $dayLabel }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Répartition par moyen de paiement aujourd'hui -->
    <div class="bg-surface-lowest border border-outline-variant/80 rounded-2xl p-6 shadow-xs flex flex-col justify-between">
        <div>
            <h3 class="font-bold text-base text-gray-900 mb-1">Encaissements du jour</h3>
            <p class="text-xs text-gray-400 mb-4">Répartition par canal de paiement</p>

            <div class="space-y-3.5">
                <!-- Espèces -->
                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-bold">
                        <span class="flex items-center gap-1 text-gray-700">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span> Espèces
                        </span>
                        <span class="text-gray-900">{{ number_format($cashAmount, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-emerald-500 h-full rounded-full" style="width: {{ $todayTotalAmount > 0 ? ($cashAmount / $todayTotalAmount) * 100 : 0 }}%;"></div>
                    </div>
                </div>

                <!-- Wave -->
                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-bold">
                        <span class="flex items-center gap-1 text-gray-700">
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-500 inline-block"></span> Wave
                        </span>
                        <span class="text-gray-900">{{ number_format($waveAmount, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-blue-500 h-full rounded-full" style="width: {{ $todayTotalAmount > 0 ? ($waveAmount / $todayTotalAmount) * 100 : 0 }}%;"></div>
                    </div>
                </div>

                <!-- Orange Money -->
                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-bold">
                        <span class="flex items-center gap-1 text-gray-700">
                            <span class="w-2.5 h-2.5 rounded-full bg-orange-500 inline-block"></span> Orange Money
                        </span>
                        <span class="text-gray-900">{{ number_format($omAmount, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-orange-500 h-full rounded-full" style="width: {{ $todayTotalAmount > 0 ? ($omAmount / $todayTotalAmount) * 100 : 0 }}%;"></div>
                    </div>
                </div>

                <!-- CMU / Autre -->
                @if($otherAmount > 0)
                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-bold">
                        <span class="flex items-center gap-1 text-gray-700">
                            <span class="w-2.5 h-2.5 rounded-full bg-purple-500 inline-block"></span> CMU / Autres
                        </span>
                        <span class="text-gray-900">{{ number_format($otherAmount, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-purple-500 h-full rounded-full" style="width: {{ ($otherAmount / $todayTotalAmount) * 100 }}%;"></div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 mt-4 flex items-center justify-between text-xs">
            <span class="text-gray-400 font-medium">Total encaissé :</span>
            <span class="font-extrabold text-primary text-sm">{{ number_format($todayTotalAmount, 0, ',', ' ') }} FCFA</span>
        </div>
    </div>
</div>

<!-- Dernières Transactions Réalisées -->
<div class="bg-surface-lowest border border-outline-variant/80 rounded-2xl shadow-xs overflow-hidden">
    <div class="p-5 border-b border-gray-100 flex items-center justify-between bg-surface-low/50">
        <div>
            <h3 class="font-bold text-base text-gray-900">Dernières Ventes Enregistrées</h3>
            <p class="text-xs text-gray-400">Vos dernières opérations de comptoir</p>
        </div>
        <a href="{{ route('vendor.sales') }}" class="px-3.5 py-1.5 bg-white hover:bg-gray-50 border border-outline-variant text-xs font-bold text-primary rounded-lg transition-colors flex items-center gap-1">
            <span>Historique complet</span>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm border-collapse">
            <thead>
                <tr class="bg-gray-50/80 text-[11px] font-bold text-gray-500 uppercase border-b border-gray-100">
                    <th class="py-3 px-5">Réf. Ticket</th>
                    <th class="py-3 px-5">Heure</th>
                    <th class="py-3 px-5">Bénéficiaire / Patient</th>
                    <th class="py-3 px-5">Articles</th>
                    <th class="py-3 px-5">Paiement</th>
                    <th class="py-3 px-5 text-right">Montant Total</th>
                    <th class="py-3 px-5 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($recentSales as $sale)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-3.5 px-5 font-mono text-xs font-bold text-gray-900">{{ $sale->reference }}</td>
                        <td class="py-3.5 px-5 text-xs text-gray-500">{{ $sale->created_at->format('H:i') }}</td>
                        <td class="py-3.5 px-5 text-xs font-medium text-gray-700">{{ $sale->patient_name ?? 'Client Comptoir' }}</td>
                        <td class="py-3.5 px-5 text-xs text-gray-600">
                            <span class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded-full font-bold text-[11px]">
                                {{ $sale->items->sum('quantity') }} article(s)
                            </span>
                        </td>
                        <td class="py-3.5 px-5 text-xs font-medium">
                            <span class="capitalize px-2 py-0.5 rounded-md text-[11px] font-bold bg-blue-50 text-blue-700">
                                {{ $sale->payment_method }}
                            </span>
                        </td>
                        <td class="py-3.5 px-5 text-right font-bold text-primary">{{ number_format($sale->total_amount, 0, ',', ' ') }} FCFA</td>
                        <td class="py-3.5 px-5 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <a 
                                    href="{{ route('sales.ticket', $sale->id) }}" 
                                    target="_blank"
                                    class="p-1.5 text-gray-500 hover:text-primary hover:bg-primary/10 rounded-lg transition-colors" 
                                    title="Imprimer Ticket">
                                    <span class="material-symbols-outlined text-[18px]">receipt</span>
                                </a>
                                <a 
                                    href="{{ route('sales.pdf', $sale->id) }}" 
                                    class="p-1.5 text-gray-500 hover:text-primary hover:bg-primary/10 rounded-lg transition-colors" 
                                    title="Reçu PDF">
                                    <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-400">
                            <span class="material-symbols-outlined text-3xl text-gray-300 mb-1 block">receipt_long</span>
                            Aucune vente enregistrée pour le moment.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
