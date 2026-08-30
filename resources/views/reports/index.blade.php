@extends('layouts.app')

@section('title', 'PharmaGestion - Rapports & Statistiques')
@section('page-title', 'Rapports d\'Activité')

@section('content')
<!-- Page Header & Actions -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
    <div>
        <h2 class="text-xl font-bold text-gray-900">Rapports d'Activité & Bilans Périodiques</h2>
        <p class="text-xs text-gray-500 mt-0.5">Synthèse chiffrée des ventes, des réassorts et performances par vendeuse.</p>
    </div>
    <a 
        href="{{ route('reports.pdf', request()->query()) }}" 
        target="_blank"
        class="bg-primary hover:bg-primary-hover text-white px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-1.5 shadow-sm transition-all cursor-pointer">
        <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span>
        <span>Télécharger le Rapport (PDF)</span>
    </a>
</div>

<!-- ======================= SÉLECTEUR DE PÉRIODE ======================= -->
<div class="bg-surface-lowest p-4 rounded-2xl border border-outline-variant/80 shadow-xs mb-6 space-y-3">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none text-xs">
            <span class="text-gray-400 font-bold uppercase text-[11px] mr-1">Période :</span>
            
            <a 
                href="{{ route('reports.index', ['period' => 'week']) }}" 
                class="px-3.5 py-1.5 rounded-xl font-bold transition-all shrink-0 {{ $periodKey === 'week' ? 'bg-primary text-white shadow-xs' : 'bg-surface-low text-gray-700 hover:bg-surface-container border border-outline-variant/60' }}">
                📅 Cette Semaine
            </a>

            <a 
                href="{{ route('reports.index', ['period' => 'month']) }}" 
                class="px-3.5 py-1.5 rounded-xl font-bold transition-all shrink-0 {{ $periodKey === 'month' ? 'bg-primary text-white shadow-xs' : 'bg-surface-low text-gray-700 hover:bg-surface-container border border-outline-variant/60' }}">
                📅 Ce Mois-ci
            </a>

            <a 
                href="{{ route('reports.index', ['period' => 'last_week']) }}" 
                class="px-3.5 py-1.5 rounded-xl font-bold transition-all shrink-0 {{ $periodKey === 'last_week' ? 'bg-primary text-white shadow-xs' : 'bg-surface-low text-gray-700 hover:bg-surface-container border border-outline-variant/60' }}">
                Semaine Dernière
            </a>

            <a 
                href="{{ route('reports.index', ['period' => 'last_month']) }}" 
                class="px-3.5 py-1.5 rounded-xl font-bold transition-all shrink-0 {{ $periodKey === 'last_month' ? 'bg-primary text-white shadow-xs' : 'bg-surface-low text-gray-700 hover:bg-surface-container border border-outline-variant/60' }}">
                Mois Dernier
            </a>

            <a 
                href="{{ route('reports.index', ['period' => 'today']) }}" 
                class="px-3.5 py-1.5 rounded-xl font-bold transition-all shrink-0 {{ $periodKey === 'today' ? 'bg-primary text-white shadow-xs' : 'bg-surface-low text-gray-700 hover:bg-surface-container border border-outline-variant/60' }}">
                Aujourd'hui
            </a>
        </div>

        <div class="text-xs font-bold text-primary bg-primary/10 px-3 py-1.5 rounded-xl flex items-center gap-1.5 shrink-0 self-start sm:self-auto">
            <span class="material-symbols-outlined text-[16px]">date_range</span>
            <span>{{ $periodLabel }}</span>
        </div>
    </div>

    <!-- Filtre Date personnalisée -->
    <form action="{{ route('reports.index') }}" method="GET" class="pt-3 border-t border-gray-100 flex flex-wrap items-center gap-3 text-xs">
        <input type="hidden" name="period" value="custom">
        <span class="font-bold text-gray-500 uppercase text-[11px]">Plage sur-mesure :</span>
        <div class="flex items-center gap-2">
            <label class="text-gray-500">Du</label>
            <input type="date" name="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}" required class="bg-gray-50 border border-outline-variant rounded-lg px-2.5 py-1 text-xs focus:bg-white focus:border-primary font-medium">
        </div>
        <div class="flex items-center gap-2">
            <label class="text-gray-500">Au</label>
            <input type="date" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}" required class="bg-gray-50 border border-outline-variant rounded-lg px-2.5 py-1 text-xs focus:bg-white focus:border-primary font-medium">
        </div>
        <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-bold px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1">
            <span class="material-symbols-outlined text-[14px]">search</span>
            <span>Appliquer</span>
        </button>
    </form>
