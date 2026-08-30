@extends('layouts.app')

@section('title', 'PharmaGestion - Gestion des Médicaments')
@section('page-title', 'Catalogue des Médicaments')

@section('content')
<!-- Page Header & Actions -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
    <div>
        <h2 class="text-xl font-bold text-gray-900">Catalogue des Médicaments & Produits</h2>
        <p class="text-xs text-gray-500 mt-0.5">Enregistrez de nouvelles références, configurez les prix et surveillez les stocks disponibles.</p>
    </div>
    <button onclick="toggleModal('addMedicationModal')" class="bg-primary hover:bg-primary-hover text-white px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-1.5 shadow-sm transition-all cursor-pointer">
        <span class="material-symbols-outlined text-[18px]">add_circle</span>
        <span>Ajouter un Médicament</span>
    </button>
</div>

<!-- 4 Cartes d'Indicateurs du Catalogue -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-surface-lowest border border-outline-variant/80 p-4 rounded-xl shadow-xs">
        <span class="text-xs uppercase font-bold text-gray-400">Total Références</span>
        <h3 class="text-2xl font-extrabold text-gray-900 mt-1">{{ number_format($totalReferences) }}</h3>
        <p class="text-xs text-gray-400 mt-0.5">Produits enregistrés au catalogue</p>
    </div>

    <div class="bg-surface-lowest border border-outline-variant/80 p-4 rounded-xl shadow-xs">
        <span class="text-xs uppercase font-bold text-gray-400">Stock Total en Rayon</span>
        <h3 class="text-2xl font-extrabold text-primary mt-1">{{ number_format($totalStockUnits) }} <span class="text-xs font-bold text-gray-500">unités</span></h3>
        <p class="text-xs text-gray-400 mt-0.5">Toutes formes confondues</p>
    </div>

    <div class="bg-surface-lowest border border-outline-variant/80 p-4 rounded-xl shadow-xs">
        <span class="text-xs uppercase font-bold text-gray-400">Valeur Marchande du Stock</span>
        <h3 class="text-2xl font-extrabold text-gray-900 mt-1">{{ number_format($totalStockValue, 0, ',', ' ') }} <span class="text-xs font-bold text-gray-500">FCFA</span></h3>
        <p class="text-xs text-gray-400 mt-0.5">Au prix public actuel</p>
    </div>

    <div class="bg-surface-lowest border border-outline-variant/80 p-4 rounded-xl shadow-xs">
        <span class="text-xs uppercase font-bold text-gray-400">Alertes Stock</span>
        <div class="flex items-center gap-2 mt-1">
            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                {{ $lowStockCount }} faibles
            </span>
            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                {{ $outOfStockCount }} épuisés
            </span>
        </div>
        <p class="text-xs text-gray-400 mt-1">Nécessitent un réassort</p>
    </div>
</div>

<!-- Search & Filters Bar -->
<div class="bg-surface-lowest p-4 rounded-xl border border-outline-variant/80 shadow-xs mb-6">
    <form action="{{ route('medications.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
        <!-- Search Input -->
        <div class="md:col-span-2">
            <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Recherche</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">search</span>
                <input 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Nom, code, dosage..." 
                    class="w-full pl-9 pr-3 py-2 text-xs bg-gray-50 border border-outline-variant rounded-lg focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary font-medium" 
                    type="text"
                />
            </div>
        </div>

        <!-- Filter Category -->
        <div>
            <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Catégorie</label>
            <select name="category" onchange="this.form.submit()" class="w-full text-xs bg-gray-50 border border-outline-variant rounded-lg p-2 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary font-medium">
                <option value="Toutes">Toutes les catégories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->name }}" {{ request('category') == $cat->name ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Filter Statut -->
        <div>
            <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">État du Stock</label>
            <select name="status" onchange="this.form.submit()" class="w-full text-xs bg-gray-50 border border-outline-variant rounded-lg p-2 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary font-medium">
                <option value="">Tous les états</option>
                <option value="ok" {{ request('status') === 'ok' ? 'selected' : '' }}>Stock Normal</option>
                <option value="faible" {{ request('status') === 'faible' ? 'selected' : '' }}>Stock Faible</option>
                <option value="rupture" {{ request('status') === 'rupture' ? 'selected' : '' }}>Rupture de Stock</option>
            </select>
        </div>

        <!-- Reset Button -->
        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 bg-primary hover:bg-primary-hover text-white text-xs font-bold py-2 px-3 rounded-lg shadow-xs transition-colors flex items-center justify-center gap-1">
                <span class="material-symbols-outlined text-[16px]">filter_list</span>
                <span>Filtrer</span>
            </button>
            <a href="{{ route('medications.index') }}" class="p-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg transition-colors" title="Réinitialiser">
                <span class="material-symbols-outlined text-[16px]">refresh</span>
            </a>
        </div>
    </form>
