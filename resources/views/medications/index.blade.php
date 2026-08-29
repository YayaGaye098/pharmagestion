@extends('layouts.app')

@section('title', 'PharmaGestion - Inventaire des Médicaments')
@section('page-title', 'Gestion des Médicaments')

@section('content')
<!-- Page Header & Actions -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-lg gap-md">
    <div>
        <h2 class="font-headline-md text-headline-md text-on-surface font-bold">Inventaire des Médicaments</h2>
        <p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">Gérez et consultez votre stock actuel.</p>
    </div>
    <button onclick="toggleModal('addMedicationModal')" class="bg-primary text-on-primary px-lg py-sm rounded-lg font-label-md text-label-md flex items-center gap-sm hover:opacity-90 transition-opacity whitespace-nowrap self-stretch md:self-auto justify-center shadow-sm font-bold cursor-pointer">
        <span class="material-symbols-outlined text-[18px]">add</span>
        Ajouter un médicament
    </button>
</div>

<!-- Search & Filters Bar -->
<form action="{{ route('medications.index') }}" method="GET" class="bg-surface-container-lowest p-md rounded-xl border border-outline-variant shadow-sm mb-lg flex flex-col md:flex-row gap-md items-center">
    <!-- Search Input -->
    <div class="relative w-full md:w-96 flex-shrink-0">
        <div class="absolute inset-y-0 left-0 pl-sm flex items-center pointer-events-none">
            <span class="material-symbols-outlined text-on-surface-variant">search</span>
        </div>
        <input name="search" value="{{ request('search') }}" class="block w-full pl-xl pr-sm py-sm border border-outline-variant rounded-lg leading-5 bg-surface-container-lowest placeholder-on-surface-variant focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary font-body-sm text-body-sm" placeholder="Rechercher par nom ou code..." type="text"/>
    </div>

    <!-- Filters -->
    <div class="flex flex-wrap items-center gap-sm w-full">
        <div class="flex items-center gap-xs">
            <span class="font-label-md text-label-md text-on-surface-variant mr-xs">Catégorie:</span>
            <select name="category" onchange="this.form.submit()" class="border border-outline-variant rounded-lg px-sm py-[6px] bg-surface-container-lowest font-body-sm text-body-sm text-on-surface focus:ring-1 focus:ring-primary focus:border-primary">
                <option value="Toutes">Toutes</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->name }}" {{ request('category') == $cat->name ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center gap-xs">
            <span class="font-label-md text-label-md text-on-surface-variant mr-xs">Forme:</span>
            <select name="form" onchange="this.form.submit()" class="border border-outline-variant rounded-lg px-sm py-[6px] bg-surface-container-lowest font-body-sm text-body-sm text-on-surface focus:ring-1 focus:ring-primary focus:border-primary">
                <option value="Toutes">Toutes</option>
                @foreach($forms as $f)
                    <option value="{{ $f }}" {{ request('form') == $f ? 'selected' : '' }}>{{ $f }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center gap-xs ml-auto">
            <a href="{{ route('medications.index') }}" class="flex items-center gap-xs border border-outline-variant rounded-lg px-sm py-[6px] hover:bg-surface-container-low transition-colors text-on-surface font-label-md text-label-md">
                <span class="material-symbols-outlined text-[16px]">refresh</span>
                Réinitialiser
            </a>
        </div>
    </div>
</form>

<!-- Data Table (Desktop View) -->
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden hidden md:block">
    <table class="min-w-full divide-y divide-outline-variant">
        <thead class="bg-surface">
            <tr>
                <th class="px-md py-sm text-left font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Nom</th>
                <th class="px-md py-sm text-left font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Dosage</th>
                <th class="px-md py-sm text-left font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Forme</th>
                <th class="px-md py-sm text-left font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Catégorie</th>
                <th class="px-md py-sm text-right font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Stock</th>
                <th class="px-md py-sm text-right font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Prix (FCFA)</th>
                <th class="px-md py-sm text-center font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Statut</th>
                <th class="relative px-md py-sm"><span class="sr-only">Actions</span></th>
            </tr>
        </thead>
        <tbody class="bg-surface-container-lowest divide-y divide-outline-variant">
            @forelse($medications as $med)
                <tr class="hover:bg-surface-container-lowest/50 transition-colors">
                    <td class="px-md py-sm whitespace-nowrap">
                        <div class="font-body-md text-body-md font-semibold text-on-surface">{{ $med->name }}</div>
                        <div class="font-body-sm text-body-sm text-on-surface-variant">CODE: {{ $med->code }}</div>
                    </td>
                    <td class="px-md py-sm whitespace-nowrap font-body-sm text-body-sm text-on-surface">{{ $med->dosage }}</td>
                    <td class="px-md py-sm whitespace-nowrap font-body-sm text-body-sm text-on-surface">{{ $med->form }}</td>
                    <td class="px-md py-sm whitespace-nowrap font-body-sm text-body-sm text-on-surface">{{ $med->category->name ?? '-' }}</td>
                    <td class="px-md py-sm whitespace-nowrap text-right font-body-sm text-body-sm {{ $med->stock_quantity <= 0 ? 'text-error font-bold' : ($med->stock_quantity <= $med->min_threshold ? 'text-tertiary font-bold' : 'text-on-surface') }}">
                        {{ number_format($med->stock_quantity) }}
                    </td>
                    <td class="px-md py-sm whitespace-nowrap text-right font-currency-md text-currency-md text-on-surface font-bold">
                        {{ number_format($med->unit_price, 0, ',', ' ') }}
                    </td>
                    <td class="px-md py-sm whitespace-nowrap text-center">
                        @if($med->stock_quantity <= 0)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full font-label-md text-[10px] bg-error-container text-on-error-container font-bold">
                                Rupture
                            </span>
                        @elseif($med->stock_quantity <= $med->min_threshold)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full font-label-md text-[10px] bg-tertiary-fixed text-on-tertiary-fixed-variant font-bold">
                                Faible
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full font-label-md text-[10px] bg-surface-container-high text-primary-container font-bold">
                                Stock OK
                            </span>
                        @endif
                    </td>
                    <td class="px-md py-sm whitespace-nowrap text-right text-sm font-medium">
                        <button class="text-on-surface-variant hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[20px]">more_vert</span>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-md py-lg text-center text-on-surface-variant">
                        Aucun médicament trouvé.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Mobile View Cards (Hidden on Desktop) -->
<div class="md:hidden space-y-sm">
    @forelse($medications as $med)
        <div class="bg-surface-container-lowest p-md rounded-xl border border-outline-variant shadow-sm flex flex-col gap-sm">
            <div class="flex justify-between items-start">
                <div>
                    <div class="font-body-md text-body-md font-semibold text-on-surface">{{ $med->name }} <span class="font-normal text-on-surface-variant">- {{ $med->dosage }}</span></div>
                    <div class="font-body-sm text-body-sm text-on-surface-variant">{{ $med->form }} • {{ $med->category->name ?? '' }}</div>
                </div>
                @if($med->stock_quantity <= 0)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full font-label-md text-[10px] bg-error-container text-on-error-container font-bold">Rupture</span>
                @elseif($med->stock_quantity <= $med->min_threshold)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full font-label-md text-[10px] bg-tertiary-fixed text-on-tertiary-fixed-variant font-bold">Faible</span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full font-label-md text-[10px] bg-surface-container-high text-primary-container font-bold">Stock OK</span>
                @endif
            </div>
            <div class="flex justify-between items-end border-t border-outline-variant pt-sm mt-xs">
                <div>
                    <div class="font-label-md text-label-md text-on-surface-variant">Stock Restant</div>
                    <div class="font-currency-md text-currency-md font-bold {{ $med->stock_quantity <= 0 ? 'text-error' : 'text-on-surface' }}">{{ number_format($med->stock_quantity) }}</div>
                </div>
                <div>
                    <div class="font-label-md text-label-md text-on-surface-variant text-right">Prix</div>
                    <div class="font-currency-md text-currency-md text-on-surface font-bold text-right">{{ number_format($med->unit_price, 0, ',', ' ') }} FCFA</div>
                </div>
            </div>
        </div>
    @empty
        <div class="p-md text-center text-on-surface-variant">Aucun médicament trouvé.</div>
    @endforelse
</div>

<!-- Modal Ajouter un médicament -->
<div id="addMedicationModal" class="fixed inset-0 bg-on-surface/40 backdrop-blur-sm hidden items-center justify-center z-50 p-md">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-lg w-full max-w-lg overflow-hidden flex flex-col">
        <div class="px-lg py-md border-b border-outline-variant flex justify-between items-center">
            <h3 class="font-headline-sm text-headline-sm font-bold text-on-surface">Nouveau Médicament</h3>
            <button onclick="toggleModal('addMedicationModal')" class="text-on-surface-variant hover:text-on-surface">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ route('medications.store') }}" method="POST" class="p-lg space-y-md">
            @csrf
            <div class="grid grid-cols-2 gap-md">
                <div>
                    <label class="font-label-md text-label-md text-on-surface">Code Identifiant</label>
                    <input name="code" placeholder="ex: PAR-002" required class="w-full border border-outline-variant rounded px-md py-sm font-body-sm text-body-sm" type="text"/>
                </div>
                <div>
                    <label class="font-label-md text-label-md text-on-surface">Nom Commercial</label>
                    <input name="name" placeholder="ex: Paracétamol" required class="w-full border border-outline-variant rounded px-md py-sm font-body-sm text-body-sm" type="text"/>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-md">
                <div>
                    <label class="font-label-md text-label-md text-on-surface">Dosage</label>
                    <input name="dosage" placeholder="ex: 500mg" required class="w-full border border-outline-variant rounded px-md py-sm font-body-sm text-body-sm" type="text"/>
                </div>
                <div>
                    <label class="font-label-md text-label-md text-on-surface">Forme Galénique</label>
                    <input name="form" placeholder="ex: Comprimé" required class="w-full border border-outline-variant rounded px-md py-sm font-body-sm text-body-sm" type="text"/>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-md">
                <div>
                    <label class="font-label-md text-label-md text-on-surface">Catégorie</label>
                    <select name="category_id" required class="w-full border border-outline-variant rounded px-md py-sm font-body-sm text-body-sm">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="font-label-md text-label-md text-on-surface">Prix Unitaire (FCFA)</label>
                    <input name="unit_price" placeholder="500" required min="0" step="10" class="w-full border border-outline-variant rounded px-md py-sm font-body-sm text-body-sm" type="number"/>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-md">
                <div>
                    <label class="font-label-md text-label-md text-on-surface">Quantité Initiale</label>
                    <input name="stock_quantity" value="100" required min="0" class="w-full border border-outline-variant rounded px-md py-sm font-body-sm text-body-sm" type="number"/>
                </div>
                <div>
                    <label class="font-label-md text-label-md text-on-surface">Seuil d'Alerte</label>
                    <input name="min_threshold" value="20" required min="1" class="w-full border border-outline-variant rounded px-md py-sm font-body-sm text-body-sm" type="number"/>
                </div>
            </div>

            <div class="flex justify-end gap-sm pt-md border-t border-outline-variant">
                <button type="button" onclick="toggleModal('addMedicationModal')" class="px-lg py-sm rounded border border-outline-variant text-on-surface font-label-md text-label-md">
                    Annuler
                </button>
                <button type="submit" class="px-lg py-sm rounded bg-primary text-on-primary font-label-md text-label-md font-bold shadow-sm">
                    Enregistrer
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
</script>
@endsection
