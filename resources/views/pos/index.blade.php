<!DOCTYPE html>
<html class="light" lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Guichet de Vente - PharmaGestion</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="{{ asset('js/tailwindcss.js') }}"></script>

    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#006565",
                        "primary-hover": "#004f4f",
                        "primary-container": "#008080",
                        "on-primary-container": "#e3fffe",
                        secondary: "#0059bb",
                        "secondary-container": "#d8e2ff",
                        surface: "#f8f9ff",
                        "surface-lowest": "#ffffff",
                        "surface-low": "#eff4ff",
                        "surface-container": "#e5eeff",
                        "on-surface": "#0b1c30",
                        "on-surface-variant": "#4a5568",
                        "outline-variant": "#cbd5e1",
                        error: "#ba1a1a",
                        "error-container": "#ffdad6"
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .icon-filled {
            font-variation-settings: 'FILL' 1;
        }
        @media print {
            body * {
                visibility: hidden;
            }
            #printTicketFrame, #printTicketFrame * {
                visibility: visible;
            }
            #printTicketFrame {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body class="bg-[#f0f4f8] text-on-surface font-sans h-screen flex flex-col overflow-hidden select-none">

    <!-- Top Bar Guichet -->
    <header class="bg-surface-lowest border-b border-outline-variant h-16 flex items-center justify-between px-6 z-30 shrink-0 shadow-sm">
        <div class="flex items-center gap-4">
            <a href="{{ auth()->user()->isAdmin() ? route('dashboard') : route('vendor.dashboard') }}" class="w-10 h-10 rounded-xl bg-primary text-white flex items-center justify-center shadow hover:opacity-90 transition-opacity" title="Retour au Dashboard">
                <span class="material-symbols-outlined icon-filled">local_pharmacy</span>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="font-bold text-lg text-primary">Guichet de Vente</h1>
                    <span class="bg-primary/10 text-primary text-xs font-bold px-2 py-0.5 rounded-full uppercase">Caisse N°1</span>
                </div>
                <p class="text-xs text-on-surface-variant">Poste de Santé & Dispensaire</p>
            </div>
        </div>

        <div class="flex items-center gap-6">
            <!-- Horloge en direct -->
            <div class="hidden sm:flex items-center gap-2 text-sm text-on-surface-variant bg-surface-low px-3 py-1.5 rounded-lg border border-outline-variant/60 font-medium">
                <span class="material-symbols-outlined text-primary text-[18px]">schedule</span>
                <span id="liveClock">--:--:--</span>
            </div>

            <!-- Vendeuse connectée -->
            <div class="flex items-center gap-3 pl-4 border-l border-outline-variant">
                <div class="w-9 h-9 rounded-full bg-primary-container text-white flex items-center justify-center font-bold text-sm shadow-sm">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="text-left hidden md:block">
                    <p class="text-xs font-bold text-on-surface leading-tight">{{ auth()->user()->name }}</p>
                    <p class="text-[11px] text-on-surface-variant capitalize">{{ auth()->user()->role === 'vendor' ? 'Vendeuse' : auth()->user()->role }}</p>
                </div>
            </div>

            <!-- Liens d'action -->
            <div class="flex items-center gap-2">
                <a href="{{ auth()->user()->isAdmin() ? route('dashboard') : route('vendor.dashboard') }}" class="p-2 rounded-lg text-on-surface-variant hover:bg-surface-low hover:text-primary transition-colors" title="Tableau de bord">
                    <span class="material-symbols-outlined">dashboard</span>
                </a>
                <a href="{{ route('vendor.sales') }}" class="p-2 rounded-lg text-on-surface-variant hover:bg-surface-low hover:text-primary transition-colors" title="Mes Ventes">
                    <span class="material-symbols-outlined">history</span>
                </a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="p-2 rounded-lg text-error hover:bg-error-container/40 transition-colors" title="Déconnexion">
                        <span class="material-symbols-outlined">logout</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content Area (Split: Left Catalog, Right Cart) -->
    <div class="flex-1 flex overflow-hidden">

        <!-- ======================= LEFT: CATALOGUE DES MEDICAMENTS ======================= -->
        <section class="flex-1 flex flex-col min-w-0 border-r border-outline-variant bg-surface-low">
            
            <!-- Barre de Recherche & Filtres -->
            <div class="p-4 bg-surface-lowest border-b border-outline-variant shrink-0 space-y-3">
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                    <input 
                        type="text" 
                        id="medSearch" 
                        placeholder="Rechercher un médicament (nom, code, dosage, forme)... [F2]"
                        autocomplete="off"
                        autofocus
                        class="w-full pl-11 pr-10 py-2.5 bg-gray-50 border border-outline-variant rounded-xl text-sm focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all font-medium"
                    />
                    <button id="clearSearchBtn" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <span class="material-symbols-outlined text-[18px]">cancel</span>
                    </button>
                </div>

                <!-- Filtres par catégorie -->
                <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs scrollbar-none" id="categoryFilters">
                    <button 
                        data-cat="all" 
                        class="cat-filter-btn active px-3.5 py-1.5 rounded-full font-bold bg-primary text-white shadow-sm shrink-0 transition-all">
                        Tous (<span id="totalCount">0</span>)
                    </button>
                    @foreach($categories as $cat)
                        <button 
                            data-cat="{{ $cat->id }}" 
                            class="cat-filter-btn px-3.5 py-1.5 rounded-full font-semibold bg-surface-low text-on-surface-variant hover:bg-surface-container shrink-0 transition-all border border-outline-variant/60">
                            {{ $cat->name }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Grille des Médicaments -->
            <div class="flex-1 overflow-y-auto p-4" id="medicationsGridContainer">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3.5" id="medicationsGrid">
                    <!-- Généré dynamiquement par JavaScript -->
                </div>

                <!-- Empty State recherche -->
                <div id="noMedicationState" class="hidden h-64 flex flex-col items-center justify-center text-center text-on-surface-variant">
                    <span class="material-symbols-outlined text-4xl text-gray-300 mb-2">search_off</span>
                    <p class="font-bold text-base text-gray-600">Aucun médicament trouvé</p>
                    <p class="text-xs text-gray-400">Vérifiez l'orthographe ou changez de catégorie de recherche.</p>
                </div>
            </div>
        </section>

        <!-- ======================= RIGHT: PANIER & ENCAISSEMENT ======================= -->
        <aside class="w-[420px] lg:w-[460px] flex flex-col shrink-0 bg-surface-lowest shadow-lg">
            
            <!-- Entête Panier -->
            <div class="p-4 border-b border-outline-variant bg-surface flex items-center justify-between shrink-0">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary icon-filled">shopping_bag</span>
                    <h2 class="font-bold text-base text-on-surface">Panier de Vente</h2>
                    <span id="cartCountBadge" class="bg-primary text-white text-xs font-bold px-2 py-0.5 rounded-full">0</span>
                </div>
                <button id="clearCartBtn" class="text-xs font-semibold text-error hover:bg-error-container/50 px-2.5 py-1 rounded-lg transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">delete_sweep</span>
                    Vider
                </button>
            </div>

            <!-- Champ Patient / Ordonnance (Optionnel) -->
            <div class="px-4 py-2.5 bg-gray-50 border-b border-outline-variant shrink-0">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-gray-400 text-[18px]">person</span>
                    <input 
                        type="text" 
                        id="patientNameInput" 
                        placeholder="Nom du patient / N° Ordonnance (Optionnel)"
                        class="w-full bg-transparent border-0 p-0 text-xs text-gray-700 placeholder-gray-400 focus:ring-0 font-medium"
                    />
                </div>
            </div>

            <!-- Liste des articles dans le panier -->
            <div class="flex-1 overflow-y-auto p-4 space-y-2.5" id="cartItemsList">
                <!-- Panier Vide State -->
                <div id="emptyCartState" class="h-full flex flex-col items-center justify-center text-center text-on-surface-variant p-6">
                    <div class="w-16 h-16 rounded-full bg-surface-low flex items-center justify-center text-gray-300 mb-3">
                        <span class="material-symbols-outlined text-3xl">shopping_cart</span>
                    </div>
                    <p class="font-bold text-sm text-gray-600">Le panier est vide</p>
                    <p class="text-xs text-gray-400 mt-1 max-w-[220px]">Cliquez sur un médicament dans la liste de gauche pour l'ajouter au panier.</p>
                </div>
            </div>

            <!-- Zone de Paiement & Totaux -->
            <div class="p-4 border-t border-outline-variant bg-surface shrink-0 space-y-3.5 shadow-inner">
                
                <!-- Résumé Total -->
                <div class="bg-primary/5 border border-primary/20 rounded-xl p-3 flex items-center justify-between">
                    <span class="text-xs uppercase font-bold text-gray-600">Total à payer</span>
                    <div class="text-right">
                        <span id="cartTotalDisplay" class="text-2xl font-extrabold text-primary">0</span>
                        <span class="text-sm font-bold text-primary">FCFA</span>
                    </div>
                </div>

                <!-- Mode de Règlement -->
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1.5">Mode de Paiement</label>
                    <div class="grid grid-cols-4 gap-1.5" id="paymentMethodSelector">
                        <button type="button" data-method="espèces" class="pay-method-btn active py-2 px-1 rounded-lg border text-xs font-bold flex flex-col items-center gap-1 transition-all bg-primary text-white border-primary">
                            <span class="material-symbols-outlined text-[18px]">payments</span>
                            Espèces
                        </button>
                        <button type="button" data-method="wave" class="pay-method-btn py-2 px-1 rounded-lg border text-xs font-bold flex flex-col items-center gap-1 transition-all bg-surface-lowest text-gray-700 border-outline-variant hover:bg-surface-low">
                            <span class="material-symbols-outlined text-[18px] text-blue-500">smartphone</span>
                            Wave
                        </button>
                        <button type="button" data-method="orange_money" class="pay-method-btn py-2 px-1 rounded-lg border text-xs font-bold flex flex-col items-center gap-1 transition-all bg-surface-lowest text-gray-700 border-outline-variant hover:bg-surface-low">
                            <span class="material-symbols-outlined text-[18px] text-orange-500">contactless</span>
                            Orange M.
                        </button>
                        <button type="button" data-method="cmu" class="pay-method-btn py-2 px-1 rounded-lg border text-xs font-bold flex flex-col items-center gap-1 transition-all bg-surface-lowest text-gray-700 border-outline-variant hover:bg-surface-low">
                            <span class="material-symbols-outlined text-[18px] text-emerald-600">health_and_safety</span>
                            CMU
                        </button>
                    </div>
                </div>

                <!-- Zone Espèces : Montant reçu & Monnaie -->
                <div id="cashCalcSection" class="space-y-2 bg-surface-lowest p-3 rounded-xl border border-outline-variant">
                    <div class="flex items-center justify-between gap-2">
                        <label class="text-xs font-bold text-gray-600">Montant reçu :</label>
                        <div class="relative w-36">
                            <input 
                                type="number" 
                                id="paidAmountInput" 
                                placeholder="0" 
                                min="0"
                                class="w-full text-right font-extrabold text-sm py-1.5 px-2 bg-gray-50 border border-outline-variant rounded-lg focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary"
                            />
                        </div>
                    </div>

                    <!-- Raccourcis billets FCFA -->
                    <div class="flex items-center gap-1.5 flex-wrap pt-1 text-[11px]">
                        <button type="button" class="quick-cash-btn px-2 py-1 bg-gray-100 hover:bg-primary/10 hover:text-primary rounded font-bold border border-gray-200" data-cash="exact">Exact</button>
                        <button type="button" class="quick-cash-btn px-2 py-1 bg-gray-100 hover:bg-primary/10 hover:text-primary rounded font-bold border border-gray-200" data-cash="1000">1 000</button>
                        <button type="button" class="quick-cash-btn px-2 py-1 bg-gray-100 hover:bg-primary/10 hover:text-primary rounded font-bold border border-gray-200" data-cash="2000">2 000</button>
                        <button type="button" class="quick-cash-btn px-2 py-1 bg-gray-100 hover:bg-primary/10 hover:text-primary rounded font-bold border border-gray-200" data-cash="5000">5 000</button>
                        <button type="button" class="quick-cash-btn px-2 py-1 bg-gray-100 hover:bg-primary/10 hover:text-primary rounded font-bold border border-gray-200" data-cash="10000">10 000</button>
                    </div>

                    <!-- Monnaie à rendre -->
                    <div class="flex items-center justify-between pt-2 border-t border-dashed border-gray-200">
                        <span class="text-xs font-bold text-gray-500">Monnaie à rendre :</span>
                        <span id="changeAmountDisplay" class="font-extrabold text-base text-emerald-600">0 FCFA</span>
                    </div>
                </div>

                <!-- Bouton de Validation de la Vente -->
                <button 
                    type="button" 
                    id="submitSaleBtn" 
                    disabled 
                    class="w-full py-3.5 bg-primary hover:bg-primary-hover disabled:opacity-40 disabled:pointer-events-none text-white font-extrabold text-base rounded-xl shadow-md flex items-center justify-center gap-2 transition-all cursor-pointer">
                    <span class="material-symbols-outlined text-[20px]">check_circle</span>
                    <span>Valider la Vente (F10)</span>
                </button>
            </div>
        </aside>
    </div>

    <!-- ======================= MODAL DE CONFIRMATION & IMPRESSION TICKET ======================= -->
    <div id="saleSuccessModal" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden border border-outline-variant animate-in fade-in zoom-in duration-200">
            <!-- Header Modal -->
            <div class="bg-primary p-6 text-white text-center relative">
                <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-2 text-white">
                    <span class="material-symbols-outlined text-3xl">task_alt</span>
                </div>
                <h3 class="text-xl font-extrabold">Vente Validée !</h3>
                <p id="modalSaleRef" class="text-xs text-on-primary-container font-mono mt-0.5">VNT-XXXXXXXX</p>
            </div>

            <!-- Corps du Reçu -->
            <div class="p-6 space-y-4">
                <div class="bg-surface-low rounded-xl p-4 space-y-2 border border-outline-variant/60 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>Montant Total :</span>
                        <span id="modalTotalAmount" class="font-bold text-gray-900">0 FCFA</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Mode de Règlement :</span>
                        <span id="modalPaymentMethod" class="font-bold capitalize text-gray-900">Espèces</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Montant Versé :</span>
                        <span id="modalPaidAmount" class="font-bold text-gray-900">0 FCFA</span>
                    </div>
                    <div class="flex justify-between text-emerald-700 font-bold pt-2 border-t border-gray-200">
                        <span>Monnaie Rendue :</span>
                        <span id="modalChangeAmount" class="text-base font-extrabold">0 FCFA</span>
                    </div>
                </div>

                <!-- Actions Impression -->
                <div class="grid grid-cols-2 gap-3">
                    <button 
                        type="button" 
                        id="printTicketBtn"
                        class="py-3 px-4 bg-primary text-white font-bold text-sm rounded-xl hover:bg-primary-hover flex items-center justify-center gap-2 shadow-sm transition-all">
                        <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                        Imprimer Ticket
                    </button>
                    <a 
                        id="downloadPdfBtn"
                        href="#" 
                        target="_blank"
                        class="py-3 px-4 bg-surface-low text-primary border border-primary/30 font-bold text-sm rounded-xl hover:bg-primary/10 flex items-center justify-center gap-2 transition-all">
                        <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span>
                        Reçu PDF
                    </a>
                </div>

                <button 
                    type="button" 
                    id="newSaleModalBtn" 
                    class="w-full py-3 bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold text-sm rounded-xl transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    Nouvelle Vente
                </button>
            </div>
        </div>
    </div>

    <!-- Iframe caché pour impression directe ticket -->
    <iframe id="printTicketFrame" class="hidden w-0 h-0 border-0"></iframe>

    <!-- ======================= LOGIQUE JS DU GUICHET ======================= -->
    <script>
        // Données transmises depuis Laravel
        const allMedications = @json($medications, JSON_UNESCAPED_UNICODE);
        let selectedCategory = 'all';
        let searchQuery = '';
        let cart = []; // [{ medication, quantity }]
        let selectedPaymentMethod = 'espèces';
        let currentSale = null;

        // Horloge temps réel
        function updateClock() {
            const now = new Date();
            const timeStr = now.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            document.getElementById('liveClock').textContent = timeStr;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Initialisation de la grille médicaments
        function renderMedications() {
            const grid = document.getElementById('medicationsGrid');
            const noState = document.getElementById('noMedicationState');
            grid.innerHTML = '';

            const filtered = allMedications.filter(med => {
                const matchesCat = (selectedCategory === 'all') || (med.category_id == selectedCategory);
                const query = searchQuery.toLowerCase().trim();
                const matchesSearch = !query || 
                    med.name.toLowerCase().includes(query) || 
                    med.code.toLowerCase().includes(query) || 
                    (med.dosage && med.dosage.toLowerCase().includes(query)) ||
                    (med.form && med.form.toLowerCase().includes(query));
                return matchesCat && matchesSearch;
            });

            document.getElementById('totalCount').textContent = allMedications.length;

            if (filtered.length === 0) {
                noState.classList.remove('hidden');
            } else {
                noState.classList.add('hidden');
            }

            filtered.forEach(med => {
                const inCartItem = cart.find(i => i.medication.id === med.id);
                const inCartQty = inCartItem ? inCartItem.quantity : 0;
                const remainingStock = med.stock_quantity - inCartQty;
                const isOutOfStock = remainingStock <= 0;

                const card = document.createElement('div');
                card.className = `bg-surface-lowest border border-outline-variant/80 rounded-xl p-3.5 flex flex-col justify-between shadow-sm transition-all ${isOutOfStock ? 'opacity-50 grayscale' : 'hover:border-primary hover:shadow-md cursor-pointer'}`;
                
                let stockBadgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                if (remainingStock <= 0) {
                    stockBadgeClass = 'bg-red-50 text-red-700 border-red-200';
                } else if (remainingStock <= med.min_threshold) {
                    stockBadgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
                }

                card.innerHTML = `
                    <div>
                        <div class="flex justify-between items-start gap-1 mb-1">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">${med.code}</span>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full border ${stockBadgeClass}">
                                ${remainingStock > 0 ? remainingStock + ' dispo' : 'Épuisé'}
                            </span>
                        </div>
                        <h4 class="font-bold text-sm text-gray-900 leading-snug line-clamp-1">${med.name}</h4>
                        <p class="text-xs text-gray-500 mt-0.5">${med.dosage || ''} ${med.form ? '• ' + med.form : ''}</p>
                    </div>

                    <div class="flex items-center justify-between mt-3 pt-2.5 border-t border-gray-100">
                        <div>
                            <span class="text-xs text-gray-400 font-semibold">Prix :</span>
                            <span class="font-extrabold text-sm text-primary block">${formatNumber(med.unit_price)} F</span>
                        </div>
                        <button 
                            type="button" 
                            ${isOutOfStock ? 'disabled' : ''}
                            class="add-to-cart-btn px-3 py-1.5 bg-primary/10 hover:bg-primary hover:text-white text-primary rounded-lg text-xs font-bold flex items-center gap-1 transition-all">
                            <span class="material-symbols-outlined text-[16px]">add</span>
                            <span>Ajouter</span>
                        </button>
                    </div>
                `;

                if (!isOutOfStock) {
                    card.addEventListener('click', (e) => {
                        addToCart(med);
                    });
                }

                grid.appendChild(card);
            });
        }

        // Gestion du Panier
        function addToCart(medication) {
            const existing = cart.find(i => i.medication.id === medication.id);
            if (existing) {
                if (existing.quantity < medication.stock_quantity) {
                    existing.quantity += 1;
                } else {
                    alert(`Stock maximum disponible atteint pour ${medication.name} (${medication.stock_quantity})`);
                    return;
                }
            } else {
                cart.push({
                    medication: medication,
                    quantity: 1
                });
            }
            renderCart();
            renderMedications(); // Met à jour les stocks restants affichés
        }

        function updateCartItemQty(medicationId, delta) {
            const item = cart.find(i => i.medication.id === medicationId);
            if (!item) return;

            const newQty = item.quantity + delta;
            if (newQty <= 0) {
                removeFromCart(medicationId);
            } else if (newQty > item.medication.stock_quantity) {
                alert(`Stock maximum disponible atteint (${item.medication.stock_quantity})`);
            } else {
                item.quantity = newQty;
                renderCart();
                renderMedications();
            }
        }

        function removeFromCart(medicationId) {
            cart = cart.filter(i => i.medication.id !== medicationId);
            renderCart();
            renderMedications();
        }

        function clearCart() {
            cart = [];
            document.getElementById('patientNameInput').value = '';
            document.getElementById('paidAmountInput').value = '';
            renderCart();
            renderMedications();
        }

        function getCartTotal() {
            return cart.reduce((sum, item) => sum + (item.quantity * item.medication.unit_price), 0);
        }

        function renderCart() {
            const container = document.getElementById('cartItemsList');
            const countBadge = document.getElementById('cartCountBadge');
            const totalDisplay = document.getElementById('cartTotalDisplay');
            const submitBtn = document.getElementById('submitSaleBtn');

            countBadge.textContent = cart.reduce((acc, item) => acc + item.quantity, 0);

            if (cart.length === 0) {
                container.innerHTML = `
                    <div id="emptyCartState" class="h-full flex flex-col items-center justify-center text-center text-on-surface-variant p-6">
                        <div class="w-16 h-16 rounded-full bg-surface-low flex items-center justify-center text-gray-300 mb-3">
                            <span class="material-symbols-outlined text-3xl">shopping_cart</span>
                        </div>
                        <p class="font-bold text-sm text-gray-600">Le panier est vide</p>
                        <p class="text-xs text-gray-400 mt-1 max-w-[220px]">Cliquez sur un médicament dans la liste de gauche pour l'ajouter au panier.</p>
                    </div>
                `;
                totalDisplay.textContent = '0';
                submitBtn.disabled = true;
                updateChangeCalculation();
                return;
            }

            container.innerHTML = '';
            const total = getCartTotal();
            totalDisplay.textContent = formatNumber(total);
            submitBtn.disabled = false;

            cart.forEach(item => {
                const subtotal = item.quantity * item.medication.unit_price;
                const row = document.createElement('div');
                row.className = 'bg-surface-low/80 border border-outline-variant/60 rounded-xl p-3 flex items-center justify-between gap-2 shadow-xs';
                row.innerHTML = `
                    <div class="flex-1 min-w-0">
                        <h5 class="font-bold text-xs text-gray-900 truncate">${item.medication.name}</h5>
                        <p class="text-[11px] text-gray-500">${formatNumber(item.medication.unit_price)} F × ${item.quantity} = <strong class="text-primary font-bold">${formatNumber(subtotal)} F</strong></p>
                    </div>
                    
                    <div class="flex items-center gap-1.5 shrink-0">
                        <button type="button" class="w-7 h-7 rounded-lg bg-white border border-outline-variant flex items-center justify-center text-gray-600 hover:bg-primary/10 hover:text-primary font-bold qty-minus">
                            -
                        </button>
                        <span class="w-7 text-center font-bold text-xs">${item.quantity}</span>
                        <button type="button" class="w-7 h-7 rounded-lg bg-white border border-outline-variant flex items-center justify-center text-gray-600 hover:bg-primary/10 hover:text-primary font-bold qty-plus">
                            +
                        </button>
                        <button type="button" class="w-7 h-7 rounded-lg text-gray-400 hover:text-error hover:bg-error-container/40 flex items-center justify-center ml-1 delete-item" title="Supprimer">
                            <span class="material-symbols-outlined text-[16px]">close</span>
                        </button>
                    </div>
                `;

                row.querySelector('.qty-minus').addEventListener('click', () => updateCartItemQty(item.medication.id, -1));
                row.querySelector('.qty-plus').addEventListener('click', () => updateCartItemQty(item.medication.id, 1));
                row.querySelector('.delete-item').addEventListener('click', () => removeFromCart(item.medication.id));

                container.appendChild(row);
            });

            updateChangeCalculation();
        }

        // Calcul du rendu de monnaie
        function updateChangeCalculation() {
            const total = getCartTotal();
            const paidInput = document.getElementById('paidAmountInput');
            const changeDisplay = document.getElementById('changeAmountDisplay');
            
            let paid = parseFloat(paidInput.value);
            if (isNaN(paid) || paid === 0) {
                changeDisplay.textContent = '0 FCFA';
                changeDisplay.className = 'font-extrabold text-base text-gray-400';
                return;
            }

            const change = paid - total;
            if (change >= 0) {
                changeDisplay.textContent = formatNumber(change) + ' FCFA';
                changeDisplay.className = 'font-extrabold text-base text-emerald-600';
            } else {
                changeDisplay.textContent = 'Manque: ' + formatNumber(Math.abs(change)) + ' F';
                changeDisplay.className = 'font-extrabold text-sm text-red-600';
            }
        }

        // Formatage des nombres
        function formatNumber(num) {
            return new Intl.NumberFormat('fr-FR').format(num || 0);
        }

        // Events Listener
        document.addEventListener('DOMContentLoaded', () => {
            renderMedications();
            renderCart();

            // Recherche
            const searchInput = document.getElementById('medSearch');
            const clearSearchBtn = document.getElementById('clearSearchBtn');

            searchInput.addEventListener('input', (e) => {
                searchQuery = e.target.value;
                if (searchQuery) {
                    clearSearchBtn.classList.remove('hidden');
                } else {
                    clearSearchBtn.classList.add('hidden');
                }
                renderMedications();
            });

            clearSearchBtn.addEventListener('click', () => {
                searchInput.value = '';
                searchQuery = '';
                clearSearchBtn.classList.add('hidden');
                searchInput.focus();
                renderMedications();
            });

            // Filtres catégories
            document.querySelectorAll('.cat-filter-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.querySelectorAll('.cat-filter-btn').forEach(b => {
                        b.classList.remove('active', 'bg-primary', 'text-white');
                        b.classList.add('bg-surface-low', 'text-on-surface-variant');
                    });
                    btn.classList.add('active', 'bg-primary', 'text-white');
                    btn.classList.remove('bg-surface-low', 'text-on-surface-variant');
                    selectedCategory = btn.getAttribute('data-cat');
                    renderMedications();
                });
            });

            // Vider panier
            document.getElementById('clearCartBtn').addEventListener('click', () => {
                if (cart.length > 0 && confirm('Voulez-vous vraiment vider le panier en cours ?')) {
                    clearCart();
                }
            });

            // Modes de paiement
            document.querySelectorAll('.pay-method-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.querySelectorAll('.pay-method-btn').forEach(b => {
                        b.classList.remove('active', 'bg-primary', 'text-white', 'border-primary');
                        b.classList.add('bg-surface-lowest', 'text-gray-700', 'border-outline-variant');
                    });
                    btn.classList.add('active', 'bg-primary', 'text-white', 'border-primary');
                    btn.classList.remove('bg-surface-lowest', 'text-gray-700', 'border-outline-variant');
                    selectedPaymentMethod = btn.getAttribute('data-method');

                    const cashSection = document.getElementById('cashCalcSection');
                    if (selectedPaymentMethod === 'espèces') {
                        cashSection.classList.remove('hidden');
                    } else {
                        // Pour Wave / Orange Money / CMU, montant versé = total
                        cashSection.classList.remove('hidden'); // On laisse visible pour consultation
                        document.getElementById('paidAmountInput').value = getCartTotal();
                        updateChangeCalculation();
                    }
                });
            });

            // Raccourcis espèces
            document.querySelectorAll('.quick-cash-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const cashVal = btn.getAttribute('data-cash');
                    const total = getCartTotal();
                    if (cashVal === 'exact') {
                        document.getElementById('paidAmountInput').value = total;
                    } else {
                        document.getElementById('paidAmountInput').value = parseInt(cashVal);
                    }
                    updateChangeCalculation();
                });
            });

            document.getElementById('paidAmountInput').addEventListener('input', updateChangeCalculation);

            // Validation de la vente
            document.getElementById('submitSaleBtn').addEventListener('click', submitSale);

            // Raccourcis Clavier : F2 (recherche), F10 (validation)
            window.addEventListener('keydown', (e) => {
                if (e.key === 'F2') {
                    e.preventDefault();
                    searchInput.focus();
                    searchInput.select();
                } else if (e.key === 'F10') {
                    e.preventDefault();
                    if (cart.length > 0) {
                        submitSale();
                    }
                }
            });

            // Modal actions
            document.getElementById('newSaleModalBtn').addEventListener('click', () => {
                document.getElementById('saleSuccessModal').classList.add('hidden');
                clearCart();
                searchInput.focus();
            });

            document.getElementById('printTicketBtn').addEventListener('click', () => {
                if (currentSale && currentSale.ticket_url) {
                    const frame = document.getElementById('printTicketFrame');
                    frame.src = currentSale.ticket_url;
                    frame.onload = function() {
                        frame.contentWindow.print();
                    };
                }
            });
        });

        // Envoi de la vente au backend
        async function submitSale() {
            if (cart.length === 0) return;

            const total = getCartTotal();
            const paidInput = document.getElementById('paidAmountInput');
            let paid = parseFloat(paidInput.value);
            if (isNaN(paid) || paid <= 0) {
                paid = total;
            }

            const payload = {
                patient_name: document.getElementById('patientNameInput').value.trim(),
                payment_method: selectedPaymentMethod,
                paid_amount: paid,
                items: cart.map(i => ({
                    medication_id: i.medication.id,
                    quantity: i.quantity
                }))
            };

            const submitBtn = document.getElementById('submitSaleBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = `<span class="material-symbols-outlined animate-spin text-[20px]">sync</span> Validation en cours...`;

            try {
                const response = await fetch("{{ route('sales.pos.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    currentSale = result;

                    // Mettre à jour les stocks locaux
                    cart.forEach(item => {
                        const localMed = allMedications.find(m => m.id === item.medication.id);
                        if (localMed) {
                            localMed.stock_quantity -= item.quantity;
                        }
                    });

                    // Remplir le modal
                    document.getElementById('modalSaleRef').textContent = result.reference;
                    document.getElementById('modalTotalAmount').textContent = formatNumber(result.total_amount) + ' FCFA';
                    document.getElementById('modalPaymentMethod').textContent = selectedPaymentMethod;
                    document.getElementById('modalPaidAmount').textContent = formatNumber(result.paid_amount) + ' FCFA';
                    document.getElementById('modalChangeAmount').textContent = formatNumber(result.change_amount) + ' FCFA';
                    document.getElementById('downloadPdfBtn').href = result.pdf_url;

                    // Ouvrir modal
                    document.getElementById('saleSuccessModal').classList.remove('hidden');

                    // Impression directe automatique facultative (pré-chargement du ticket)
                    const frame = document.getElementById('printTicketFrame');
                    frame.src = result.ticket_url;
                } else {
                    alert("Erreur lors de la vente : " + (result.message || "Une erreur est survenue."));
                }
            } catch (err) {
                console.error(err);
                alert("Erreur réseau ou serveur lors de la validation.");
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = `<span class="material-symbols-outlined text-[20px]">check_circle</span> <span>Valider la Vente (F10)</span>`;
            }
        }
    </script>
</body>
</html>
