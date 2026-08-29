@extends('layouts.app')

@section('title', 'PharmaGestion - Gestion des Vendeuses')
@section('page-title', 'Gestion des Vendeuses')

@section('content')
<div class="flex justify-between items-end mb-lg">
    <div>
        <h2 class="font-display-lg text-display-lg text-on-background mb-xs font-bold">Gestion des Vendeuses</h2>
        <p class="font-body-md text-body-md text-on-surface-variant">Création de comptes vendeuses et supervision des opérations.</p>
    </div>
    <button onclick="document.getElementById('newVendorModal').classList.toggle('hidden')" class="bg-primary text-on-primary font-label-md text-label-md py-sm px-md rounded-lg flex items-center justify-center gap-xs shadow-sm hover:bg-surface-tint transition-colors font-bold cursor-pointer">
        <span class="material-symbols-outlined">person_add</span>
        Ajouter une Vendeuse
    </button>
</div>

<!-- Modal Ajouter une Vendeuse -->
<div id="newVendorModal" class="hidden mb-lg bg-surface-container-lowest border border-outline-variant rounded-xl p-lg shadow-sm">
    <h3 class="font-title-lg text-title-lg font-bold text-on-surface mb-md">Nouveau Compte Vendeuse</h3>
    <form action="{{ route('admin.vendors.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-md">
        @csrf
        <div>
            <label class="font-label-md text-label-md text-on-surface">Nom Complet</label>
            <input name="name" required placeholder="ex: Aminata Sow" class="w-full border border-outline-variant rounded px-md py-sm font-body-sm" type="text"/>
        </div>
        <div>
            <label class="font-label-md text-label-md text-on-surface">Email</label>
            <input name="email" required placeholder="aminata@pharmacie.sn" class="w-full border border-outline-variant rounded px-md py-sm font-body-sm" type="email"/>
        </div>
        <div>
            <label class="font-label-md text-label-md text-on-surface">Téléphone</label>
            <input name="phone" placeholder="+221 77 000 00 00" class="w-full border border-outline-variant rounded px-md py-sm font-body-sm" type="tel"/>
        </div>
        <div>
            <label class="font-label-md text-label-md text-on-surface">Mot de Passe</label>
            <input name="password" required placeholder="••••••••" class="w-full border border-outline-variant rounded px-md py-sm font-body-sm" type="password"/>
        </div>
        <div class="md:col-span-4 flex justify-end">
            <button type="submit" class="bg-primary text-on-primary font-bold px-lg py-sm rounded hover:bg-surface-tint transition-colors">
                Enregistrer le Compte Vendeuse
            </button>
        </div>
    </form>
</div>

<!-- Table des Vendeuses -->
<div class="bg-surface rounded-xl border border-outline-variant shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-container-low border-b border-outline-variant">
                    <th class="py-sm px-md font-label-md text-label-md text-on-surface-variant uppercase">Vendeuse</th>
                    <th class="py-sm px-md font-label-md text-label-md text-on-surface-variant uppercase">Statut</th>
                    <th class="py-sm px-md font-label-md text-label-md text-on-surface-variant uppercase text-right">Nombre de Ventes</th>
                    <th class="py-sm px-md font-label-md text-label-md text-on-surface-variant uppercase text-right">Chiffre d'Affaires Généré</th>
                    <th class="py-sm px-md font-label-md text-label-md text-on-surface-variant uppercase text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($vendors as $v)
                    <tr class="hover:bg-surface-container-lowest transition-colors bg-white {{ $v->status === 'inactive' ? 'opacity-60' : '' }}">
                        <td class="py-sm px-md">
                            <div class="flex items-center gap-sm">
                                <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-bold">
                                    {{ substr($v->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-title-lg text-title-lg text-on-surface text-[16px] font-bold">{{ $v->name }}</p>
                                    <p class="font-body-sm text-body-sm text-on-surface-variant">{{ $v->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-sm px-md">
                            @if($v->status === 'active')
                                <span class="inline-flex items-center gap-xs px-2 py-1 rounded-full bg-primary-container/20 text-primary font-label-md text-label-md font-bold">
                                    <span class="w-2 h-2 rounded-full bg-primary"></span>
                                    Actif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-xs px-2 py-1 rounded-full bg-error-container text-on-error-container font-label-md text-label-md font-bold">
                                    <span class="w-2 h-2 rounded-full bg-error"></span>
                                    Inactif
                                </span>
                            @endif
                        </td>
                        <td class="py-sm px-md font-body-md text-body-md text-on-surface text-right font-bold">{{ number_format($v->sales_count) }}</td>
                        <td class="py-sm px-md font-currency-md text-currency-md text-primary text-right font-bold">{{ number_format($v->sales_total ?? 0, 0, ',', ' ') }} FCFA</td>
                        <td class="py-sm px-md text-center">
                            <form action="{{ route('admin.vendors.toggle', $v->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-sm py-xs rounded border border-outline-variant text-on-surface hover:bg-surface-container font-label-md text-label-md font-bold">
                                    {{ $v->status === 'active' ? 'Désactiver' : 'Activer' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-md text-center text-on-surface-variant">Aucune vendeuse créée pour le moment.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
