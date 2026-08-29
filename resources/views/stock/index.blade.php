@extends('layouts.app')

@section('title', 'PharmaGestion - Suivi du Stock')
@section('page-title', 'Stock Détaillé')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-lg gap-md">
    <div>
        <h2 class="font-headline-md text-headline-md text-on-surface font-bold">État du Stock</h2>
        <p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">Consultez les niveaux de réserve et la péremption.</p>
    </div>
    <div class="flex gap-sm">
        <a href="{{ route('stock.index', ['status' => 'rupture']) }}" class="px-md py-sm rounded bg-error-container text-on-error-container font-label-md text-label-md font-bold">Ruptures</a>
        <a href="{{ route('stock.index', ['status' => 'faible']) }}" class="px-md py-sm rounded bg-tertiary-fixed text-on-tertiary-fixed font-label-md text-label-md font-bold">Stocks bas</a>
        <a href="{{ route('stock.index') }}" class="px-md py-sm rounded border border-outline-variant text-on-surface font-label-md text-label-md">Tous</a>
    </div>
</div>

<div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-outline-variant">
        <thead class="bg-surface">
            <tr>
                <th class="px-md py-sm text-left font-label-md text-label-md text-on-surface-variant uppercase">Code / Nom</th>
                <th class="px-md py-sm text-left font-label-md text-label-md text-on-surface-variant uppercase">Dosage & Forme</th>
                <th class="px-md py-sm text-right font-label-md text-label-md text-on-surface-variant uppercase">Stock Restant</th>
                <th class="px-md py-sm text-right font-label-md text-label-md text-on-surface-variant uppercase">Seuil d'Alerte</th>
                <th class="px-md py-sm text-center font-label-md text-label-md text-on-surface-variant uppercase">Expiration</th>
                <th class="px-md py-sm text-center font-label-md text-label-md text-on-surface-variant uppercase">Statut</th>
            </tr>
        </thead>
        <tbody class="bg-surface-container-lowest divide-y divide-outline-variant">
            @foreach($medications as $med)
                <tr class="hover:bg-surface-container-lowest/50">
                    <td class="px-md py-sm">
                        <div class="font-body-md text-body-md font-semibold text-on-surface">{{ $med->name }}</div>
                        <div class="font-body-sm text-body-sm text-on-surface-variant">{{ $med->code }}</div>
                    </td>
                    <td class="px-md py-sm font-body-sm text-body-sm text-on-surface">{{ $med->dosage }} - {{ $med->form }}</td>
                    <td class="px-md py-sm text-right font-bold text-body-md {{ $med->stock_quantity <= 0 ? 'text-error' : ($med->stock_quantity <= $med->min_threshold ? 'text-tertiary' : 'text-on-surface') }}">
                        {{ number_format($med->stock_quantity) }}
                    </td>
                    <td class="px-md py-sm text-right font-body-sm text-body-sm text-on-surface-variant">{{ $med->min_threshold }}</td>
                    <td class="px-md py-sm text-center font-body-sm text-body-sm text-on-surface">
                        {{ $med->expiration_date ? $med->expiration_date->format('d/m/Y') : '-' }}
                    </td>
                    <td class="px-md py-sm text-center">
                        @if($med->stock_quantity <= 0)
                            <span class="px-2 py-0.5 rounded-full font-label-md text-[10px] bg-error-container text-on-error-container font-bold">Rupture</span>
                        @elseif($med->stock_quantity <= $med->min_threshold)
                            <span class="px-2 py-0.5 rounded-full font-label-md text-[10px] bg-tertiary-fixed text-on-tertiary-fixed-variant font-bold">Faible</span>
                        @else
                            <span class="px-2 py-0.5 rounded-full font-label-md text-[10px] bg-surface-container-high text-primary-container font-bold">OK</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
