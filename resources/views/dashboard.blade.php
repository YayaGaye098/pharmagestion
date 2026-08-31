@extends('layouts.app')

@section('title', 'PharmaGestion - Dashboard')
@section('page-title', 'Vue d\'ensemble')

@section('content')
<!-- Mobile/Tablet Title -->
<div class="lg:hidden">
    <h2 class="font-headline-md text-headline-md text-on-surface font-bold">Vue d'ensemble</h2>
</div>

<!-- KPI Cards Bento Grid -->
<section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-md md:gap-lg">
    <!-- Total Medications -->
    <div class="card-level-1 rounded-xl p-md flex flex-col justify-between h-32">
        <div class="flex justify-between items-start">
            <span class="font-body-md text-body-md text-on-surface-variant">Total Médicaments</span>
            <div class="bg-primary-container/20 p-sm rounded-lg text-primary">
                <span class="material-symbols-outlined text-[20px]">medical_services</span>
            </div>
        </div>
        <div class="font-display-lg text-display-lg text-on-surface font-bold">{{ number_format($totalMedications) }}</div>
    </div>

    <!-- Total Stock -->
    <div class="card-level-1 rounded-xl p-md flex flex-col justify-between h-32">
        <div class="flex justify-between items-start">
            <span class="font-body-md text-body-md text-on-surface-variant">Stock Total</span>
            <div class="bg-secondary-container/20 p-sm rounded-lg text-secondary">
                <span class="material-symbols-outlined text-[20px]">inventory_2</span>
            </div>
        </div>
        <div class="font-display-lg text-display-lg text-on-surface font-bold">{{ number_format($totalStock) }}</div>
    </div>

    <!-- Stock Value -->
    <div class="card-level-1 rounded-xl p-md flex flex-col justify-between h-32">
        <div class="flex justify-between items-start">
            <span class="font-body-md text-body-md text-on-surface-variant">Valeur du Stock</span>
            <div class="bg-tertiary-container/20 p-sm rounded-lg text-tertiary">
                <span class="material-symbols-outlined text-[20px]">payments</span>
            </div>
        </div>
        <div class="font-currency-md text-currency-md text-on-surface font-bold text-2xl">
            {{ number_format($stockValue, 0, ',', ' ') }} <span class="text-sm font-normal text-outline">FCFA</span>
        </div>
    </div>

    <!-- Stock Alerts (Combined) -->
    <div class="grid grid-rows-2 gap-md h-32">
        <div class="card-level-1 rounded-lg px-md py-sm flex justify-between items-center bg-error-container/20 border-error-container">
            <div class="flex items-center space-x-sm text-error">
                <span class="material-symbols-outlined">warning</span>
                <span class="font-label-md text-label-md font-bold">Ruptures</span>
            </div>
            <span class="font-headline-sm text-headline-sm font-bold text-on-surface">{{ $outOfStockCount }}</span>
        </div>
        <div class="card-level-1 rounded-lg px-md py-sm flex justify-between items-center bg-tertiary-fixed/30 border-tertiary-fixed">
            <div class="flex items-center space-x-sm text-tertiary">
                <span class="material-symbols-outlined">arrow_downward</span>
                <span class="font-label-md text-label-md font-bold">Stock Bas</span>
            </div>
            <span class="font-headline-sm text-headline-sm font-bold text-on-surface">{{ $lowStockCount }}</span>
        </div>
    </div>
</section>