</div>

<!-- ======================= GRILLE DES KPIS D'ACTIVITÉ ======================= -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    
    <!-- KPI 1 : CA Ventes -->
    <div class="bg-primary text-white p-5 rounded-2xl shadow-md flex flex-col justify-between relative overflow-hidden">
        <div class="flex items-center justify-between relative z-10">
            <span class="text-xs uppercase font-bold text-white/80">Recette des Ventes</span>
            <div class="w-8 h-8 rounded-lg bg-white/20 text-white flex items-center justify-center">
                <span class="material-symbols-outlined text-[20px]">payments</span>
            </div>
        </div>
        <div class="mt-3 relative z-10">
            <h3 class="text-2xl font-extrabold">{{ number_format($totalRevenue, 0, ',', ' ') }} <span class="text-sm font-normal">FCFA</span></h3>
            <p class="text-xs text-white/80 mt-0.5">{{ number_format($totalSalesCount) }} ticket(s) sur la période</p>
        </div>
        <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
    </div>

    <!-- KPI 2 : Médicaments Vendus -->
    <div class="bg-surface-lowest border border-outline-variant/80 p-5 rounded-2xl shadow-xs flex flex-col justify-between">
        <div class="flex items-center justify-between">
            <span class="text-xs uppercase font-bold text-gray-400">Unités Médicaments Vendues</span>
            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-[20px]">medication</span>
            </div>
        </div>
        <div class="mt-3">
            <h3 class="text-2xl font-extrabold text-gray-900">{{ number_format($totalItemsSold) }} <span class="text-xs font-bold text-gray-500">unités</span></h3>
            <p class="text-xs text-gray-400 mt-0.5">Délivrées au guichet comptoir</p>
        </div>
    </div>

    <!-- KPI 3 : Réassorts & Entrées -->
    <div class="bg-surface-lowest border border-outline-variant/80 p-5 rounded-2xl shadow-xs flex flex-col justify-between">
        <div class="flex items-center justify-between">
            <span class="text-xs uppercase font-bold text-gray-400">Entrées en Stock (Réassort)</span>
            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-[20px]">login</span>
            </div>
        </div>
        <div class="mt-3">
            <h3 class="text-2xl font-extrabold text-emerald-700">+{{ number_format($entriesUnits) }} <span class="text-xs font-bold text-gray-500">unités</span></h3>
            <p class="text-xs text-gray-400 mt-0.5">Réceptions enregistrées sur la période</p>
        </div>
    </div>

    <!-- KPI 4 : Valeur du Stock -->
    <div class="bg-surface-lowest border border-outline-variant/80 p-5 rounded-2xl shadow-xs flex flex-col justify-between">
        <div class="flex items-center justify-between">
            <span class="text-xs uppercase font-bold text-gray-400">Valorisation Globale Actuelle</span>
            <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-[20px]">account_balance_wallet</span>
            </div>
        </div>
        <div class="mt-3">
            <h3 class="text-2xl font-extrabold text-gray-900">{{ number_format($totalStockValue, 0, ',', ' ') }} <span class="text-xs font-bold text-gray-500">FCFA</span></h3>
            <p class="text-xs text-gray-400 mt-0.5">{{ number_format($totalStockUnits) }} unités réparties en {{ $totalReferences }} réf.</p>
        </div>
    </div>
</div>