</div>

<!-- Data Table (Desktop View) -->
<div class="bg-surface-lowest rounded-2xl border border-outline-variant/80 shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm border-collapse">
            <thead>
                <tr class="bg-gray-50/80 text-[11px] font-bold text-gray-500 uppercase border-b border-gray-100">
                    <th class="py-3.5 px-5">Code</th>
                    <th class="py-3.5 px-5">Médicament</th>
                    <th class="py-3.5 px-5">Dosage & Forme</th>
                    <th class="py-3.5 px-5">Catégorie</th>
                    <th class="py-3.5 px-5 text-right">Stock Disponible</th>
                    <th class="py-3.5 px-5 text-right">Prix Unitaire</th>
                    <th class="py-3.5 px-5 text-center">Date Péremption</th>
                    <th class="py-3.5 px-5 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($medications as $med)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-3.5 px-5 font-mono text-xs font-bold text-gray-400">
                            {{ $med->code }}
                        </td>
                        <td class="py-3.5 px-5">
                            <div class="font-bold text-gray-900 text-xs">{{ $med->name }}</div>
                            <div class="text-[11px] text-gray-400">Seuil min : {{ $med->min_threshold }} u.</div>
                        </td>
                        <td class="py-3.5 px-5 text-xs text-gray-600">
                            {{ $med->dosage }} <span class="text-gray-400">({{ $med->form }})</span>
                        </td>
                        <td class="py-3.5 px-5 text-xs">
                            <span class="bg-gray-100 text-gray-700 px-2.5 py-0.5 rounded-full font-medium text-[11px]">
                                {{ $med->category->name ?? 'Général' }}
                            </span>
                        </td>
                        <td class="py-3.5 px-5 text-right">
                            @if($med->stock_quantity <= 0)
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                                    0 (Rupture)
                                </span>
                            @elseif($med->stock_quantity <= $med->min_threshold)
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    {{ $med->stock_quantity }} (Faible)
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    {{ $med->stock_quantity }}
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-5 text-right font-extrabold text-primary text-sm">
                            {{ number_format($med->unit_price, 0, ',', ' ') }} FCFA
                        </td>
                        <td class="py-3.5 px-5 text-center text-xs text-gray-500">
                            @if($med->expiration_date)
                                {{ $med->expiration_date->format('d/m/Y') }}
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-5 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <button 
                                    type="button" 
                                    onclick="openEditMedicationModal({{ json_encode($med) }})"
                                    class="p-1.5 text-gray-500 hover:text-primary hover:bg-primary/10 rounded-lg transition-colors"
                                    title="Modifier la fiche produit">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </button>
                                <form action="{{ route('medications.destroy', $med->id) }}" method="POST" class="inline" onsubmit="return confirm('Confirmer la suppression du médicament {{ $med->name }} ?')">
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
                        <td colspan="8" class="py-10 text-center text-gray-400">
                            <span class="material-symbols-outlined text-4xl text-gray-300 mb-2 block">medication</span>
                            Aucun médicament trouvé avec les filtres actuels.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- ======================= MODAL 1: AJOUTER UN MEDICAMENT ======================= -->
