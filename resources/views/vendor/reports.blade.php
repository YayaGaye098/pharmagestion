@extends('layouts.vendor')

@section('title', 'PharmaGestion - Mes Rapports de Vente')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-900">Synthèse & Rapports d'Activité</h2>
        <p class="text-xs text-gray-500 mt-0.5">Bilan global de vos performances de vente et des produits les plus délivrés.</p>
    </div>
    <a href="{{ route('sales.pos') }}" class="bg-primary hover:bg-primary-hover text-white font-bold text-xs py-2.5 px-4 rounded-xl flex items-center gap-1.5 shadow-sm transition-all">
        <span class="material-symbols-outlined text-[18px]">point_of_sale</span>
        <span>Guichet de Vente</span>
    </a>
</div>

<!-- 3 Cartes de Chiffres Clés -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-surface-lowest border border-outline-variant/80 p-5 rounded-2xl shadow-xs">
        <span class="text-xs uppercase font-bold text-gray-400">Recette d'Aujourd'hui</span>
        <h3 class="text-2xl font-extrabold text-primary mt-1">{{ number_format($todayRevenue, 0, ',', ' ') }} FCFA</h3>
        <p class="text-xs text-gray-400 mt-1">Encaissements de la journée</p>
    </div>

    <div class="bg-surface-lowest border border-outline-variant/80 p-5 rounded-2xl shadow-xs">
        <span class="text-xs uppercase font-bold text-gray-400">Recette de ce Mois</span>
        <h3 class="text-2xl font-extrabold text-gray-900 mt-1">{{ number_format($monthRevenue, 0, ',', ' ') }} FCFA</h3>
        <p class="text-xs text-gray-400 mt-1">Mois de {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
    </div>

    <div class="bg-surface-lowest border border-outline-variant/80 p-5 rounded-2xl shadow-xs">
        <span class="text-xs uppercase font-bold text-gray-400">Chiffre d'Affaires Cumulé</span>
        <h3 class="text-2xl font-extrabold text-gray-900 mt-1">{{ number_format($totalRevenue, 0, ',', ' ') }} FCFA</h3>
        <p class="text-xs text-gray-400 mt-1">Total depuis l'ouverture du compte ({{ number_format($totalSalesCount) }} ventes)</p>
    </div>
</div>

<!-- Top Médicaments les plus vendus par la vendeuse -->
<div class="bg-surface-lowest border border-outline-variant/80 rounded-2xl shadow-xs overflow-hidden">
    <div class="p-5 border-b border-gray-100 bg-surface-low/50">
        <h3 class="font-bold text-base text-gray-900">Vos Produits les Plus Vendus (Top 5)</h3>
        <p class="text-xs text-gray-400">Classement des médicaments selon le volume délivré par votre compte.</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm border-collapse">
            <thead>
                <tr class="bg-gray-50/80 text-[11px] font-bold text-gray-500 uppercase border-b border-gray-100">
                    <th class="py-3.5 px-5">Rang</th>
                    <th class="py-3.5 px-5">Médicament</th>
                    <th class="py-3.5 px-5">Dosage</th>
                    <th class="py-3.5 px-5 text-right">Quantité Totale Vendue</th>
                    <th class="py-3.5 px-5 text-right">Chiffre Généré</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($topMedications as $idx => $med)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-3.5 px-5 font-bold text-xs text-gray-400">#{{ $idx + 1 }}</td>
                        <td class="py-3.5 px-5 font-bold text-gray-900">{{ $med->name }}</td>
                        <td class="py-3.5 px-5 text-xs text-gray-600">{{ $med->dosage ?? '-' }}</td>
                        <td class="py-3.5 px-5 text-right font-extrabold text-gray-900">
                            <span class="bg-primary/10 text-primary px-2.5 py-1 rounded-full text-xs font-bold">
                                {{ number_format($med->total_qty) }} boîtes
                            </span>
                        </td>
                        <td class="py-3.5 px-5 text-right font-extrabold text-primary">
                            {{ number_format($med->total_amount, 0, ',', ' ') }} FCFA
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-400">
                            Aucune donnée de vente enregistrée pour le moment.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