<!-- ======================= DEUXIÈME SECTION : VENDEUSES & ENCAISSEMENTS ======================= -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    
    <!-- Activité par Vendeuse -->
    <div class="lg:col-span-2 bg-surface-lowest border border-outline-variant/80 rounded-2xl shadow-xs overflow-hidden flex flex-col justify-between">
        <div class="p-5 border-b border-gray-100 bg-surface-low/50 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-base text-gray-900">Activité & Ventes par Vendeuse</h3>
                <p class="text-xs text-gray-400">Répartition des encaissements sur la période sélectionnée</p>
            </div>
            <a href="{{ route('admin.vendors.index') }}" class="text-xs font-bold text-primary hover:underline">
                Gérer les vendeuses
            </a>
        </div>

        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 text-[11px] font-bold text-gray-500 uppercase border-b border-gray-100">
                        <th class="py-3 px-5">Vendeuse</th>
                        <th class="py-3 px-5 text-right">Nombre de Tickets</th>
                        <th class="py-3 px-5 text-right">Chiffre d'Affaires Généré</th>
                        <th class="py-3 px-5 text-right">Part du Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($vendorActivity as $va)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-3.5 px-5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center font-bold text-xs">
                                        {{ substr($va['user']->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-xs text-gray-900">{{ $va['user']->name }}</p>
                                        <p class="text-[10px] text-gray-400">{{ $va['user']->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-5 text-right font-bold text-xs text-gray-700">
                                {{ number_format($va['sales_count']) }} vente(s)
                            </td>
                            <td class="py-3.5 px-5 text-right font-extrabold text-xs text-primary">
                                {{ number_format($va['sales_revenue'], 0, ',', ' ') }} FCFA
                            </td>
                            <td class="py-3.5 px-5 text-right">
                                <span class="bg-gray-100 text-gray-700 text-[11px] font-bold px-2 py-0.5 rounded-full">
                                    {{ $va['share'] }} %
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-gray-400">Aucune vente enregistrée sur cette période.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Répartition par Mode de Paiement -->
    <div class="bg-surface-lowest border border-outline-variant/80 rounded-2xl p-6 shadow-xs flex flex-col justify-between">
        <div>
            <h3 class="font-bold text-base text-gray-900 mb-1">Modes d'Encaissement</h3>
            <p class="text-xs text-gray-400 mb-4">Canaux de règlement utilisés</p>

            <div class="space-y-4">
                <!-- Espèces -->
                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-bold">
                        <span class="flex items-center gap-1.5 text-gray-700">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span> Espèces
                        </span>
                        <span class="text-gray-900">{{ number_format($cashTotal, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-emerald-500 h-full rounded-full" style="width: {{ $totalRevenue > 0 ? ($cashTotal / $totalRevenue) * 100 : 0 }}%;"></div>
                    </div>
                </div>

                <!-- Wave -->
                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-bold">
                        <span class="flex items-center gap-1.5 text-gray-700">
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-500 inline-block"></span> Wave
                        </span>
                        <span class="text-gray-900">{{ number_format($waveTotal, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-blue-500 h-full rounded-full" style="width: {{ $totalRevenue > 0 ? ($waveTotal / $totalRevenue) * 100 : 0 }}%;"></div>
                    </div>
                </div>

                <!-- Orange Money -->
                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-bold">
                        <span class="flex items-center gap-1.5 text-gray-700">
                            <span class="w-2.5 h-2.5 rounded-full bg-orange-500 inline-block"></span> Orange Money
                        </span>
                        <span class="text-gray-900">{{ number_format($omTotal, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-orange-500 h-full rounded-full" style="width: {{ $totalRevenue > 0 ? ($omTotal / $totalRevenue) * 100 : 0 }}%;"></div>
                    </div>
                </div>

                <!-- Autres / CMU -->
                @if($otherTotal > 0)
                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-bold">
                        <span class="flex items-center gap-1.5 text-gray-700">
                            <span class="w-2.5 h-2.5 rounded-full bg-purple-500 inline-block"></span> CMU / Autres
                        </span>
                        <span class="text-gray-900">{{ number_format($otherTotal, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-purple-500 h-full rounded-full" style="width: {{ ($otherTotal / $totalRevenue) * 100 }}%;"></div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 mt-4 flex items-center justify-between text-xs">
            <span class="text-gray-400 font-medium">Recette totale période :</span>
            <span class="font-extrabold text-primary text-sm">{{ number_format($totalRevenue, 0, ',', ' ') }} FCFA</span>
        </div>
    </div>
</div>

<!-- ======================= TROISIÈME SECTION : TOP MÉDICAMENTS VENDUS ======================= -->
<div class="bg-surface-lowest border border-outline-variant/80 rounded-2xl shadow-xs overflow-hidden mb-6">
    <div class="p-5 border-b border-gray-100 bg-surface-low/50 flex items-center justify-between">
        <div>
            <h3 class="font-bold text-base text-gray-900">Top 10 des Médicaments les Plus Vendus</h3>
            <p class="text-xs text-gray-400">Classement selon le volume de délivrance sur la période</p>
        </div>
        <span class="text-xs font-bold text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
            {{ count($topMedications) }} produit(s)
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm border-collapse">
            <thead>
                <tr class="bg-gray-50/80 text-[11px] font-bold text-gray-500 uppercase border-b border-gray-100">
                    <th class="py-3.5 px-5">Rang</th>
                    <th class="py-3.5 px-5">Code</th>
                    <th class="py-3.5 px-5">Médicament</th>
                    <th class="py-3.5 px-5">Dosage & Forme</th>
                    <th class="py-3.5 px-5 text-right">Quantité Vendue</th>
                    <th class="py-3.5 px-5 text-right">Chiffre d'Affaires Généré</th>
                    <th class="py-3.5 px-5 text-right">Stock Restant</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($topMedications as $idx => $med)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-3.5 px-5 font-bold text-xs text-gray-400">#{{ $idx + 1 }}</td>
                        <td class="py-3.5 px-5 font-mono text-xs font-bold text-gray-400">{{ $med->code }}</td>
                        <td class="py-3.5 px-5 font-bold text-gray-900 text-xs">{{ $med->name }}</td>
                        <td class="py-3.5 px-5 text-xs text-gray-600">{{ $med->dosage }} ({{ $med->form }})</td>
                        <td class="py-3.5 px-5 text-right">
                            <span class="bg-primary/10 text-primary px-2.5 py-0.5 rounded-full font-extrabold text-xs">
                                {{ number_format($med->total_qty) }} unités
                            </span>
                        </td>
                        <td class="py-3.5 px-5 text-right font-extrabold text-primary text-xs">
                            {{ number_format($med->total_amount, 0, ',', ' ') }} FCFA
                        </td>
                        <td class="py-3.5 px-5 text-right text-xs font-bold {{ $med->stock_quantity <= 0 ? 'text-error' : 'text-gray-700' }}">
                            {{ number_format($med->stock_quantity) }} dispo
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-400">Aucune vente enregistrée sur cette période.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- ======================= QUATRIÈME SECTION : CATÉGORIES ======================= -->
<div class="bg-surface-lowest rounded-2xl border border-outline-variant/80 p-6 shadow-xs">
    <h3 class="font-bold text-base text-gray-900 mb-1">Répartition des Médicaments par Catégorie</h3>
    <p class="text-xs text-gray-400 mb-4">Structure du catalogue de la pharmacie</p>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
        @foreach($categoryStats as $cat)
            <div class="p-3 rounded-xl border border-outline-variant/60 bg-surface-low flex justify-between items-center">
                <span class="text-xs font-bold text-gray-800">{{ $cat->name }}</span>
                <span class="px-2 py-0.5 rounded-full bg-primary-container text-white font-bold text-[11px]">
                    {{ $cat->medications_count }} réf.
                </span>
            </div>
        @endforeach
    </div>
</div>
@endsection
