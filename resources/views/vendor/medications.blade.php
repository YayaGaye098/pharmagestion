@extends('layouts.vendor')

@section('title', 'PharmaGestion - Catalogue Médicaments')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-900">Médicaments Disponibles</h2>
        <p class="text-xs text-gray-500 mt-0.5">Consultez la disponibilité des stocks et les prix publics pour les ventes au comptoir.</p>
    </div>
    <a href="{{ route('sales.pos') }}" class="bg-primary hover:bg-primary-hover text-white font-bold text-xs py-2.5 px-4 rounded-xl flex items-center gap-1.5 shadow-sm transition-all">
        <span class="material-symbols-outlined text-[18px]">point_of_sale</span>
        <span>Aller au Guichet</span>
    </a>
</div>

<!-- Filtres de recherche -->
<div class="bg-surface-lowest border border-outline-variant/80 rounded-xl p-4 shadow-xs mb-6">
    <form action="{{ route('vendor.medications') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <div>
            <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Rechercher</label>
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Nom, code ou dosage..." 
                class="w-full text-xs bg-gray-50 border border-outline-variant rounded-lg px-3 py-2 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary font-medium"
            />
        </div>

        <div>
            <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Catégorie</label>
            <select name="category_id" class="w-full text-xs bg-gray-50 border border-outline-variant rounded-lg px-3 py-2 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary font-medium">
                <option value="">Toutes les catégories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 bg-primary hover:bg-primary-hover text-white text-xs font-bold py-2 px-3 rounded-lg shadow-xs transition-colors flex items-center justify-center gap-1">
                <span class="material-symbols-outlined text-[16px]">search</span>
                <span>Rechercher</span>
            </button>
            <a href="{{ route('vendor.medications') }}" class="p-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg transition-colors" title="Réinitialiser">
                <span class="material-symbols-outlined text-[16px]">refresh</span>
            </a>
        </div>
    </form>
</div>

<!-- Table des Médicaments -->
<div class="bg-surface-lowest border border-outline-variant/80 rounded-2xl shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm border-collapse">
            <thead>
                <tr class="bg-gray-50/80 text-[11px] font-bold text-gray-500 uppercase border-b border-gray-100">
                    <th class="py-3.5 px-5">Code</th>
                    <th class="py-3.5 px-5">Médicament</th>
                    <th class="py-3.5 px-5">Dosage & Forme</th>
                    <th class="py-3.5 px-5">Catégorie</th>
                    <th class="py-3.5 px-5 text-right">Stock Disponible</th>
                    <th class="py-3.5 px-5 text-right">Prix Unitaire</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($medications as $med)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-3.5 px-5 font-mono text-xs font-bold text-gray-400">{{ $med->code }}</td>
                        <td class="py-3.5 px-5 font-bold text-gray-900">{{ $med->name }}</td>
                        <td class="py-3.5 px-5 text-xs text-gray-600">{{ $med->dosage }} <span class="text-gray-400">({{ $med->form }})</span></td>
                        <td class="py-3.5 px-5 text-xs">
                            <span class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded-full text-[11px] font-semibold">
                                {{ $med->category->name ?? 'Général' }}
                            </span>
                        </td>
                        <td class="py-3.5 px-5 text-right">
                            @if($med->stock_quantity <= 0)
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">Rupture</span>
                            @elseif($med->stock_quantity <= $med->min_threshold)
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">{{ $med->stock_quantity }} (Faible)</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">{{ $med->stock_quantity }} en stock</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-5 text-right font-extrabold text-primary text-base">
                            {{ number_format($med->unit_price, 0, ',', ' ') }} FCFA
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-10 text-center text-gray-400">
                            <span class="material-symbols-outlined text-4xl text-gray-300 mb-2 block">medication</span>
                            Aucun médicament trouvé en stock.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($medications->hasPages())
        <div class="p-4 border-t border-gray-100 bg-gray-50">
            {{ $medications->links() }}
        </div>
    @endif
</div>
@endsection
