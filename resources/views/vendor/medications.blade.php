@extends('layouts.vendor')

@section('title', 'PharmaGestion - Médicaments Disponibles')

@section('content')
<div class="mb-lg">
    <h2 class="font-headline-md text-headline-md text-on-surface font-bold">Médicaments Disponibles en Stock</h2>
    <p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">Consultez la disponibilité des produits et leurs prix de vente unitaires.</p>
</div>

<div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-outline-variant">
        <thead class="bg-surface">
            <tr>
                <th class="px-md py-sm text-left font-label-md text-label-md text-on-surface-variant uppercase">Médicament</th>
                <th class="px-md py-sm text-left font-label-md text-label-md text-on-surface-variant uppercase">Dosage & Forme</th>
                <th class="px-md py-sm text-left font-label-md text-label-md text-on-surface-variant uppercase">Catégorie</th>
                <th class="px-md py-sm text-right font-label-md text-label-md text-on-surface-variant uppercase">Stock Dispo</th>
                <th class="px-md py-sm text-right font-label-md text-label-md text-on-surface-variant uppercase">Prix Unitaire</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-outline-variant">
            @foreach($medications as $med)
                <tr class="hover:bg-surface-container-lowest/50">
                    <td class="px-md py-sm font-body-md text-body-md font-bold text-on-surface">
                        {{ $med->name }}
                        <span class="block text-xs font-normal text-on-surface-variant">Code: {{ $med->code }}</span>
                    </td>
                    <td class="px-md py-sm font-body-sm text-body-sm text-on-surface">{{ $med->dosage }} ({{ $med->form }})</td>
                    <td class="px-md py-sm font-body-sm text-body-sm text-on-surface-variant">{{ $med->category->name ?? 'Général' }}</td>
                    <td class="px-md py-sm text-right font-bold text-body-md {{ $med->stock_quantity <= $med->min_threshold ? 'text-error' : 'text-on-surface' }}">
                        {{ number_format($med->stock_quantity) }}
                    </td>
                    <td class="px-md py-sm text-right font-currency-md text-currency-md text-primary font-bold">
                        {{ number_format($med->unit_price, 0, ',', ' ') }} FCFA
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
