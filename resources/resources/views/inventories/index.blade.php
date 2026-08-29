@extends('layouts.app')

@section('title', 'PharmaGestion - Inventaires Physiques')
@section('page-title', 'Inventaires Physiques')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-lg gap-md">
    <div>
        <h2 class="font-headline-md text-headline-md text-on-surface font-bold">Audit d'Inventaire Physique</h2>
        <p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">Comparez et ajustez le stock physique au stock théorique.</p>
    </div>
    <a href="{{ route('inventories.pdf') }}" class="bg-secondary text-on-secondary px-lg py-sm rounded-lg font-label-md text-label-md flex items-center gap-sm shadow-sm font-bold cursor-pointer hover:bg-secondary-container transition-colors">
        <span class="material-symbols-outlined">print</span>
        Imprimer Fiche de Comptage (PDF)
    </a>
</div>

<div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-outline-variant">
        <thead class="bg-surface">
            <tr>
                <th class="px-md py-sm text-left font-label-md text-label-md text-on-surface-variant uppercase">Médicament</th>
                <th class="px-md py-sm text-right font-label-md text-label-md text-on-surface-variant uppercase">Stock Système</th>
                <th class="px-md py-sm text-center font-label-md text-label-md text-on-surface-variant uppercase">Stock Physique Compté</th>
                <th class="px-md py-sm text-right font-label-md text-label-md text-on-surface-variant uppercase">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-outline-variant">
            @foreach($medications as $med)
                <tr class="hover:bg-surface-container-lowest/50">
                    <td class="px-md py-sm font-body-md text-body-md font-semibold text-on-surface">
                        {{ $med->name }} {{ $med->dosage }} ({{ $med->form }})
                    </td>
                    <td class="px-md py-sm text-right font-bold text-on-surface">
                        {{ number_format($med->stock_quantity) }}
                    </td>
                    <form action="{{ route('inventories.update', $med->id) }}" method="POST">
                        @csrf
                        <td class="px-md py-sm text-center">
                            <input name="physical_stock" value="{{ $med->stock_quantity }}" class="w-28 border border-outline-variant rounded text-center py-xs font-bold" type="number" min="0"/>
                        </td>
                        <td class="px-md py-sm text-right">
                            <button type="submit" class="px-md py-xs bg-primary text-on-primary rounded font-label-md text-label-md font-bold">
                                Ajuster
                            </button>
                        </td>
                    </form>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