<div id="addMedicationModal" class="fixed inset-0 bg-black/50 backdrop-blur-xs hidden items-center justify-center z-50 p-4">
    <div class="bg-white border border-outline-variant rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden animate-in fade-in zoom-in duration-150">
        <!-- Header -->
        <div class="p-5 bg-surface-low border-b border-outline-variant flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center font-bold">
                    <span class="material-symbols-outlined text-[20px]">add_circle</span>
                </div>
                <h3 class="font-bold text-base text-gray-900">Ajouter un Nouveau Médicament</h3>
            </div>
            <button onclick="toggleModal('addMedicationModal')" class="text-gray-400 hover:text-gray-600">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- Formulaire -->
        <form action="{{ route('medications.store') }}" method="POST" class="p-6 space-y-4 max-h-[80vh] overflow-y-auto">
            @csrf
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Code / Référence <span class="text-error">*</span></label>
                    <input name="code" placeholder="ex: PAR-002" required class="w-full text-xs bg-gray-50 border border-outline-variant rounded-lg p-2.5 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary font-medium uppercase" type="text"/>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nom Commercial <span class="text-error">*</span></label>
                    <input name="name" placeholder="ex: Paracétamol" required class="w-full text-xs bg-gray-50 border border-outline-variant rounded-lg p-2.5 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary font-medium" type="text"/>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Dosage <span class="text-error">*</span></label>
                    <input name="dosage" placeholder="ex: 500mg, 1g, 200mg/5ml" required class="w-full text-xs bg-gray-50 border border-outline-variant rounded-lg p-2.5 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary font-medium" type="text"/>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Forme Galénique <span class="text-error">*</span></label>
                    <input name="form" placeholder="ex: Comprimés, Sirop, Gélules, Injectable" required class="w-full text-xs bg-gray-50 border border-outline-variant rounded-lg p-2.5 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary font-medium" type="text"/>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Catégorie</label>
                    <select name="category_id" id="addCategorySelect" class="w-full text-xs bg-gray-50 border border-outline-variant rounded-lg p-2.5 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary font-medium">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Ou Nouvelle Catégorie</label>
                    <input name="new_category" placeholder="ex: Antipaludiques..." class="w-full text-xs bg-gray-50 border border-outline-variant rounded-lg p-2.5 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary font-medium" type="text"/>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Prix Public Unitaire (FCFA) <span class="text-error">*</span></label>
                    <input name="unit_price" placeholder="500" required min="0" step="10" class="w-full text-xs bg-gray-50 border border-outline-variant rounded-lg p-2.5 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary font-bold text-primary" type="number"/>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Date de Péremption</label>
                    <input name="expiration_date" class="w-full text-xs bg-gray-50 border border-outline-variant rounded-lg p-2.5 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary font-medium" type="date"/>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Quantité Initiale en Stock <span class="text-error">*</span></label>
                    <input name="stock_quantity" value="100" required min="0" class="w-full text-xs bg-gray-50 border border-outline-variant rounded-lg p-2.5 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary font-bold text-gray-900" type="number"/>
                    <p class="text-[10px] text-gray-400 mt-0.5">Sera tracée automatiquement comme entrée de stock initiale.</p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Seuil d'Alerte Minimum <span class="text-error">*</span></label>
                    <input name="min_threshold" value="20" required min="1" class="w-full text-xs bg-gray-50 border border-outline-variant rounded-lg p-2.5 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary font-medium" type="number"/>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="toggleModal('addMedicationModal')" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-lg transition-colors">
                    Annuler
                </button>
                <button type="submit" class="px-5 py-2 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-lg shadow-sm transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-[18px]">check</span>
                    <span>Enregistrer le Produit</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ======================= MODAL 2: MODIFIER UN MEDICAMENT ======================= -->
