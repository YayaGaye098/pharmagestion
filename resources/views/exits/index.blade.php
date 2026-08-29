@extends('layouts.app')

@section('title', 'PharmaGestion - Sorties de Stock')
@section('page-title', 'Sorties de Stock')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-lg gap-md">
    <div>
        <h2 class="font-headline-md text-headline-md text-on-surface font-bold">Distribution & Sorties de Stock</h2>
        <p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">Enregistrez les distributions, prescriptions et imprimez les reçus.</p>
    </div>
    <button onclick="document.getElementById('newExitForm').classList.toggle('hidden')" class="bg-primary text-on-primary px-lg py-sm rounded-lg font-label-md text-label-md flex items-center gap-sm shadow-sm font-bold cursor-pointer">
        <span class="material-symbols-outlined">remove</span>
        Nouvelle Sortie
    </button>
</div>

<!-- Formulaire Nouvelle Sortie -->
<div id="newExitForm" class="hidden mb-lg bg-surface-container-lowest border border-outline-variant rounded-xl p-lg shadow-sm">
    <h3 class="font-title-lg text-title-lg font-bold text-on-surface mb-md">Enregistrer une Sortie / Prescription</h3>
    <form action="{{ route('exits.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-md">
        @csrf
        <div>
            <label class="font-label-md text-label-md text-on-surface">Médicament</label>
            <select name="medication_id" required class="w-full border border-outline-variant rounded px-md py-sm font-body-sm">
                @foreach($medications as $med)
                    <option value="{{ $med->id }}">{{ $med->name }} ({{ $med->dosage }}) - Reste: {{ $med->stock_quantity }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="font-label-md text-label-md text-on-surface">Quantité Distribuée</label>
            <input name="quantity" min="1" required placeholder="ex: 2" class="w-full border border-outline-variant rounded px-md py-sm font-body-sm" type="number"/>
        </div>
        <div>
            <label class="font-label-md text-label-md text-on-surface">Bénéficiaire / Ordonnance</label>
            <input name="patient_or_service" placeholder="ex: Patient N°204 / Maternité" class="w-full border border-outline-variant rounded px-md py-sm font-body-sm" type="text"/>
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full bg-primary text-on-primary font-bold py-sm rounded hover:bg-surface-tint transition-colors">
                Enregistrer la Sortie
            </button>
        </div>
    </form>
</div>

<!-- Table des Sorties -->
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-outline-variant">
        <thead class="bg-surface">
            <tr>
                <th class="px-md py-sm text-left font-label-md text-label-md text-on-surface-variant uppercase">Médicament</th>
                <th class="px-md py-sm text-right font-label-md text-label-md text-on-surface-variant uppercase">Quantité Retirée</th>
                <th class="px-md py-sm text-left font-label-md text-label-md text-on-surface-variant uppercase">Prescripteur / Bénéficiaire</th>
                <th class="px-md py-sm text-right font-label-md text-label-md text-on-surface-variant uppercase">Date & Heure</th>
                <th class="px-md py-sm text-center font-label-md text-label-md text-on-surface-variant uppercase">Document</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-outline-variant">
            @forelse($exits as $exit)
                <tr class="hover:bg-surface-container-lowest/50">
                    <td class="px-md py-sm font-body-md text-body-md font-semibold text-on-surface">
                        {{ $exit->medication->name ?? 'Médicament' }} {{ $exit->medication->dosage ?? '' }}
                    </td>
                    <td class="px-md py-sm text-right font-bold text-error">
                        -{{ $exit->quantity }}
                    </td>
                    <td class="px-md py-sm font-body-sm text-body-sm text-on-surface-variant">
                        <span class="font-bold text-on-surface">{{ $exit->performed_by_name ?? 'Agent' }}</span> - {{ $exit->notes ?? 'Prescription' }}
                    </td>
                    <td class="px-md py-sm text-right font-body-sm text-body-sm text-on-surface-variant">
                        {{ $exit->created_at->format('d/m/Y à H:i') }}
                    </td>
                    <td class="px-md py-sm text-center">
                        <a href="{{ route('exits.pdf', $exit->id) }}" class="inline-flex items-center gap-xs px-sm py-xs bg-surface-container-high text-primary rounded font-label-md text-label-md hover:bg-surface-variant transition-colors">
                            <span class="material-symbols-outlined text-[16px]">picture_as_pdf</span>
                            Reçu PDF
                        </a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="p-md text-center text-on-surface-variant">Aucune sortie enregistrée.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
