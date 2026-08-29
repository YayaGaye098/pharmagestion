@extends('layouts.app')

@section('title', 'PharmaGestion - Traçabilité')
@section('page-title', 'Journal de Traçabilité')

@section('content')
<div class="mb-lg">
    <h2 class="font-headline-md text-headline-md text-on-surface font-bold">Traçabilité & Journal d'Audit</h2>
    <p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">Historique complet des mouvements et modifications de stock.</p>
</div>

<div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-outline-variant">
        <thead class="bg-surface">
            <tr>
                <th class="px-md py-sm text-left font-label-md text-label-md text-on-surface-variant uppercase">Type d'opération</th>
                <th class="px-md py-sm text-left font-label-md text-label-md text-on-surface-variant uppercase">Médicament</th>
                <th class="px-md py-sm text-right font-label-md text-label-md text-on-surface-variant uppercase">Quantité</th>
                <th class="px-md py-sm text-left font-label-md text-label-md text-on-surface-variant uppercase">Utilisateur Responsable</th>
                <th class="px-md py-sm text-left font-label-md text-label-md text-on-surface-variant uppercase">Notes</th>
                <th class="px-md py-sm text-right font-label-md text-label-md text-on-surface-variant uppercase">Date & Horodatage</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-outline-variant">
            @foreach($movements as $mov)
                <tr class="hover:bg-surface-container-lowest/50">
                    <td class="px-md py-sm">
                        @if($mov->type === 'sortie')
                            <span class="inline-flex items-center space-x-1 text-primary bg-primary-container/10 px-2 py-1 rounded font-label-md text-label-md font-bold">
                                <span>Sortie</span>
                            </span>
                        @else
                            <span class="inline-flex items-center space-x-1 text-secondary bg-secondary-container/10 px-2 py-1 rounded font-label-md text-label-md font-bold">
                                <span>Entrée</span>
                            </span>
                        @endif
                    </td>
                    <td class="px-md py-sm font-body-md text-body-md font-semibold text-on-surface">
                        {{ $mov->medication->name ?? 'Médicament' }} ({{ $mov->medication->dosage ?? '' }})
                    </td>
                    <td class="px-md py-sm text-right font-bold {{ $mov->type === 'sortie' ? 'text-error' : 'text-secondary' }}">
                        {{ $mov->type === 'sortie' ? '-' : '+' }}{{ $mov->quantity }}
                    </td>
                    <td class="px-md py-sm font-body-sm text-body-sm text-on-surface font-semibold">
                        {{ $mov->performed_by_name ?? $mov->user->name ?? 'Agent' }}
                    </td>
                    <td class="px-md py-sm font-body-sm text-body-sm text-on-surface-variant">
                        {{ $mov->notes ?? '-' }}
                    </td>
                    <td class="px-md py-sm text-right font-body-sm text-body-sm text-on-surface-variant">
                        {{ $mov->created_at->format('d/m/Y H:i:s') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="p-md">
        {{ $movements->links() }}
    </div>
</div>
@endsection
