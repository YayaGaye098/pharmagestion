<!DOCTYPE html>
<html class="light" lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'PharmaGestion - Espace Vendeuse')</title>

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
                        "secondary-container": "#0070ea",
                        "on-secondary-container": "#fefcff",
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
                        sans: ["Inter", "sans-serif"]
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
        /* Sidebar transition */
        #vendorMobileSidebar {
            transition: transform 0.3s ease-in-out;
        }
        #vendorMobileSidebar.sidebar-closed {
            transform: translateX(-100%);
        }
        #vendorMobileSidebar.sidebar-open {
            transform: translateX(0);
        }
        #vendorSidebarOverlay {
            transition: opacity 0.3s ease-in-out;
        }
        /* Welcome banner animation */
        @keyframes slideDown {
            from { transform: translateY(-100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; transform: translateY(-20px); }
        }
        .welcome-banner {
            animation: slideDown 0.5s ease-out;
        }
        .welcome-banner.hiding {
            animation: fadeOut 0.5s ease-in forwards;
        }
    </style>
</head>
<body class="flex h-screen bg-[#f0f4f8] text-on-surface font-sans antialiased overflow-hidden">

    <!-- Mobile/Tablet Overlay -->
    <div id="vendorSidebarOverlay" class="fixed inset-0 bg-black/50 z-40 lg:hidden opacity-0 pointer-events-none" onclick="closeVendorSidebar()"></div>

    <!-- Mobile/Tablet Sidebar (Slide-out) -->
    <aside id="vendorMobileSidebar" class="fixed left-0 top-0 h-screen w-72 bg-surface-lowest border-r border-outline-variant flex flex-col shrink-0 z-50 lg:hidden sidebar-closed shadow-xl">
        <!-- Logo & Titre -->
        <div class="p-6 border-b border-outline-variant/60">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary text-white flex items-center justify-center font-bold shadow-sm">
                        <span class="material-symbols-outlined icon-filled text-2xl">local_pharmacy</span>
                    </div>
                    <div>
                        <h1 class="font-bold text-base text-primary leading-tight">PharmaGestion</h1>
                        <p class="text-xs text-on-surface-variant">Espace Vendeuse</p>
                    </div>
                </div>
                <button onclick="closeVendorSidebar()" class="p-1.5 rounded-lg text-on-surface-variant hover:bg-surface-container transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <!-- Bouton Guichet Principal -->
            <a href="{{ route('sales.pos') }}" onclick="closeVendorSidebar()" class="w-full bg-primary hover:bg-primary-hover text-white font-bold py-3 px-4 rounded-xl flex items-center justify-center gap-2 shadow-sm transition-all text-sm">
                <span class="material-symbols-outlined text-[20px]">shopping_cart_checkout</span>
                <span>Ouvrir le Guichet</span>
            </a>
        </div>

        <!-- Menu de Navigation -->
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1.5">
            <a href="{{ route('vendor.dashboard') }}" onclick="closeVendorSidebar()" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('vendor.dashboard') ? 'bg-primary/10 text-primary font-bold' : 'text-on-surface-variant hover:bg-surface-low hover:text-primary' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('vendor.dashboard') ? 'icon-filled text-primary' : '' }}">dashboard</span>
                <span>Tableau de bord</span>
            </a>

            <a href="{{ route('sales.pos') }}" onclick="closeVendorSidebar()" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('sales.pos*') ? 'bg-primary/10 text-primary font-bold' : 'text-on-surface-variant hover:bg-surface-low hover:text-primary' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('sales.pos*') ? 'icon-filled text-primary' : '' }}">point_of_sale</span>
                <span>Guichet de Vente</span>
            </a>

            <a href="{{ route('vendor.sales') }}" onclick="closeVendorSidebar()" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('vendor.sales') ? 'bg-primary/10 text-primary font-bold' : 'text-on-surface-variant hover:bg-surface-low hover:text-primary' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('vendor.sales') ? 'icon-filled text-primary' : '' }}">receipt_long</span>
                <span>Mes Ventes</span>
            </a>

            <a href="{{ route('vendor.medications') }}" onclick="closeVendorSidebar()" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('vendor.medications') ? 'bg-primary/10 text-primary font-bold' : 'text-on-surface-variant hover:bg-surface-low hover:text-primary' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('vendor.medications') ? 'icon-filled text-primary' : '' }}">medication</span>
                <span>Catalogue & Prix</span>
            </a>

            <a href="{{ route('vendor.reports') }}" onclick="closeVendorSidebar()" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('vendor.reports') ? 'bg-primary/10 text-primary font-bold' : 'text-on-surface-variant hover:bg-surface-low hover:text-primary' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('vendor.reports') ? 'icon-filled text-primary' : '' }}">analytics</span>
                <span>Rapports d'activité</span>
            </a>
        </nav>

        <!-- Bas de page / Profil & Déconnexion -->
        <div class="p-4 border-t border-outline-variant/60 bg-surface">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-primary-container text-white flex items-center justify-center font-bold text-xs">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="text-left leading-tight">
                        <p class="text-xs font-bold text-on-surface">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-gray-500">Vendeuse</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="p-1.5 rounded-lg text-gray-400 hover:text-error hover:bg-error-container/40 transition-colors" title="Déconnexion">
                        <span class="material-symbols-outlined text-[20px]">logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Desktop Sidebar Vendeuse (hidden on mobile/tablet) -->
    <aside class="hidden lg:flex w-64 bg-surface-lowest border-r border-outline-variant flex-col shrink-0 z-40">
        <!-- Logo & Titre -->
        <div class="p-6 border-b border-outline-variant/60">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-xl bg-primary text-white flex items-center justify-center font-bold shadow-sm">
                    <span class="material-symbols-outlined icon-filled text-2xl">local_pharmacy</span>
                </div>
                <div>
                    <h1 class="font-bold text-base text-primary leading-tight">PharmaGestion</h1>
                    <p class="text-xs text-on-surface-variant">Espace Vendeuse</p>
                </div>
            </div>

            <!-- Bouton Guichet Principal -->
            <a href="{{ route('sales.pos') }}" class="w-full bg-primary hover:bg-primary-hover text-white font-bold py-3 px-4 rounded-xl flex items-center justify-center gap-2 shadow-sm transition-all text-sm">
                <span class="material-symbols-outlined text-[20px]">shopping_cart_checkout</span>
                <span>Ouvrir le Guichet</span>
            </a>
        </div>

        <!-- Menu de Navigation -->
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1.5">
            <a href="{{ route('vendor.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('vendor.dashboard') ? 'bg-primary/10 text-primary font-bold' : 'text-on-surface-variant hover:bg-surface-low hover:text-primary' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('vendor.dashboard') ? 'icon-filled text-primary' : '' }}">dashboard</span>
                <span>Tableau de bord</span>
            </a>

            <a href="{{ route('sales.pos') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('sales.pos*') ? 'bg-primary/10 text-primary font-bold' : 'text-on-surface-variant hover:bg-surface-low hover:text-primary' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('sales.pos*') ? 'icon-filled text-primary' : '' }}">point_of_sale</span>
                <span>Guichet de Vente</span>
            </a>

            <a href="{{ route('vendor.sales') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('vendor.sales') ? 'bg-primary/10 text-primary font-bold' : 'text-on-surface-variant hover:bg-surface-low hover:text-primary' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('vendor.sales') ? 'icon-filled text-primary' : '' }}">receipt_long</span>
                <span>Mes Ventes</span>
            </a>

            <a href="{{ route('vendor.medications') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('vendor.medications') ? 'bg-primary/10 text-primary font-bold' : 'text-on-surface-variant hover:bg-surface-low hover:text-primary' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('vendor.medications') ? 'icon-filled text-primary' : '' }}">medication</span>
                <span>Catalogue & Prix</span>
            </a>

            <a href="{{ route('vendor.reports') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('vendor.reports') ? 'bg-primary/10 text-primary font-bold' : 'text-on-surface-variant hover:bg-surface-low hover:text-primary' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('vendor.reports') ? 'icon-filled text-primary' : '' }}">analytics</span>
                <span>Rapports d'activité</span>
            </a>
        </nav>

        <!-- Bas de page / Profil & Déconnexion -->
        <div class="p-4 border-t border-outline-variant/60 bg-surface">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-primary-container text-white flex items-center justify-center font-bold text-xs">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="text-left leading-tight">
                        <p class="text-xs font-bold text-on-surface">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-gray-500">Vendeuse</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="p-1.5 rounded-lg text-gray-400 hover:text-error hover:bg-error-container/40 transition-colors" title="Déconnexion">
                        <span class="material-symbols-outlined text-[20px]">logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <!-- Mobile/Tablet Top Bar with hamburger -->
        <header class="flex lg:hidden h-14 bg-surface-lowest border-b border-outline-variant px-4 items-center justify-between shrink-0 shadow-xs">
            <div class="flex items-center gap-2">
                <button onclick="openVendorSidebar()" class="p-1.5 rounded-lg text-on-surface hover:bg-surface-container transition-colors">
                    <span class="material-symbols-outlined text-2xl">menu</span>
                </button>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-xl bg-primary text-white flex items-center justify-center font-bold shadow-sm">
                        <span class="material-symbols-outlined icon-filled text-lg">local_pharmacy</span>
                    </div>
                    <span class="font-bold text-sm text-primary">PharmaGestion</span>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('sales.pos') }}" class="bg-primary hover:bg-primary-hover text-white text-xs font-bold px-3 py-1.5 rounded-xl flex items-center gap-1 shadow-sm transition-all">
                    <span class="material-symbols-outlined text-[16px]">add_circle</span>
                    <span>Vente</span>
                </a>
                <div class="w-8 h-8 rounded-full bg-primary-container text-white flex items-center justify-center font-bold text-xs">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            </div>
        </header>

        <!-- Desktop Top Bar -->
        <header class="hidden lg:flex h-16 bg-surface-lowest border-b border-outline-variant px-8 items-center justify-between shrink-0 shadow-xs">
            <div>
                <h2 class="text-base font-bold text-on-surface">Bonjour, {{ auth()->user()->name }}</h2>
                <p class="text-xs text-on-surface-variant">{{ \Carbon\Carbon::now()->translatedFormat('l d F Y') }}</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('sales.pos') }}" class="bg-primary hover:bg-primary-hover text-white text-xs font-bold px-3.5 py-2 rounded-xl flex items-center gap-1.5 shadow-sm transition-all">
                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    <span>Nouvelle Vente (Guichet)</span>
                </a>
            </div>
        </header>

        <!-- Canvas -->
        <div class="flex-1 overflow-y-auto p-4 lg:p-8 space-y-6">
            {{-- Welcome banner after login --}}
            @if(session('welcome'))
                <div id="vendorWelcomeBanner" class="welcome-banner p-4 rounded-xl bg-gradient-to-r from-primary-container to-primary text-white font-medium text-sm flex items-center gap-4 shadow-lg">
                    <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-3xl text-white">waving_hand</span>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-base">{{ session('welcome') }}</p>
                        <p class="text-white/80 text-xs mt-1">Votre session est active. Bonnes ventes !</p>
                    </div>
                    <button onclick="dismissVendorWelcome()" class="p-1.5 rounded-lg text-white/70 hover:text-white hover:bg-white/10 transition-colors shrink-0">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
            @endif

            @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 font-medium text-sm flex items-center gap-3 shadow-xs">
                    <span class="material-symbols-outlined text-emerald-600 text-[20px]">check_circle</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error') || $errors->any())
                <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 font-medium text-sm flex items-center gap-3 shadow-xs">
                    <span class="material-symbols-outlined text-red-600 text-[20px]">error</span>
                    <span>{{ session('error') ?? $errors->first() }}</span>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

