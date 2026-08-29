@extends('layouts.app')

@section('title', 'PharmaGestion - Entrées de Stock')
@section('page-title', 'Entrées de Stock')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-lg gap-md">
    <div>
        <h2 class="font-headline-md text-headline-md text-on-surface font-bold">Réception de Stock (Entrées)</h2>
        <p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">Enregistrez les livraisons et approvisionnements.</p>
    </div>
    <button onclick="document.getElementById('newEntryForm').classList.toggle('hidden')" class="bg-secondary text-on-secondary px-lg py-sm rounded-lg font-label-md text-label-md flex items-center gap-sm shadow-sm font-bold cursor-pointer">
        <span class="material-symbols-outlined">add</span>
        Nouvelle Entrée
    </button>
</div>

<!-- Formulaire Nouvelle Entrée -->
<div id="newEntryForm" class="hidden mb-lg bg-surface-container-lowest border border-outline-variant rounded-xl p-lg shadow-sm">
    <h3 class="font-title-lg text-title-lg font-bold text-on-surface mb-md">Enregistrer un Réassort</h3>
    <form action="{{ route('entries.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-md">
        @csrf
        <div>
            <label class="font-label-md text-label-md text-on-surface">Médicament</label>
            <select name="medication_id" required class="w-full border border-outline-variant rounded px-md py-sm font-body-sm">
                @foreach($medications as $med)
                    <option value="{{ $med->id }}">{{ $med->name }} ({{ $med->dosage }}) - Stock actuel: {{ $med->stock_quantity }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="font-label-md text-label-md text-on-surface">Quantité Reçue</label>
            <input name="quantity" min="1" required placeholder="ex: 100" class="w-full border border-outline-variant rounded px-md py-sm font-body-sm" type="number"/>
        </div>
        <div>
            <label class="font-label-md text-label-md text-on-surface">Fournisseur / Origine</label>
            <input name="supplier" placeholder="ex: PNA / Pharmacie Nationale" class="w-full border border-outline-variant rounded px-md py-sm font-body-sm" type="text"/>
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full bg-primary text-on-primary font-bold py-sm rounded hover:bg-surface-tint transition-colors">
                Enregistrer l'Entrée
            </button>
        </div>
    </form>
</div>

<!-- Table des Entrées -->
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-outline-variant">
        <thead class="bg-surface">
            <tr>
                <th class="px-md py-sm text-left font-label-md text-label-md text-on-surface-variant uppercase">Médicament</th>
                <th class="px-md py-sm text-right font-label-md text-label-md text-on-surface-variant uppercase">Quantité Ajoutée</th>
                <th class="px-md py-sm text-left font-label-md text-label-md text-on-surface-variant uppercase">Responsable / Notes</th>
                <th class="px-md py-sm text-right font-label-md text-label-md text-on-surface-variant uppercase">Date & Heure</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-outline-variant">
            @forelse($entries as $entry)
                <tr class="hover:bg-surface-container-lowest/50">
                    <td class="px-md py-sm font-body-md text-body-md font-semibold text-on-surface">
                        {{ $entry->medication->name ?? 'Médicament' }} {{ $entry->medication->dosage ?? '' }}
                    </td>
                    <td class="px-md py-sm text-right font-bold text-secondary">
                        +{{ $entry->quantity }}
                    </td>
                    <td class="px-md py-sm font-body-sm text-body-sm text-on-surface-variant">
                        <span class="font-bold text-on-surface">{{ $entry->performed_by_name ?? 'Agent' }}</span> - {{ $entry->notes ?? 'Réception stock' }}
                    </td>
                    <td class="px-md py-sm text-right font-body-sm text-body-sm text-on-surface-variant">
                        {{ $entry->created_at->format('d/m/Y à H:i') }}
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="p-md text-center text-on-surface-variant">Aucune entrée enregistrée.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