<div id="editMedicationModal" class="fixed inset-0 bg-black/50 backdrop-blur-xs hidden items-center justify-center z-50 p-4">
    <div class="bg-white border border-outline-variant rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden animate-in fade-in zoom-in duration-150">
        <!-- Header -->
        <div class="p-5 bg-surface-low border-b border-outline-variant flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center font-bold">
                    <span class="material-symbols-outlined text-[20px]">edit</span>
                </div>
                <h3 class="font-bold text-base text-gray-900">Modifier le Médicament</h3>
            </div>
            <button onclick="toggleModal('editMedicationModal')" class="text-gray-400 hover:text-gray-600">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- Formulaire -->
        <form id="editMedicationForm" method="POST" class="p-6 space-y-4 max-h-[80vh] overflow-y-auto">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Code / Référence <span class="text-error">*</span></label>
                    <input id="editCode" name="code" required class="w-full text-xs bg-gray-50 border border-outline-variant rounded-lg p-2.5 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary font-medium uppercase" type="text"/>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nom Commercial <span class="text-error">*</span></label>
                    <input id="editName" name="name" required class="w-full text-xs bg-gray-50 border border-outline-variant rounded-lg p-2.5 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary font-medium" type="text"/>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Dosage <span class="text-error">*</span></label>
                    <input id="editDosage" name="dosage" required class="w-full text-xs bg-gray-50 border border-outline-variant rounded-lg p-2.5 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary font-medium" type="text"/>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Forme Galénique <span class="text-error">*</span></label>
                    <input id="editForm" name="form" required class="w-full text-xs bg-gray-50 border border-outline-variant rounded-lg p-2.5 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary font-medium" type="text"/>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Catégorie</label>
                    <select id="editCategoryId" name="category_id" class="w-full text-xs bg-gray-50 border border-outline-variant rounded-lg p-2.5 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary font-medium">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nouvelle Catégorie</label>
                    <input name="new_category" placeholder="Laisser vide si inchangée" class="w-full text-xs bg-gray-50 border border-outline-variant rounded-lg p-2.5 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary font-medium" type="text"/>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Prix Unitaire (FCFA) <span class="text-error">*</span></label>
                    <input id="editUnitPrice" name="unit_price" required min="0" step="10" class="w-full text-xs bg-gray-50 border border-outline-variant rounded-lg p-2.5 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary font-bold text-primary" type="number"/>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Seuil Min <span class="text-error">*</span></label>
                    <input id="editMinThreshold" name="min_threshold" required min="1" class="w-full text-xs bg-gray-50 border border-outline-variant rounded-lg p-2.5 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary font-medium" type="number"/>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Date Péremption</label>
                    <input id="editExpirationDate" name="expiration_date" class="w-full text-xs bg-gray-50 border border-outline-variant rounded-lg p-2.5 focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary font-medium" type="date"/>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="toggleModal('editMedicationModal')" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-lg transition-colors">
                    Annuler
                </button>
                <button type="submit" class="px-5 py-2 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-lg shadow-sm transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    <span>Mettre à jour</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        } else {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    }

    function openEditMedicationModal(med) {
        document.getElementById('editCode').value = med.code;
        document.getElementById('editName').value = med.name;
        document.getElementById('editDosage').value = med.dosage;
        document.getElementById('editForm').value = med.form;
        document.getElementById('editCategoryId').value = med.category_id;
        document.getElementById('editUnitPrice').value = med.unit_price;
        document.getElementById('editMinThreshold').value = med.min_threshold;
        
        if (med.expiration_date) {
            document.getElementById('editExpirationDate').value = med.expiration_date.split('T')[0];
        } else {
            document.getElementById('editExpirationDate').value = '';
        }

        document.getElementById('editMedicationForm').action = "/medications/" + med.id;
        toggleModal('editMedicationModal');
    }
</script>
@endsection