<script>
    // Mobile/Tablet sidebar toggle
    function openVendorSidebar() {
        const sidebar = document.getElementById('vendorMobileSidebar');
        const overlay = document.getElementById('vendorSidebarOverlay');
        sidebar.classList.remove('sidebar-closed');
        sidebar.classList.add('sidebar-open');
        overlay.classList.remove('opacity-0', 'pointer-events-none');
        overlay.classList.add('opacity-100');
        document.body.style.overflow = 'hidden';
    }

    function closeVendorSidebar() {
        const sidebar = document.getElementById('vendorMobileSidebar');
        const overlay = document.getElementById('vendorSidebarOverlay');
        sidebar.classList.remove('sidebar-open');
        sidebar.classList.add('sidebar-closed');
        overlay.classList.remove('opacity-100');
        overlay.classList.add('opacity-0', 'pointer-events-none');
        document.body.style.overflow = '';
    }

    // Auto-dismiss welcome banner after 6 seconds
    function dismissVendorWelcome() {
        const banner = document.getElementById('vendorWelcomeBanner');
        if (banner) {
            banner.classList.add('hiding');
            setTimeout(() => banner.remove(), 500);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const banner = document.getElementById('vendorWelcomeBanner');
        if (banner) {
            setTimeout(() => dismissVendorWelcome(), 6000);
        }
    });
</script>
</body>
</html>
