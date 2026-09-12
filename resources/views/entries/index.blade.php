@extends('layouts.app')

@section('title', 'PharmaGestion - Entrées & Marges Prévisionnelles')
@section('page-title', 'Réceptions de Stock & Calcul des Marges')

@section('content')
<!-- En-tête de la page -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
    <div>
        <div class="flex items-center gap-2">
            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-primary/10 text-primary border border-primary/20">
                Espace Administrateur
            </span>
            <span class="text-xs text-gray-400">•</span>
            <span class="text-xs text-gray-500 font-medium">Poste de Santé</span>
        </div>
        <h2 class="text-2xl font-black text-gray-900 mt-1">Réceptions de Stock & Marges Prévisionnelles</h2>
        <p class="text-xs text-gray-500 mt-0.5">
            Enregistrez les livraisons du District / PNA avec leurs prix d'achat et anticipez immédiatement la marge bénéficiaire brute sur chaque entrée.
        </p>
    </div>
    <div class="flex items-center gap-2">
        <button onclick="toggleEntryForm()" class="bg-primary hover:bg-surface-tint text-white px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-2 shadow-sm transition-all cursor-pointer">
            <span class="material-symbols-outlined text-[18px]">add_circle</span>
            <span>Nouvelle Réception (Bordereau)</span>
        </button>
    </div>
</div>

<!-- 4 Cartes d'Indicateurs Financiers pour l'Administrateur -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-surface-container-lowest border border-outline-variant/80 p-4 rounded-xl shadow-xs">
        <div class="flex items-center justify-between">
            <span class="text-[11px] uppercase font-bold text-gray-400 tracking-wider">Total Investi en Achats</span>
            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center">
                <span class="material-symbols-outlined text-[20px]">shopping_cart</span>
            </div>
        </div>
        <h3 class="text-2xl font-black text-gray-900 mt-2">{{ number_format($totalPurchases, 0, ',', ' ') }} <span class="text-xs font-bold text-gray-500">FCFA</span></h3>
        <p class="text-xs text-gray-400 mt-0.5">Prix de cession total facturé</p>
    </div>

    <div class="bg-surface-container-lowest border border-outline-variant/80 p-4 rounded-xl shadow-xs">
        <div class="flex items-center justify-between">
            <span class="text-[11px] uppercase font-bold text-gray-400 tracking-wider">Valeur de Vente Attendue</span>
            <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-700 flex items-center justify-center">
                <span class="material-symbols-outlined text-[20px]">point_of_sale</span>
            </div>
        </div>
        <h3 class="text-2xl font-black text-primary mt-2">{{ number_format($totalExpectedSales, 0, ',', ' ') }} <span class="text-xs font-bold text-gray-500">FCFA</span></h3>
        <p class="text-xs text-gray-400 mt-0.5">Recette totale estimée au guichet</p>
    </div>

    <div class="bg-surface-container-lowest border border-outline-variant/80 p-4 rounded-xl shadow-xs">
        <div class="flex items-center justify-between">
            <span class="text-[11px] uppercase font-bold text-gray-400 tracking-wider">Marge Brute Prévisionnelle</span>
            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center">
                <span class="material-symbols-outlined text-[20px]">trending_up</span>
            </div>
        </div>
        <h3 class="text-2xl font-black {{ $totalProjectedMargin >= 0 ? 'text-emerald-700' : 'text-red-600' }} mt-2">
            {{ $totalProjectedMargin >= 0 ? '+' : '' }}{{ number_format($totalProjectedMargin, 0, ',', ' ') }} <span class="text-xs font-bold text-gray-500">FCFA</span>
        </h3>
        <p class="text-xs text-emerald-700 font-semibold mt-0.5">
            Rentabilité prévisionnelle : {{ $averageMarginPercentage }}%
        </p>
    </div>

    <div class="bg-surface-container-lowest border border-outline-variant/80 p-4 rounded-xl shadow-xs">
        <div class="flex items-center justify-between">
            <span class="text-[11px] uppercase font-bold text-gray-400 tracking-wider">Lignes Réceptionnées</span>
            <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-700 flex items-center justify-center">
                <span class="material-symbols-outlined text-[20px]">inventory_2</span>
            </div>
        </div>
        <h3 class="text-2xl font-black text-gray-900 mt-2">{{ number_format(count($entries)) }}</h3>
        <p class="text-xs text-gray-400 mt-0.5">Mouvements d'approvisionnement</p>
    </div>
