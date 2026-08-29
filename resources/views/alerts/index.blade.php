@extends('layouts.app')

@section('title', 'PharmaGestion - Centre d\'Alertes')
@section('page-title', 'Centre d\'Alertes')

@section('content')
<div class="mb-lg">
    <h2 class="font-headline-md text-headline-md text-on-surface font-bold">Alertes Critiques & Notifications</h2>
    <p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">Contrôle des ruptures, stocks faibles et dates d'expiration proches.</p>
</div>

<div class="space-y-xl">
    <!-- Ruptures de Stock -->
    <div class="card-level-1 rounded-xl p-lg border-l-4 border-error">
        <h3 class="font-title-lg text-title-lg font-bold text-error flex items-center gap-2 mb-md">
            <span class="material-symbols-outlined">warning</span>
            Ruptures Totales de Stock ({{ $outOfStock->count() }})
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
            @forelse($outOfStock as $med)
                <div class="p-md rounded border border-error-container bg-error-container/10 flex justify-between items-center">
                    <div>
                        <div class="font-body-md text-body-md font-bold text-on-surface">{{ $med->name }} {{ $med->dosage }}</div>
                        <div class="font-body-sm text-body-sm text-on-surface-variant">Code: {{ $med->code }} • {{ $med->form }}</div>
                    </div>
                    <span class="px-md py-xs rounded bg-error text-on-error font-bold text-xs">Reste: 0</span>
                </div>
            @empty
                <p class="text-body-sm text-on-surface-variant">Aucune rupture de stock signalée.</p>
            @endforelse
        </div>
    </div>

    <!-- Stocks Faibles -->
    <div class="card-level-1 rounded-xl p-lg border-l-4 border-tertiary">
        <h3 class="font-title-lg text-title-lg font-bold text-tertiary flex items-center gap-2 mb-md">
            <span class="material-symbols-outlined">arrow_downward</span>
            Niveau de Stock Faible ({{ $lowStock->count() }})
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
            @forelse($lowStock as $med)
                <div class="p-md rounded border border-tertiary-fixed bg-tertiary-fixed/20 flex justify-between items-center">
                    <div>
                        <div class="font-body-md text-body-md font-bold text-on-surface">{{ $med->name }} {{ $med->dosage }}</div>
                        <div class="font-body-sm text-body-sm text-on-surface-variant">Seuil min: {{ $med->min_threshold }}</div>
                    </div>
                    <span class="px-md py-xs rounded bg-tertiary text-on-tertiary font-bold text-xs">Reste: {{ $med->stock_quantity }}</span>
                </div>
            @empty
                <p class="text-body-sm text-on-surface-variant">Aucun stock sous le seuil d'alerte.</p>
            @endforelse
        </div>
    </div>

    <!-- Expiration Proche -->
    <div class="card-level-1 rounded-xl p-lg border-l-4 border-secondary">
        <h3 class="font-title-lg text-title-lg font-bold text-secondary flex items-center gap-2 mb-md">
            <span class="material-symbols-outlined">event_busy</span>
            Alertes de Péremption Proche ({{ $nearExpiration->count() }})
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
            @forelse($nearExpiration as $med)
                <div class="p-md rounded border border-surface-dim bg-surface-container-low flex justify-between items-center">
                    <div>
                        <div class="font-body-md text-body-md font-bold text-on-surface">{{ $med->name }} {{ $med->dosage }}</div>
                        <div class="font-body-sm text-body-sm text-on-surface-variant">Exp: {{ $med->expiration_date->format('d/m/Y') }}</div>
                    </div>
                    <span class="px-md py-xs rounded bg-secondary text-on-secondary font-bold text-xs">A surveiller</span>
                </div>
            @empty
                <p class="text-body-sm text-on-surface-variant">Aucune péremption imminente.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
