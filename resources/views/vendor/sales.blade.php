@extends('layouts.vendor')

@section('title', 'PharmaGestion - Mes Ventes')

@section('content')
<div class="flex justify-between items-center mb-lg">
    <div>
        <h2 class="font-headline-md text-headline-md text-on-surface font-bold">Mes Ventes & Délivrances</h2>
        <p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">Historique complet de vos opérations enregistrées au comptoir.</p>
    </div>
    <a href="{{ route('exits.index') }}" class="bg-primary text-on-primary font-label-md text-label-md py-sm px-md rounded-lg flex items-center gap-xs font-bold shadow-sm">
        <span class="material-symbols-outlined">add_circle</span>
        Nouvelle Vente
    </a>
</div>

<div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-outline-variant">
        <thead class="bg-surface">
            <tr>
                <th class="px-md py-sm text-left font-label-md text-label-md text-on-surface-variant uppercase">Horodatage</th>
                <th class="px-md py-sm text-left font-label-md text-label-md text-on-surface-variant uppercase">Médicament</th>
                <th class="px-md py-sm text-right font-label-md text-label-md text-on-surface-variant uppercase">Quantité</th>
                <th class="px-md py-sm text-right font-label-md text-label-md text-on-surface-variant uppercase">Montant (FCFA)</th>
                <th class="px-md py-sm text-left font-label-md text-label-md text-on-surface-variant uppercase">Notes</th>
                <th class="px-md py-sm text-center font-label-md text-label-md text-on-surface-variant uppercase">Document</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-outline-variant">
            @forelse($mySales as $m)
                <tr class="hover:bg-surface-container-lowest/50">
                    <td class="px-md py-sm font-body-sm text-body-sm text-on-surface-variant">{{ $m->created_at->format('d/m/Y H:i:s') }}</td>
                    <td class="px-md py-sm font-body-md text-body-md font-bold text-on-surface">{{ $m->medication->name ?? '-' }} ({{ $m->medication->dosage ?? '' }})</td>
                    <td class="px-md py-sm text-right font-bold text-on-surface">{{ $m->quantity }}</td>
                    <td class="px-md py-sm text-right font-bold text-primary">{{ number_format($m->quantity * ($m->medication->unit_price ?? 0), 0, ',', ' ') }} F</td>
                    <td class="px-md py-sm font-body-sm text-body-sm text-on-surface-variant">{{ $m->notes ?? '-' }}</td>
                    <td class="px-md py-sm text-center">
                        <a href="{{ route('exits.pdf', $m->id) }}" class="inline-flex items-center gap-xs px-sm py-xs bg-surface-container text-primary rounded font-bold text-xs">
                            <span class="material-symbols-outlined text-[14px]">receipt</span>
                            Reçu PDF
                        </a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="p-md text-center text-on-surface-variant">Aucune vente enregistrée.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