<!-- Main Dashboard Grid -->
<section class="grid grid-cols-1 lg:grid-cols-3 gap-lg">
    <!-- Activity Chart Area -->
    <div class="lg:col-span-2 card-level-1 rounded-xl p-lg flex flex-col h-96">
        <div class="flex justify-between items-center mb-md">
            <h3 class="font-title-lg text-title-lg text-on-surface font-bold">Mouvements de Stock</h3>
            <select class="text-body-sm border border-outline-variant rounded px-2 py-1 bg-transparent text-on-surface-variant outline-none focus:ring-1 focus:ring-primary">
                <option>7 derniers jours</option>
                <option>30 derniers jours</option>
            </select>
        </div>
        <!-- Chart Visual Mockup -->
        <div class="flex-1 bg-surface-container-low rounded-lg border border-outline-variant border-dashed flex flex-col justify-end p-md relative overflow-hidden">
            <div class="flex items-end justify-between gap-md h-full pt-lg">
                <div class="flex-1 bg-primary/20 rounded-t h-[40%] flex items-center justify-center font-bold text-xs text-primary">Lun</div>
                <div class="flex-1 bg-primary/40 rounded-t h-[65%] flex items-center justify-center font-bold text-xs text-primary">Mar</div>
                <div class="flex-1 bg-primary/80 rounded-t h-[90%] flex items-center justify-center font-bold text-xs text-on-primary">Mer</div>
                <div class="flex-1 bg-primary/50 rounded-t h-[55%] flex items-center justify-center font-bold text-xs text-primary">Jeu</div>
                <div class="flex-1 bg-primary/70 rounded-t h-[75%] flex items-center justify-center font-bold text-xs text-on-primary">Ven</div>
                <div class="flex-1 bg-primary/30 rounded-t h-[35%] flex items-center justify-center font-bold text-xs text-primary">Sam</div>
                <div class="flex-1 bg-primary/10 rounded-t h-[20%] flex items-center justify-center font-bold text-xs text-primary">Dim</div>
            </div>
        </div>
    </div>

    <!-- Alertes Critiques -->
    <div class="card-level-1 rounded-xl p-lg flex flex-col h-96">
        <div class="flex justify-between items-center mb-md">
            <h3 class="font-title-lg text-title-lg text-on-surface font-bold flex items-center gap-2">
                <span class="material-symbols-outlined text-error">error</span>
                Alertes critiques
            </h3>
            <a class="text-primary font-label-md text-label-md hover:underline" href="{{ route('medications.index') }}">Voir tout</a>
        </div>
        <div class="flex-1 overflow-y-auto space-y-sm pr-2">
            @forelse($criticalAlerts as $alertMed)
                <div class="p-sm rounded-lg border {{ $alertMed->stock_quantity <= 0 ? 'border-error-container' : 'border-tertiary-fixed' }} bg-surface-lowest">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-label-md text-label-md text-on-surface font-semibold">{{ $alertMed->name }} {{ $alertMed->dosage }}</h4>
                            <p class="font-body-sm text-body-sm text-on-surface-variant text-xs">
                                {{ $alertMed->stock_quantity <= 0 ? 'Rupture totale' : 'Stock en dessous du seuil' }}
                            </p>
                        </div>
                        <span class="{{ $alertMed->stock_quantity <= 0 ? 'bg-error-container text-on-error-container' : 'bg-tertiary-fixed text-on-tertiary-fixed' }} font-label-md text-[10px] px-2 py-1 rounded">
                            Reste: {{ $alertMed->stock_quantity }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-body-sm text-on-surface-variant">Aucune alerte critique enregistrée.</p>
            @endforelse
        </div>
    </div>
</section>

<!-- Activités Récentes -->
<section class="card-level-1 rounded-xl p-lg">
    <h3 class="font-title-lg text-title-lg text-on-surface mb-md font-bold">Activités récentes</h3>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-outline-variant">
                    <th class="py-3 px-4 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Type</th>
                    <th class="py-3 px-4 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Médicament</th>
                    <th class="py-3 px-4 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Quantité</th>
                    <th class="py-3 px-4 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Utilisateur</th>
                    <th class="py-3 px-4 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider text-right">Date/Heure</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($recentMovements as $mov)
                    <tr class="hover:bg-surface-container-low transition-colors bg-surface-lowest">
                        <td class="py-3 px-4">
                            @if($mov->type === 'sortie')
                                <span class="inline-flex items-center space-x-1 text-primary bg-primary-container/10 px-2 py-1 rounded font-label-md text-label-md">
                                    <span class="material-symbols-outlined text-[16px]">arrow_downward</span>
                                    <span>Sortie</span>
                                </span>
                            @else
                                <span class="inline-flex items-center space-x-1 text-secondary bg-secondary-container/10 px-2 py-1 rounded font-label-md text-label-md">
                                    <span class="material-symbols-outlined text-[16px]">arrow_upward</span>
                                    <span>Entrée</span>
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 font-body-sm text-body-sm text-on-surface font-semibold">
                            {{ $mov->medication->name ?? 'Médicament' }} {{ $mov->medication->dosage ?? '' }}
                        </td>
                        <td class="py-3 px-4 font-body-sm text-body-sm font-bold text-on-surface">
                            {{ $mov->type === 'sortie' ? '-' : '+' }}{{ $mov->quantity }}
                        </td>
                        <td class="py-3 px-4 font-body-sm text-body-sm text-on-surface-variant">
                            {{ $mov->performed_by_name ?? $mov->user->name ?? 'Agent' }}
                        </td>
                        <td class="py-3 px-4 font-body-sm text-body-sm text-on-surface-variant text-right">
                            {{ $mov->created_at->diffForHumans() }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-4 text-center text-on-surface-variant">Aucune activité récente.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
