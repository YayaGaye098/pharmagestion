@extends('layouts.app')

@section('title', 'PharmaGestion - Gestion des Prix & Marges')
@section('page-title', 'Tarification & Marges')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2">
        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-primary/10 text-primary border border-primary/20">
            Espace Administrateur
        </span>
    </div>
    <h2 class="text-2xl font-black text-gray-900 mt-1">Grille Tarifaire & Marges Bénéficiaires</h2>
    <p class="text-xs text-gray-500 mt-0.5">
        Ajustez vos prix de vente public et comparez-les aux prix d'achat (prix de cession du District) pour piloter vos marges.
    </p>
</div>

<div class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-outline-variant">
        <thead class="bg-gray-50/80">
            <tr class="text-[11px] font-bold text-gray-500 uppercase">
                <th class="px-4 py-3 text-left">Code / Nom</th>
                <th class="px-4 py-3 text-left">Forme & UC</th>
                <th class="px-4 py-3 text-right">Prix Achat (District)</th>
                <th class="px-4 py-3 text-right">Prix Vente Public</th>
                <th class="px-4 py-3 text-right">Marge Actuelle</th>
                <th class="px-4 py-3 text-center">Mettre à Jour Prix Vente</th>
                <th class="px-4 py-3 text-right">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-outline-variant">
            @foreach($medications as $med)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-4 py-3">
                        <div class="font-bold text-gray-900 text-xs">{{ $med->name }}</div>
                        <div class="text-[11px] text-gray-400 font-mono">{{ $med->code }}</div>
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-600">
                        <div>{{ $med->dosage }} ({{ $med->form }})</div>
                        @if($med->packaging_unit)
                            <span class="inline-block mt-0.5 text-[10px] font-bold px-1.5 py-0.2 rounded bg-gray-100 text-gray-700 border border-gray-200">
                                UC: {{ $med->packaging_unit }}
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right font-bold text-blue-900 text-xs whitespace-nowrap">
                        {{ number_format($med->purchase_price ?? 0, 0, ',', ' ') }} FCFA
                    </td>
                    <td class="px-4 py-3 text-right font-black text-gray-900 text-xs whitespace-nowrap">
                        {{ number_format($med->unit_price, 0, ',', ' ') }} FCFA
                    </td>
                    <td class="px-4 py-3 text-right whitespace-nowrap">
                        @php
                            $margin = $med->unit_margin;
                            $pct = $med->margin_percentage;
                        @endphp
                        <div class="font-black text-xs {{ $margin >= 0 ? 'text-emerald-700' : 'text-red-600' }}">
                            {{ $margin >= 0 ? '+' : '' }}{{ number_format($margin, 0, ',', ' ') }} FCFA
                        </div>
                        <div class="text-[10px] font-bold {{ $margin >= 0 ? 'text-emerald-600' : 'text-red-500' }}">
                            ({{ $pct }}%)
                        </div>
                    </td>
                    <form action="{{ route('prices.update', $med->id) }}" method="POST">
                        @csrf
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <input 
                                    name="unit_price" 
                                    value="{{ (int) $med->unit_price }}" 
                                    class="w-28 text-xs bg-white border border-outline-variant rounded-lg text-center py-1.5 font-black text-primary focus:ring-1 focus:ring-primary focus:border-primary" 
                                    type="number" 
                                    min="0" 
                                    step="1"
                                />
                                <span class="text-[11px] font-bold text-gray-400">F</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <button type="submit" class="px-3 py-1.5 bg-primary hover:bg-surface-tint text-white rounded-lg text-xs font-bold transition-colors">
                                Enregistrer
                            </button>
                        </td>
                    </form>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
