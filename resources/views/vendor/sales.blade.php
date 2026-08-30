@extends('layouts.vendor')

@section('title', 'PharmaGestion - Mes Ventes')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-900">Mes Ventes & Délivrances</h2>
        <p class="text-xs text-gray-500 mt-0.5">Historique complet de toutes vos opérations effectuées au guichet.</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('sales.pos') }}" class="bg-primary hover:bg-primary-hover text-white font-bold text-xs py-2.5 px-4 rounded-xl flex items-center gap-1.5 shadow-sm transition-all">
            <span class="material-symbols-outlined text-[18px]">add_circle</span>
            <span>Nouvelle Vente</span>
        </a>
    </div>
</div>

<!-- Résumé Global Vendeuse -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
    <div class="bg-surface-lowest border border-outline-variant/80 p-4 rounded-xl shadow-xs flex items-center justify-between">
        <div>
            <p class="text-xs uppercase font-bold text-gray-400">Total Ventes Réalisées</p>
            <h3 class="text-xl font-extrabold text-gray-900 mt-1">{{ number_format($totalSalesCount) }}</h3>
        </div>
        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
            <span class="material-symbols-outlined text-[22px]">receipt</span>
        </div>
    </div>
    <div class="bg-surface-lowest border border-outline-variant/80 p-4 rounded-xl shadow-xs flex items-center justify-between">
        <div>
            <p class="text-xs uppercase font-bold text-gray-400">Chiffre d'Affaires Total</p>
            <h3 class="text-xl font-extrabold text-primary mt-1">{{ number_format($totalSalesRevenue, 0, ',', ' ') }} FCFA</h3>
        </div>
        <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
            <span class="material-symbols-outlined text-[22px]">payments</span>
        </div>
    </div>
</div>

<!-- Filtres de Recherche -->
<div class="bg-surface-lowest border border-outline-variant/80 rounded-xl p-4 shadow-xs mb-6">
    <form action="{{ route('vendor.sales') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
        <div>
            <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Recherche</label>
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Réf ticket, nom patient..." 
                class="w-full text-xs bg-gray-50 border border-outline-variant rounded-lg px-3 py-2 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary font-medium"
            />
        </div>

        <div>
            <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Période</label>
            <select name="filter_date" class="w-full text-xs bg-gray-50 border border-outline-variant rounded-lg px-3 py-2 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary font-medium">
                <option value="">Toutes les dates</option>
                <option value="today" {{ request('filter_date') === 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                <option value="week" {{ request('filter_date') === 'week' ? 'selected' : '' }}>Cette semaine</option>
                <option value="month" {{ request('filter_date') === 'month' ? 'selected' : '' }}>Ce mois-ci</option>
            </select>
        </div>

        <div>
            <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Mode de Paiement</label>
            <select name="payment_method" class="w-full text-xs bg-gray-50 border border-outline-variant rounded-lg px-3 py-2 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary font-medium">
                <option value="">Tous les modes</option>
                <option value="espèces" {{ request('payment_method') === 'espèces' ? 'selected' : '' }}>Espèces</option>
                <option value="wave" {{ request('payment_method') === 'wave' ? 'selected' : '' }}>Wave</option>
                <option value="orange_money" {{ request('payment_method') === 'orange_money' ? 'selected' : '' }}>Orange Money</option>
                <option value="cmu" {{ request('payment_method') === 'cmu' ? 'selected' : '' }}>CMU</option>
            </select>
        </div>

        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 bg-primary hover:bg-primary-hover text-white text-xs font-bold py-2 px-3 rounded-lg shadow-xs transition-colors flex items-center justify-center gap-1">
                <span class="material-symbols-outlined text-[16px]">filter_list</span>
                <span>Filtrer</span>
            </button>
            <a href="{{ route('vendor.sales') }}" class="p-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg transition-colors" title="Réinitialiser">
                <span class="material-symbols-outlined text-[16px]">refresh</span>
            </a>
        </div>
    </form>
</div>

<!-- Table des Ventes -->
<div class="bg-surface-lowest border border-outline-variant/80 rounded-2xl shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm border-collapse">
            <thead>
                <tr class="bg-gray-50/80 text-[11px] font-bold text-gray-500 uppercase border-b border-gray-100">
                    <th class="py-3.5 px-5">Réf. Ticket</th>
                    <th class="py-3.5 px-5">Date & Heure</th>
                    <th class="py-3.5 px-5">Bénéficiaire / Patient</th>
                    <th class="py-3.5 px-5">Détail des Médicaments</th>
                    <th class="py-3.5 px-5">Paiement</th>
                    <th class="py-3.5 px-5 text-right">Montant Total</th>
                    <th class="py-3.5 px-5 text-center">Reçus</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($mySales as $sale)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-3.5 px-5 font-mono text-xs font-bold text-gray-900">{{ $sale->reference }}</td>
                        <td class="py-3.5 px-5 text-xs text-gray-500">{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                        <td class="py-3.5 px-5 text-xs font-medium text-gray-800">{{ $sale->patient_name ?? 'Client Comptoir' }}</td>
                        <td class="py-3.5 px-5 text-xs">
                            <div class="space-y-1 max-w-xs">
                                @foreach($sale->items as $item)
                                    <div class="text-[11px] text-gray-700 flex justify-between gap-2">
                                        <span class="font-medium truncate">• {{ $item->medication->name ?? 'Article' }} ({{ $item->medication->dosage ?? '' }})</span>
                                        <span class="text-gray-400 font-bold shrink-0">×{{ $item->quantity }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                        <td class="py-3.5 px-5 text-xs">
                            <span class="capitalize px-2 py-0.5 rounded-md text-[11px] font-bold bg-blue-50 text-blue-700">
                                {{ $sale->payment_method }}
                            </span>
                        </td>
                        <td class="py-3.5 px-5 text-right font-extrabold text-primary">{{ number_format($sale->total_amount, 0, ',', ' ') }} FCFA</td>
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
                                    title="Télécharger PDF">
                                    <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-10 text-center text-gray-400">
                            <span class="material-symbols-outlined text-4xl text-gray-300 mb-2 block">receipt_long</span>
                            Aucune vente trouvée avec les critères sélectionnés.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($mySales->hasPages())
        <div class="p-4 border-t border-gray-100 bg-gray-50">
            {{ $mySales->links() }}
        </div>
    @endif
</div>
@endsection
