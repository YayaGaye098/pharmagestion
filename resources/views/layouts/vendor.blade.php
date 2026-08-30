<!DOCTYPE html>
<html class="light" lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'PharmaGestion - Espace Vendeuse')</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

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
    </style>
</head>
<body class="flex h-screen bg-[#f0f4f8] text-on-surface font-sans antialiased overflow-hidden">

    <!-- Sidebar Vendeuse -->
    <aside class="w-64 bg-surface-lowest border-r border-outline-variant flex flex-col shrink-0 z-40">
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
        <!-- Top Bar -->
        <header class="h-16 bg-surface-lowest border-b border-outline-variant px-8 flex items-center justify-between shrink-0 shadow-xs">
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
        <div class="flex-1 overflow-y-auto p-8 space-y-6">
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
</body>
</html>
