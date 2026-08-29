@extends('layouts.app')

@section('title', 'PharmaGestion - Gestion des Prix')
@section('page-title', 'Tarification & Prix')

@section('content')
<div class="mb-lg">
    <h2 class="font-headline-md text-headline-md text-on-surface font-bold">Grille Tarification (FCFA)</h2>
    <p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">Modifiez les prix unitaires de vente des médicaments.</p>
</div>

<div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-outline-variant">
        <thead class="bg-surface">
            <tr>
                <th class="px-md py-sm text-left font-label-md text-label-md text-on-surface-variant uppercase">Code / Nom</th>
                <th class="px-md py-sm text-left font-label-md text-label-md text-on-surface-variant uppercase">Forme & Dosage</th>
                <th class="px-md py-sm text-right font-label-md text-label-md text-on-surface-variant uppercase">Prix Actuel (FCFA)</th>
                <th class="px-md py-sm text-center font-label-md text-label-md text-on-surface-variant uppercase">Nouveau Prix</th>
                <th class="px-md py-sm text-right font-label-md text-label-md text-on-surface-variant uppercase">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-outline-variant">
            @foreach($medications as $med)
                <tr class="hover:bg-surface-container-lowest/50">
                    <td class="px-md py-sm">
                        <div class="font-body-md text-body-md font-semibold text-on-surface">{{ $med->name }}</div>
                        <div class="font-body-sm text-body-sm text-on-surface-variant">{{ $med->code }}</div>
                    </td>
                    <td class="px-md py-sm font-body-sm text-body-sm text-on-surface">{{ $med->dosage }} ({{ $med->form }})</td>
                    <td class="px-md py-sm text-right font-currency-md text-currency-md text-on-surface font-bold">
                        {{ number_format($med->unit_price, 0, ',', ' ') }} FCFA
                    </td>
                    <form action="{{ route('prices.update', $med->id) }}" method="POST">
                        @csrf
                        <td class="px-md py-sm text-center">
                            <input name="unit_price" value="{{ $med->unit_price }}" class="w-32 border border-outline-variant rounded text-center py-xs font-bold" type="number" min="0" step="10"/>
                        </td>
                        <td class="px-md py-sm text-right">
                            <button type="submit" class="px-md py-xs bg-primary text-on-primary rounded font-label-md text-label-md font-bold">
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
