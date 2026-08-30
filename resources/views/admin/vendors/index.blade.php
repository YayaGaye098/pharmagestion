@extends('layouts.app')

@section('title', 'PharmaGestion - Gestion des Vendeuses')
@section('page-title', 'Gestion des Vendeuses')

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-900">Comptes Vendeuses & Guichetières</h2>
        <p class="text-xs text-gray-500 mt-0.5">Créez les comptes pour votre personnel de vente et suivez leurs performances de caisse.</p>
    </div>
    <button onclick="document.getElementById('newVendorModal').classList.remove('hidden')" class="bg-primary hover:bg-primary-hover text-white font-bold text-xs py-2.5 px-4 rounded-xl flex items-center gap-1.5 shadow-sm transition-all cursor-pointer">
        <span class="material-symbols-outlined text-[18px]">person_add</span>
        <span>Créer un Compte Vendeuse</span>
    </button>
</div>

<!-- Modal Créer une Vendeuse -->
<div id="newVendorModal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full overflow-hidden border border-outline-variant animate-in fade-in zoom-in duration-150">
        <div class="p-5 bg-surface-low border-b border-outline-variant flex justify-between items-center">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-2xl">badge</span>
                <h3 class="font-bold text-base text-gray-900">Nouveau Compte Vendeuse</h3>
            </div>
            <button onclick="document.getElementById('newVendorModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form action="{{ route('admin.vendors.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nom & Prénom <span class="text-error">*</span></label>
                <input 
                    type="text" 
                    name="name" 
                    required 
                    placeholder="ex: Aminata Sow" 
                    class="w-full text-xs bg-gray-50 border border-outline-variant rounded-lg p-2.5 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary font-medium"
                />
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Email / Identifiant de connexion <span class="text-error">*</span></label>
                <input 
                    type="email" 
                    name="email" 
                    required 
                    placeholder="aminata.sow@pharmacie.sn" 
                    class="w-full text-xs bg-gray-50 border border-outline-variant rounded-lg p-2.5 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary font-medium"
                />
                <p class="text-[11px] text-gray-400 mt-1">Cet email servira d'identifiant à la vendeuse pour se connecter au guichet.</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Téléphone</label>
                <input 
                    type="tel" 
                    name="phone" 
                    placeholder="+221 77 000 00 00" 
                    class="w-full text-xs bg-gray-50 border border-outline-variant rounded-lg p-2.5 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary font-medium"
                />
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Mot de Passe <span class="text-error">*</span></label>
                <input 
                    type="password" 
                    name="password" 
                    required 
                    placeholder="••••••••" 
                    minlength="6"
                    class="w-full text-xs bg-gray-50 border border-outline-variant rounded-lg p-2.5 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary font-medium"
                />
                <p class="text-[11px] text-gray-400 mt-1">Minimum 6 caractères. Donnez ce mot de passe à la vendeuse.</p>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="document.getElementById('newVendorModal').classList.add('hidden')" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-lg transition-colors">
                    Annuler
                </button>
                <button type="submit" class="px-5 py-2 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-lg shadow-sm transition-colors">
                    Créer le Compte
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Table des Vendeuses -->
<div class="bg-surface-lowest border border-outline-variant/80 rounded-2xl shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm border-collapse">
            <thead>
                <tr class="bg-gray-50/80 text-[11px] font-bold text-gray-500 uppercase border-b border-gray-100">
                    <th class="py-3.5 px-5">Vendeuse</th>
                    <th class="py-3.5 px-5">Contact</th>
                    <th class="py-3.5 px-5">Statut</th>
                    <th class="py-3.5 px-5 text-right">Ventes Réalisées</th>
                    <th class="py-3.5 px-5 text-right">Recette Aujourd'hui</th>
                    <th class="py-3.5 px-5 text-right">Chiffre d'Affaires Total</th>
                    <th class="py-3.5 px-5 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($vendors as $v)
                    <tr class="hover:bg-gray-50/50 transition-colors {{ $v->status === 'inactive' ? 'bg-gray-50/70 opacity-60' : '' }}">
                        <td class="py-3.5 px-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-bold text-sm">
                                    {{ substr($v->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 text-xs">{{ $v->name }}</p>
                                    <p class="text-[11px] text-gray-400">{{ $v->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3.5 px-5 text-xs text-gray-600 font-medium">
                            {{ $v->phone ?? 'Non renseigné' }}
                        </td>
                        <td class="py-3.5 px-5 text-xs">
                            @if($v->status === 'active')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-bold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Actif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-red-50 text-red-700 border border-red-200 text-[11px] font-bold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Inactif
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-5 text-right text-xs font-bold text-gray-900">
                            {{ number_format($v->sales_count) }} ticket(s)
                        </td>
                        <td class="py-3.5 px-5 text-right text-xs font-bold text-emerald-700">
                            {{ number_format($v->today_sales_total ?? 0, 0, ',', ' ') }} FCFA
                        </td>
                        <td class="py-3.5 px-5 text-right text-xs font-extrabold text-primary">
                            {{ number_format($v->sales_total ?? 0, 0, ',', ' ') }} FCFA
                        </td>
                        <td class="py-3.5 px-5 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <!-- Activer / Désactiver -->
                                <form action="{{ route('admin.vendors.toggle', $v->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button 
                                        type="submit" 
                                        class="px-2.5 py-1 rounded-lg border text-xs font-bold transition-colors {{ $v->status === 'active' ? 'border-amber-200 text-amber-700 bg-amber-50 hover:bg-amber-100' : 'border-emerald-200 text-emerald-700 bg-emerald-50 hover:bg-emerald-100' }}"
                                        title="{{ $v->status === 'active' ? 'Désactiver le compte' : 'Activer le compte' }}">
                                        {{ $v->status === 'active' ? 'Désactiver' : 'Activer' }}
                                    </button>
                                </form>

                                <!-- Modifier Modal Trigger -->
                                <button 
                                    type="button" 
                                    onclick="openEditModal({{ json_encode($v) }})"
                                    class="p-1.5 text-gray-500 hover:text-primary hover:bg-gray-100 rounded-lg transition-colors"
                                    title="Modifier les informations">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </button>

                                <!-- Supprimer -->
                                <form action="{{ route('admin.vendors.destroy', $v->id) }}" method="POST" class="inline" onsubmit="return confirm('Confirmer la suppression / désactivation de ce compte vendeuse ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button 
                                        type="submit" 
                                        class="p-1.5 text-gray-400 hover:text-error hover:bg-error-container/30 rounded-lg transition-colors"
                                        title="Supprimer">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-10 text-center text-gray-400">
                            <span class="material-symbols-outlined text-4xl text-gray-300 mb-2 block">group_off</span>
                            Aucun compte vendeuse créé. Cliquez sur "Créer un Compte Vendeuse" ci-dessus.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Modifier une Vendeuse -->
<div id="editVendorModal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full overflow-hidden border border-outline-variant">
        <div class="p-5 bg-surface-low border-b border-outline-variant flex justify-between items-center">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-2xl">edit_note</span>
                <h3 class="font-bold text-base text-gray-900">Modifier le Compte Vendeuse</h3>
            </div>
            <button onclick="document.getElementById('editVendorModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form id="editVendorForm" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nom & Prénom <span class="text-error">*</span></label>
                <input 
                    type="text" 
                    id="editName"
                    name="name" 
                    required 
                    class="w-full text-xs bg-gray-50 border border-outline-variant rounded-lg p-2.5 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary font-medium"
                />
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Email <span class="text-error">*</span></label>
                <input 
                    type="email" 
                    id="editEmail"
                    name="email" 
                    required 
                    class="w-full text-xs bg-gray-50 border border-outline-variant rounded-lg p-2.5 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary font-medium"
                />
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Téléphone</label>
                <input 
                    type="tel" 
                    id="editPhone"
                    name="phone" 
                    class="w-full text-xs bg-gray-50 border border-outline-variant rounded-lg p-2.5 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary font-medium"
                />
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nouveau Mot de Passe (laisser vide pour ne pas changer)</label>
                <input 
                    type="password" 
                    name="password" 
                    placeholder="••••••••" 
                    minlength="6"
                    class="w-full text-xs bg-gray-50 border border-outline-variant rounded-lg p-2.5 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary font-medium"
                />
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="document.getElementById('editVendorModal').classList.add('hidden')" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-lg transition-colors">
                    Annuler
                </button>
                <button type="submit" class="px-5 py-2 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-lg shadow-sm transition-colors">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(vendor) {
        document.getElementById('editName').value = vendor.name;
        document.getElementById('editEmail').value = vendor.email;
        document.getElementById('editPhone').value = vendor.phone || '';
        document.getElementById('editVendorForm').action = "/admin/vendors/" + vendor.id;
        document.getElementById('editVendorModal').classList.remove('hidden');
    }
</script>
@endsection
