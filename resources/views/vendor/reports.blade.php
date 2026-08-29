@extends('layouts.vendor')

@section('title', 'PharmaGestion - Mes Rapports de Vente')

@section('content')
<div class="mb-lg">
    <h2 class="font-headline-md text-headline-md text-on-surface font-bold">Mes Rapports d'Activité</h2>
    <p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">Synthèse de votre activité et bilan des ventes réalisées au comptoir.</p>
</div>

<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg mb-lg shadow-sm">
    <div class="flex justify-between items-center">
        <div>
            <h3 class="font-title-lg text-title-lg text-on-surface font-bold">Chiffre d'Affaires Cumulé</h3>
            <p class="font-body-sm text-body-sm text-on-surface-variant">Total des ventes enregistrées par votre compte.</p>
        </div>
        <span class="font-display-lg text-display-lg text-primary font-bold">{{ number_format($totalRevenue, 0, ',', ' ') }} FCFA</span>
    </div>
</div>

<div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden">
    <div class="p-md bg-surface border-b border-outline-variant">
        <h3 class="font-title-lg text-title-lg text-on-surface font-bold">Détail des Opérations</h3>
    </div>
    <table class="min-w-full divide-y divide-outline-variant">
        <thead class="bg-surface">
            <tr>
                <th class="px-md py-sm text-left font-label-md text-label-md text-on-surface-variant uppercase">Date</th>
                <th class="px-md py-sm text-left font-label-md text-label-md text-on-surface-variant uppercase">Médicament</th>
                <th class="px-md py-sm text-right font-label-md text-label-md text-on-surface-variant uppercase">Quantité</th>
                <th class="px-md py-sm text-right font-label-md text-label-md text-on-surface-variant uppercase">Prix Unitaire</th>
                <th class="px-md py-sm text-right font-label-md text-label-md text-on-surface-variant uppercase">Sous-total</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-outline-variant">
            @foreach($mySales as $s)
                <tr class="hover:bg-surface-container-lowest/50">
                    <td class="px-md py-sm font-body-sm text-body-sm text-on-surface-variant">{{ $s->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-md py-sm font-body-md text-body-md font-bold text-on-surface">{{ $s->medication->name ?? '-' }}</td>
                    <td class="px-md py-sm text-right font-bold text-on-surface">{{ $s->quantity }}</td>
                    <td class="px-md py-sm text-right font-body-sm text-body-sm text-on-surface-variant">{{ number_format($s->medication->unit_price ?? 0, 0, ',', ' ') }} F</td>
                    <td class="px-md py-sm text-right font-bold text-primary">{{ number_format($s->quantity * ($s->medication->unit_price ?? 0), 0, ',', ' ') }} FCFA</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