</div>

<!-- Formulaire Nouvelle Réception / Bordereau de Livraison -->
<div id="newEntryForm" class="{{ request('show_form') ? '' : 'hidden' }} mb-6 bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 shadow-sm">
    <div class="flex items-center justify-between pb-4 mb-5 border-b border-gray-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-bold">
                <span class="material-symbols-outlined">receipt_long</span>
            </div>
            <div>
                <h3 class="text-lg font-black text-gray-900">Enregistrer une Réception de Médicaments (Bordereau)</h3>
                <p class="text-xs text-gray-500">Renseignez les éléments du bon de commande/livraison du District ou fournisseur.</p>
            </div>
        </div>
        <button type="button" onclick="toggleEntryForm()" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100 transition-colors">
            <span class="material-symbols-outlined text-[20px]">close</span>
        </button>
    </div>

    <form action="{{ route('entries.store') }}" method="POST" id="entryStoreForm" class="space-y-6">
        @csrf

        <!-- Section 1: Informations du Bordereau / Commande -->
        <div class="bg-gray-50/70 p-4 rounded-xl border border-gray-200/80">
            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-3 flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[16px]">description</span>
                <span>Informations du Bon de Commande / Livraison</span>
            </h4>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Date d'Entrée / Date Commande <span class="text-red-500">*</span></label>
                    <input 
                        type="date" 
                        name="movement_date" 
                        value="{{ old('movement_date', date('Y-m-d')) }}" 
                        required 
                        class="w-full text-xs bg-white border border-outline-variant rounded-lg px-3 py-2 focus:ring-1 focus:ring-primary focus:border-primary font-medium"
                    />
                    <p class="text-[10px] text-gray-400 mt-0.5">Date figurant sur la fiche de livraison</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">N° Commande / Réf. Bordereau</label>
                    <input 
                        type="text" 
                        name="reference_no" 
                        value="{{ old('reference_no') }}" 
                        placeholder="ex: TH08J2609CC00021" 
                        class="w-full text-xs bg-white border border-outline-variant rounded-lg px-3 py-2 focus:ring-1 focus:ring-primary focus:border-primary uppercase font-bold tracking-wide text-gray-800"
                    />
                    <p class="text-[10px] text-gray-400 mt-0.5">Numéro officiel du document du District</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Fournisseur / Origine</label>
                    <input 
                        type="text" 
                        name="supplier" 
                        value="{{ old('supplier', 'District THIES') }}" 
                        placeholder="ex: District THIES / PNA" 
                        class="w-full text-xs bg-white border border-outline-variant rounded-lg px-3 py-2 focus:ring-1 focus:ring-primary focus:border-primary font-medium"
                    />
                    <p class="text-[10px] text-gray-400 mt-0.5">Ex: District THIES, PNA, Donateur...</p>
                </div>
            </div>
        </div>

        <!-- Section 2: Médicament & Quantité Reçue -->
        <div>
            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-3 flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[16px]">medical_services</span>
                <span>Produit & Quantité Reçue</span>
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <!-- Choix du médicament -->
                <div class="md:col-span-6">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Médicament à Réceptionner <span class="text-red-500">*</span></label>
                    <select 
                        id="medicationSelect" 
                        name="medication_id" 
                        required 
                        onchange="onMedicationChange()"
                        class="w-full text-xs bg-white border border-outline-variant rounded-lg px-3 py-2 focus:ring-1 focus:ring-primary focus:border-primary font-medium"
                    >
                        <option value="">-- Sélectionnez un médicament --</option>
                        @foreach($medications as $med)
                            <option 
                                value="{{ $med['id'] }}" 
                                data-code="{{ $med['code'] }}" 
                                data-name="{{ $med['name'] }}" 
                                data-dosage="{{ $med['dosage'] }}" 
                                data-form="{{ $med['form'] }}" 
                                data-uc="{{ $med['packaging_unit'] }}" 
                                data-stock="{{ $med['stock_quantity'] }}" 
                                data-purchase="{{ $med['purchase_price'] }}" 
                                data-price="{{ $med['unit_price'] }}"
                                {{ old('medication_id') == $med['id'] ? 'selected' : '' }}
                            >
                                [{{ $med['code'] }}] {{ $med['name'] }} ({{ $med['dosage'] }} - {{ $med['form'] }}) - Stock act.: {{ $med['stock_quantity'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Unité de conditionnement (UC) -->
                <div class="md:col-span-3">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Unité Conditionnement (UC)</label>
                    <input 
                        type="text" 
                        id="packagingUnitInput" 
                        name="packaging_unit" 
                        value="{{ old('packaging_unit') }}" 
                        placeholder="ex: FL/500, B/100, T/30" 
                        class="w-full text-xs bg-white border border-outline-variant rounded-lg px-3 py-2 focus:ring-1 focus:ring-primary focus:border-primary uppercase font-medium"
                    />
                    <p class="text-[10px] text-gray-400 mt-0.5">Format UC sur le bon de livraison</p>
                </div>

                <!-- Quantité reçue -->
                <div class="md:col-span-3">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Quantité Reçue (QTE) <span class="text-red-500">*</span></label>
                    <input 
                        type="number" 
                        id="quantityInput" 
                        name="quantity" 
                        value="{{ old('quantity', 100) }}" 
                        min="1" 
                        step="1" 
                        required 
                        oninput="calculateMarginPreview()" 
                        placeholder="ex: 150" 
                        class="w-full text-xs bg-white border border-outline-variant rounded-lg px-3 py-2 focus:ring-1 focus:ring-primary focus:border-primary font-bold text-gray-900"
                    />
                    <p class="text-[10px] text-gray-400 mt-0.5">Nombre d'unités ou boîtes livrées</p>
                </div>
            </div>
        </div>

        <!-- Section 3: Tarification & Calculateur de Marge -->
        <div>
            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-3 flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[16px]">price_change</span>
                <span>Tarification & Fixation du Prix de Vente</span>
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Prix d'achat unitaire -->
                <div class="bg-blue-50/50 p-3.5 rounded-xl border border-blue-200">
                    <label class="block text-xs font-bold text-blue-950 mb-1">
                        Prix d'Achat Unitaire (Prix Cession District) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="number" 
                            id="purchasePriceInput" 
                            name="purchase_price" 
                            value="{{ old('purchase_price', 0) }}" 
                            min="0" 
                            step="1" 
                            required 
                            oninput="calculateMarginPreview()" 
                            placeholder="ex: 690" 
                            class="w-full text-sm bg-white border border-blue-300 rounded-lg px-3 py-2 pr-16 focus:ring-2 focus:ring-blue-400 font-black text-gray-900"
                        />
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-blue-700">FCFA</span>
                    </div>
                    <p class="text-[10px] text-blue-700/80 mt-1">Prix unitaire facturé par le District sur le bordereau</p>
                </div>

                <!-- Prix de vente unitaire au public -->
                <div class="bg-emerald-50/50 p-3.5 rounded-xl border border-emerald-200">
                    <label class="block text-xs font-bold text-emerald-950 mb-1">
                        Prix de Vente Fixé au Public (Patients) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="number" 
                            id="sellingPriceInput" 
                            name="selling_price" 
                            value="{{ old('selling_price', 0) }}" 
                            min="0" 
                            step="1" 
                            required 
                            oninput="calculateMarginPreview()" 
                            placeholder="ex: 850" 
                            class="w-full text-sm bg-white border border-emerald-300 rounded-lg px-3 py-2 pr-16 focus:ring-2 focus:ring-emerald-400 font-black text-emerald-900"
                        />
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-emerald-700">FCFA</span>
                    </div>
                    <p class="text-[10px] text-emerald-700/80 mt-1">Prix appliqué lors de la vente au guichet du poste</p>
                </div>
            </div>
        </div>

        <!-- Section 4: Encart Interactif de Calcul Prévisionnel de la Marge (Temps Réel) -->
        <div id="marginPreviewBox" class="bg-gradient-to-br from-emerald-50 via-teal-50/40 to-blue-50/30 p-5 rounded-2xl border border-emerald-300/80 shadow-xs">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 mb-3 border-b border-emerald-200/60">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-emerald-700">analytics</span>
                    <span class="text-xs font-extrabold text-emerald-950 uppercase tracking-wider">
                        Prévision de Rentabilité pour l'Administrateur
                    </span>
                </div>
                <div id="marginStatusBadge" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                    Marge Positive
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center sm:text-left">
                <!-- Total Achat -->
                <div class="bg-white/80 p-3 rounded-xl border border-emerald-200/50">
                    <span class="text-[10px] uppercase font-bold text-gray-500">Coût Total Achat</span>
                    <p id="previewTotalPurchase" class="text-lg font-black text-gray-900 mt-0.5">0 FCFA</p>
                    <span class="text-[10px] text-gray-400">Investissement</span>
                </div>

                <!-- Total Vente Attendue -->
                <div class="bg-white/80 p-3 rounded-xl border border-emerald-200/50">
                    <span class="text-[10px] uppercase font-bold text-gray-500">Recette Totale Attendue</span>
                    <p id="previewTotalSelling" class="text-lg font-black text-primary mt-0.5">0 FCFA</p>
                    <span class="text-[10px] text-gray-400">Chiffre d'affaires prévu</span>
                </div>

                <!-- Marge Unitaire -->
                <div class="bg-white/80 p-3 rounded-xl border border-emerald-200/50">
                    <span class="text-[10px] uppercase font-bold text-gray-500">Marge par Unité</span>
                    <p id="previewUnitMargin" class="text-lg font-black text-emerald-700 mt-0.5">+0 FCFA</p>
                    <span id="previewMarginPercentage" class="text-[10px] text-emerald-700 font-bold">0% de marge</span>
                </div>

                <!-- Bénéfice Brut Total -->
                <div class="bg-emerald-600 text-white p-3 rounded-xl shadow-xs">
                    <span class="text-[10px] uppercase font-bold text-emerald-100">Bénéfice Prévisionnel Total</span>
                    <p id="previewTotalMargin" class="text-xl font-black mt-0.5">+0 FCFA</p>
                    <span class="text-[10px] text-emerald-100">Gain net estimé pour le poste</span>
                </div>
            </div>

            <div id="marginWarningAlert" class="hidden mt-3 p-2.5 rounded-lg bg-red-100 border border-red-200 text-red-800 text-xs font-semibold flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">warning</span>
                <span>Attention : Le prix de vente est inférieur ou égal au prix d'achat. La marge sera négative ou nulle !</span>
            </div>
        </div>

        <!-- Section 5: Notes & Validation -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 pt-2">
            <div class="flex-1">
                <input 
                    type="text" 
                    name="notes" 
                    value="{{ old('notes') }}" 
                    placeholder="Remarques / Observations éventuelles (optionnel)..." 
                    class="w-full text-xs bg-white border border-outline-variant rounded-lg px-3 py-2 font-medium"
                />
            </div>
            <div class="flex items-center gap-3">
                <button type="button" onclick="toggleEntryForm()" class="px-4 py-2.5 rounded-xl border border-outline-variant text-gray-600 hover:bg-gray-100 text-xs font-bold transition-colors">
                    Annuler
                </button>
                <button type="submit" class="bg-primary hover:bg-surface-tint text-white px-6 py-2.5 rounded-xl font-bold text-xs shadow-md transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">check_circle</span>
                    <span>Enregistrer la Réception & Actualiser le Stock</span>
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Barre de Recherche et Filtres -->
<div class="bg-surface-container-lowest p-4 rounded-xl border border-outline-variant/80 shadow-xs mb-6">
    <form action="{{ route('entries.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
        <!-- Recherche Texte -->
        <div class="md:col-span-2">
            <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Recherche</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">search</span>
                <input 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Médicament, N° Commande, Fournisseur..." 
                    class="w-full pl-9 pr-3 py-2 text-xs bg-gray-50 border border-outline-variant rounded-lg focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary font-medium" 
                    type="text"
                />
            </div>
        </div>

        <!-- Date Début -->
        <div>
            <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Depuis le</label>
            <input 
                type="date" 
                name="date_from" 
                value="{{ request('date_from') }}" 
                class="w-full text-xs bg-gray-50 border border-outline-variant rounded-lg p-2 focus:bg-white font-medium"
            />
        </div>

        <!-- Date Fin -->
        <div>
            <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Jusqu'au</label>
            <input 
                type="date" 
                name="date_to" 
                value="{{ request('date_to') }}" 
                class="w-full text-xs bg-gray-50 border border-outline-variant rounded-lg p-2 focus:bg-white font-medium"
            />
        </div>

        <!-- Boutons d'Action -->
        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 bg-primary hover:bg-surface-tint text-white text-xs font-bold py-2 px-3 rounded-lg shadow-xs transition-colors flex items-center justify-center gap-1">
                <span class="material-symbols-outlined text-[16px]">filter_list</span>
                <span>Filtrer</span>
            </button>
            <a href="{{ route('entries.index') }}" class="p-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg transition-colors" title="Réinitialiser">
                <span class="material-symbols-outlined text-[16px]">refresh</span>
            </a>
        </div>
    </form>
</div>

<!-- Table des Entrées & Marges Prévisionnelles -->
<div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/80 shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm border-collapse">
            <thead>
                <tr class="bg-gray-50/80 text-[11px] font-bold text-gray-500 uppercase border-b border-gray-100">
                    <th class="py-3.5 px-4">Date Entrée</th>
                    <th class="py-3.5 px-4">N° Commande / Réf</th>
                    <th class="py-3.5 px-4">Médicament & Conditionnement</th>
                    <th class="py-3.5 px-4 text-right">Quantité</th>
                    <th class="py-3.5 px-4 text-right">Prix Achat</th>
                    <th class="py-3.5 px-4 text-right">Prix Vente</th>
                    <th class="py-3.5 px-4 text-right">Marge Prévue (Bénéfice)</th>
                    <th class="py-3.5 px-4 text-left">Origine / Opérateur</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($entries as $entry)
                    <tr class="hover:bg-gray-50/60 transition-colors">
                        <!-- Date de Réception -->
                        <td class="py-3 px-4 font-semibold text-gray-900 whitespace-nowrap text-xs">
                            {{ $entry->movement_date ? $entry->movement_date->format('d/m/Y') : $entry->created_at->format('d/m/Y') }}
                            <div class="text-[10px] text-gray-400 font-normal">à {{ $entry->created_at->format('H:i') }}</div>
                        </td>

                        <!-- Référence Commande / Bordereau -->
                        <td class="py-3 px-4 whitespace-nowrap">
                            @if($entry->reference_no)
                                <span class="px-2 py-0.5 rounded-md font-mono text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                    {{ $entry->reference_no }}
                                </span>
                            @else
                                <span class="text-gray-400 text-xs italic">Sans réf.</span>
                            @endif
                        </td>

                        <!-- Médicament & Forme & UC -->
                        <td class="py-3 px-4">
                            <div class="font-bold text-gray-900 text-xs">
                                {{ $entry->medication->name ?? 'Médicament' }}
                            </div>
                            <div class="text-[11px] text-gray-500 flex items-center gap-1.5 mt-0.5">
                                <span>{{ $entry->medication->dosage ?? '' }} ({{ $entry->medication->form ?? '' }})</span>
                                @if($entry->packaging_unit || ($entry->medication && $entry->medication->packaging_unit))
                                    <span class="px-1.5 py-0.2 rounded bg-gray-100 text-gray-700 font-bold text-[10px] border border-gray-200">
                                        UC: {{ $entry->packaging_unit ?? $entry->medication->packaging_unit }}
                                    </span>
                                @endif
                            </div>
                        </td>

                        <!-- Quantité Ajoutée -->
                        <td class="py-3 px-4 text-right whitespace-nowrap">
                            <span class="px-2.5 py-1 rounded-full text-xs font-black bg-emerald-50 text-emerald-800 border border-emerald-200">
                                +{{ number_format($entry->quantity) }}
                            </span>
                        </td>

                        <!-- Prix Achat Unit. -->
                        <td class="py-3 px-4 text-right whitespace-nowrap text-xs font-bold text-blue-900">
                            {{ number_format($entry->effective_purchase_price, 0, ',', ' ') }} FCFA
                            @if($entry->quantity > 0)
                                <div class="text-[10px] text-gray-400 font-normal">
                                    Total: {{ number_format($entry->total_purchase, 0, ',', ' ') }} F
                                </div>
                            @endif
                        </td>

                        <!-- Prix Vente Unit. -->
                        <td class="py-3 px-4 text-right whitespace-nowrap text-xs font-bold text-gray-900">
                            {{ number_format($entry->effective_selling_price, 0, ',', ' ') }} FCFA
                            @if($entry->quantity > 0)
                                <div class="text-[10px] text-gray-400 font-normal">
                                    Total: {{ number_format($entry->total_selling, 0, ',', ' ') }} F
                                </div>
                            @endif
                        </td>

                        <!-- Marge Brute Prévue -->
                        <td class="py-3 px-4 text-right whitespace-nowrap">
                            @php
                                $totalMargin = $entry->total_margin;
                                $marginPct = $entry->margin_percentage;
                            @endphp
                            <div class="font-black text-xs {{ $totalMargin >= 0 ? 'text-emerald-700' : 'text-red-600' }}">
                                {{ $totalMargin >= 0 ? '+' : '' }}{{ number_format($totalMargin, 0, ',', ' ') }} FCFA
                            </div>
                            <div class="text-[10px] font-bold {{ $totalMargin >= 0 ? 'text-emerald-600' : 'text-red-500' }}">
                                +{{ number_format($entry->unit_margin, 0, ',', ' ') }} F/unité ({{ $marginPct }}%)
                            </div>
                        </td>

                        <!-- Fournisseur & Responsable -->
                        <td class="py-3 px-4 text-xs text-gray-600">
                            <div class="font-semibold text-gray-800">
                                {{ $entry->supplier ?? 'District Sanitaire' }}
                            </div>
                            <div class="text-[10px] text-gray-400 mt-0.5">
                                Par {{ $entry->performed_by_name ?? ($entry->user->name ?? 'Admin') }}
                                @if($entry->notes)
                                    <span title="{{ $entry->notes }}">• Obs.</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="p-8 text-center">
                            <div class="w-12 h-12 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center mx-auto mb-2">
                                <span class="material-symbols-outlined text-[24px]">inventory_2</span>
                            </div>
                            <p class="text-xs text-gray-500 font-medium">Aucune entrée de stock trouvée pour cette période.</p>
                            <button onclick="toggleEntryForm()" class="mt-3 text-xs font-bold text-primary hover:underline">
                                + Enregistrer une première réception
                            </button>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Script interactif de calcul en temps réel des marges -->
<script>
    // Liste des médicaments injectée pour l'autocomplétion
    const medicationsCatalog = @json($medications);

    function toggleEntryForm() {
        const form = document.getElementById('newEntryForm');
        form.classList.toggle('hidden');
        if (!form.classList.contains('hidden')) {
            form.scrollIntoView({ behavior: 'smooth' });
        }
    }

    function onMedicationChange() {
        const select = document.getElementById('medicationSelect');
        const selectedId = select.value;
        if (!selectedId) return;

        const med = medicationsCatalog.find(m => m.id == selectedId);
        if (med) {
            // Autocomplétion UC
            const ucInput = document.getElementById('packagingUnitInput');
            if (med.packaging_unit && ucInput) {
                ucInput.value = med.packaging_unit;
            }

            // Autocomplétion Prix Achat
            const buyInput = document.getElementById('purchasePriceInput');
            if (buyInput) {
                buyInput.value = med.purchase_price > 0 ? med.purchase_price : '';
            }

            // Autocomplétion Prix Vente
            const sellInput = document.getElementById('sellingPriceInput');
            if (sellInput) {
                sellInput.value = med.unit_price > 0 ? med.unit_price : '';
            }

            calculateMarginPreview();
        }
    }

    function calculateMarginPreview() {
        const qty = parseFloat(document.getElementById('quantityInput').value) || 0;
        const buyPrice = parseFloat(document.getElementById('purchasePriceInput').value) || 0;
        const sellPrice = parseFloat(document.getElementById('sellingPriceInput').value) || 0;

        const totalPurchase = qty * buyPrice;
        const totalSelling = qty * sellPrice;
        const unitMargin = sellPrice - buyPrice;
        const totalMargin = totalSelling - totalPurchase;
        const marginPct = (buyPrice > 0) ? Math.round(((sellPrice - buyPrice) / buyPrice) * 1000) / 10 : 0;

        // Mise à jour de l'affichage
        document.getElementById('previewTotalPurchase').innerText = formatFCFA(totalPurchase);
        document.getElementById('previewTotalSelling').innerText = formatFCFA(totalSelling);
        document.getElementById('previewUnitMargin').innerText = (unitMargin >= 0 ? '+' : '') + formatFCFA(unitMargin);
        document.getElementById('previewMarginPercentage').innerText = (marginPct >= 0 ? '+' : '') + marginPct + '% de marge';
        document.getElementById('previewTotalMargin').innerText = (totalMargin >= 0 ? '+' : '') + formatFCFA(totalMargin);

        // Alertes et badges
        const badge = document.getElementById('marginStatusBadge');
        const warning = document.getElementById('marginWarningAlert');

        if (sellPrice > buyPrice && buyPrice > 0) {
            badge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800';
            badge.innerText = 'Marge Positive (+' + marginPct + '%)';
            warning.classList.add('hidden');
        } else if (sellPrice === buyPrice && buyPrice > 0) {
            badge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800';
            badge.innerText = 'Marge Nulle (0%)';
            warning.classList.remove('hidden');
        } else if (sellPrice < buyPrice) {
            badge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800';
            badge.innerText = 'Marge Négative (' + marginPct + '%)';
            warning.classList.remove('hidden');
        } else {
            badge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-700';
            badge.innerText = 'En attente de saisie';
            warning.classList.add('hidden');
        }
    }

    function formatFCFA(num) {
        return new Intl.NumberFormat('fr-FR').format(Math.round(num)) + ' FCFA';
    }

    // Calcul initial au chargement
    document.addEventListener('DOMContentLoaded', function() {
        calculateMarginPreview();
    });
</script>
@endsection
