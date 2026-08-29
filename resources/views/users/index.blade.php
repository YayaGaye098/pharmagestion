@extends('layouts.app')

@section('title', 'PharmaGestion - Gestion des Utilisateurs')
@section('page-title', 'Gestion du Personnel & Utilisateurs')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-lg gap-md">
    <div>
        <h2 class="font-headline-md text-headline-md text-on-surface font-bold">Personnel & Accès</h2>
        <p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">Gérez les comptes des médecins, pharmaciens et agents de santé.</p>
    </div>
    <button onclick="document.getElementById('newUserForm').classList.toggle('hidden')" class="bg-primary text-on-primary px-lg py-sm rounded-lg font-label-md text-label-md flex items-center gap-sm shadow-sm font-bold cursor-pointer">
        <span class="material-symbols-outlined">person_add</span>
        Ajouter un membre
    </button>
</div>

<!-- Formulaire Nouvel Utilisateur -->
<div id="newUserForm" class="hidden mb-lg bg-surface-container-lowest border border-outline-variant rounded-xl p-lg shadow-sm">
    <h3 class="font-title-lg text-title-lg font-bold text-on-surface mb-md">Créer un Compte Utilisateur</h3>
    <form action="{{ route('users.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-md">
        @csrf
        <div>
            <label class="font-label-md text-label-md text-on-surface">Nom Complet</label>
            <input name="name" required placeholder="ex: Dr. Sow" class="w-full border border-outline-variant rounded px-md py-sm font-body-sm" type="text"/>
        </div>
        <div>
            <label class="font-label-md text-label-md text-on-surface">Email</label>
            <input name="email" required placeholder="sow@pharmacie.sn" class="w-full border border-outline-variant rounded px-md py-sm font-body-sm" type="email"/>
        </div>
        <div>
            <label class="font-label-md text-label-md text-on-surface">Téléphone</label>
            <input name="phone" placeholder="+221 77 000 00 00" class="w-full border border-outline-variant rounded px-md py-sm font-body-sm" type="tel"/>
        </div>
        <div>
            <label class="font-label-md text-label-md text-on-surface">Rôle</label>
            <select name="role" required class="w-full border border-outline-variant rounded px-md py-sm font-body-sm">
                <option value="admin">Administrateur / Médecin Chef</option>
                <option value="pharmacist">Pharmacien / Responsable Stock</option>
                <option value="agent">Agent de santé</option>
            </select>
        </div>
        <div>
            <label class="font-label-md text-label-md text-on-surface">Mot de passe provisoire</label>
            <input name="password" required placeholder="••••••••" class="w-full border border-outline-variant rounded px-md py-sm font-body-sm" type="password"/>
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full bg-primary text-on-primary font-bold py-sm rounded hover:bg-surface-tint transition-colors">
                Créer l'utilisateur
            </button>
        </div>
    </form>
</div>

<!-- Liste des Utilisateurs -->
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-outline-variant">
        <thead class="bg-surface">
            <tr>
                <th class="px-md py-sm text-left font-label-md text-label-md text-on-surface-variant uppercase">Membre</th>
                <th class="px-md py-sm text-left font-label-md text-label-md text-on-surface-variant uppercase">Email</th>
                <th class="px-md py-sm text-left font-label-md text-label-md text-on-surface-variant uppercase">Téléphone</th>
                <th class="px-md py-sm text-center font-label-md text-label-md text-on-surface-variant uppercase">Rôle</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-outline-variant">
            @foreach($users as $u)
                <tr class="hover:bg-surface-container-lowest/50">
                    <td class="px-md py-sm font-body-md text-body-md font-bold text-on-surface">
                        {{ $u->name }}
                    </td>
                    <td class="px-md py-sm font-body-sm text-body-sm text-on-surface-variant">{{ $u->email }}</td>
                    <td class="px-md py-sm font-body-sm text-body-sm text-on-surface-variant">{{ $u->phone ?? '-' }}</td>
                    <td class="px-md py-sm text-center">
                        <span class="px-md py-xs rounded-full font-label-md text-[10px] font-bold uppercase {{ $u->role === 'admin' ? 'bg-primary-container text-on-primary-container' : 'bg-surface-variant text-on-surface-variant' }}">
                            {{ $u->role }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
