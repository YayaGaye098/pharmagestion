@extends('layouts.app')

@section('title', 'PharmaGestion - Paramètres')
@section('page-title', 'Paramètres de l\'Etablissement')

@section('content')
<div class="mb-lg">
    <h2 class="font-headline-md text-headline-md text-on-surface font-bold">Configuration du Poste de Santé</h2>
    <p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">Gérez les informations de l'établissement et les seuils système.</p>
</div>

<div class="card-level-1 rounded-xl p-xl max-w-2xl">
    <form action="{{ route('settings.update') }}" method="POST" class="space-y-md">
        @csrf
        <div>
            <label class="font-label-md text-label-md text-on-surface">Nom du Poste de Santé / Pharmacie</label>
            <input name="facility_name" value="{{ $settings['facility_name'] }}" class="w-full border border-outline-variant rounded px-md py-sm font-body-sm font-bold text-on-surface" type="text"/>
        </div>
        <div>
            <label class="font-label-md text-label-md text-on-surface">Région / District Médical</label>
            <input name="region" value="{{ $settings['region'] }}" class="w-full border border-outline-variant rounded px-md py-sm font-body-sm text-on-surface" type="text"/>
        </div>
        <div>
            <label class="font-label-md text-label-md text-on-surface">Devise de Gestion</label>
            <input name="currency" value="{{ $settings['currency'] }}" readonly class="w-full border border-outline-variant rounded px-md py-sm font-body-sm bg-surface-container-low font-bold" type="text"/>
        </div>
        <div>
            <label class="font-label-md text-label-md text-on-surface">Email des notifications d'urgence</label>
            <input name="alert_email" value="{{ $settings['alert_email'] }}" class="w-full border border-outline-variant rounded px-md py-sm font-body-sm text-on-surface" type="email"/>
        </div>
        <div class="pt-md">
            <button type="submit" class="bg-primary text-on-primary font-bold px-xl py-md rounded shadow-sm hover:bg-surface-tint transition-colors">
                Enregistrer les Paramètres
            </button>
        </div>
    </form>
</div>
@endsection
