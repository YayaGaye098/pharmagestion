@extends('layouts.app')

@section('title', 'PharmaGestion - Rapports & Statistiques')
@section('page-title', 'Rapports & Statistiques')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-lg gap-md">
    <div>
        <h2 class="font-headline-md text-headline-md text-on-surface font-bold">Rapports d'Activité & Inventaire</h2>
        <p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">Statistiques mensuelles et valorisation globale du stock.</p>
    </div>
    <a href="{{ route('reports.pdf') }}" class="bg-primary text-on-primary px-lg py-sm rounded-lg font-label-md text-label-md flex items-center gap-sm shadow-sm font-bold cursor-pointer hover:bg-surface-tint transition-colors">
        <span class="material-symbols-outlined">picture_as_pdf</span>
        Exporter le Rapport de Stock (PDF)
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-lg mb-xl">
    <div class="card-level-1 rounded-xl p-lg flex flex-col justify-between">
        <span class="font-body-md text-body-md text-on-surface-variant">Valorisation Totale du Stock</span>
        <div class="font-currency-md text-currency-md text-primary font-bold text-3xl mt-sm">
            {{ number_format($totalValuation, 0, ',', ' ') }} <span class="text-sm text-outline font-normal">FCFA</span>
        </div>
    </div>
    <div class="card-level-1 rounded-xl p-lg flex flex-col justify-between">
        <span class="font-body-md text-body-md text-on-surface-variant">Entrées ce Mois-ci</span>
        <div class="font-headline-md text-headline-md text-secondary font-bold text-3xl mt-sm">+{{ number_format($monthlyEntriesCount) }} unités</div>
    </div>
    <div class="card-level-1 rounded-xl p-lg flex flex-col justify-between">
        <span class="font-body-md text-body-md text-on-surface-variant">Sorties ce Mois-ci</span>
        <div class="font-headline-md text-headline-md text-error font-bold text-3xl mt-sm">-{{ number_format($monthlyExitsCount) }} unités</div>
    </div>
</div>

<div class="card-level-1 rounded-xl p-lg">
    <h3 class="font-title-lg text-title-lg font-bold text-on-surface mb-md">Répartition des Médicaments par Catégorie</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-md">
        @foreach($categoryStats as $cat)
            <div class="p-md rounded border border-outline-variant bg-surface-container-low flex justify-between items-center">
                <span class="font-body-md text-body-md font-semibold text-on-surface">{{ $cat->name }}</span>
                <span class="px-md py-xs rounded bg-primary-container text-on-primary-container font-bold text-sm">
                    {{ $cat->medications_count }} réf.
                </span>
            </div>
        @endforeach
    </div>
</div>
@endsection
