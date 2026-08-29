<!DOCTYPE html>
<html class="light" lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'Gestion Pharmacie - Espace Vendeuse')</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet"/>

    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "primary": "#006565",
                        "outline": "#6e7979",
                        "surface-container-highest": "#d3e4fe",
                        "secondary": "#0059bb",
                        "background": "#f8f9ff",
                        "surface": "#f8f9ff",
                        "on-surface": "#0b1c30",
                        "on-surface-variant": "#3e4949",
                        "primary-container": "#008080",
                        "on-primary-container": "#e3fffe",
                        "secondary-container": "#0070ea",
                        "on-secondary-container": "#fefcff",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-low": "#eff4ff",
                        "surface-container": "#e5eeff",
                        "outline-variant": "#bdc9c8",
                        "error": "#ba1a1a",
                        "error-container": "#ffdad6"
                    },
                    "fontFamily": {
                        "sans": ["Inter", "sans-serif"]
                    }
                }
            }
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .icon-filled, .material-symbols-outlined[data-weight="fill"] {
            font-variation-settings: 'FILL' 1;
        }
    </style>
</head>
<body class="flex h-screen bg-background text-on-background font-sans">
    <!-- SideNavBar Vendeuse (Strictement conforme à la maquette Screenshot 4) -->
    <nav class="bg-surface-container-lowest border-r border-outline-variant h-screen w-64 flex flex-col py-md fixed left-0 top-0 z-50">
        <div class="px-lg pb-lg">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-bold">
                    <span class="material-symbols-outlined icon-filled">local_pharmacy</span>
                </div>
                <div>
                    <h1 class="font-title-lg text-title-lg text-primary font-bold">Gestion Pharmacie</h1>
                    <p class="font-label-md text-label-md text-on-surface-variant">Poste de Santé</p>
                </div>
            </div>
            <a href="{{ route('sales.pos') }}" class="w-full bg-primary text-on-primary font-title-lg text-title-lg py-3 rounded-lg flex items-center justify-center gap-2 hover:bg-surface-tint transition-colors mb-6 shadow-sm font-bold">
                <span class="material-symbols-outlined">add_circle</span>
                Nouvelle Vente
            </a>
        </div>
        <div class="flex-1 overflow-y-auto px-2 space-y-1">
            <a class="{{ request()->routeIs('vendor.dashboard') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-lowest' }} rounded-lg mx-2 flex items-center gap-3 px-4 py-3 font-label-md text-label-md transition-all" href="{{ route('vendor.dashboard') }}">
                <span class="material-symbols-outlined">dashboard</span>
                Tableau de bord
            </a>
            <a class="{{ request()->routeIs('sales.pos') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-lowest' }} rounded-lg mx-2 flex items-center gap-3 px-4 py-3 font-label-md text-label-md transition-all" href="{{ route('sales.pos') }}">
                <span class="material-symbols-outlined">shopping_cart</span>
                Nouvelle vente
            </a>
            <a class="{{ request()->routeIs('vendor.sales') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-lowest' }} rounded-lg mx-2 flex items-center gap-3 px-4 py-3 font-label-md text-label-md transition-all" href="{{ route('vendor.sales') }}">
                <span class="material-symbols-outlined">point_of_sale</span>
                Mes ventes
            </a>
            <a class="{{ request()->routeIs('vendor.medications') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-lowest' }} rounded-lg mx-2 flex items-center gap-3 px-4 py-3 font-label-md text-label-md transition-all" href="{{ route('vendor.medications') }}">
                <span class="material-symbols-outlined">medication</span>
                Médicaments
            </a>
            <a class="{{ request()->routeIs('vendor.returns') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-lowest' }} rounded-lg mx-2 flex items-center gap-3 px-4 py-3 font-label-md text-label-md transition-all" href="{{ route('vendor.returns') }}">
                <span class="material-symbols-outlined">assignment_return</span>
                Retours / annulations
            </a>
            <a class="{{ request()->routeIs('cash_register.index') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-lowest' }} rounded-lg mx-2 flex items-center gap-3 px-4 py-3 font-label-md text-label-md transition-all" href="{{ route('cash_register.index') }}">
                <span class="material-symbols-outlined">account_balance_wallet</span>
                Caisse
            </a>
            <a class="{{ request()->routeIs('vendor.profile') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-lowest' }} rounded-lg mx-2 flex items-center gap-3 px-4 py-3 font-label-md text-label-md transition-all" href="{{ route('vendor.profile') }}">
                <span class="material-symbols-outlined">person</span>
                Mon profil
            </a>
        </div>
        <div class="mt-auto px-2 pt-4 border-t border-outline-variant mx-4">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-on-surface-variant hover:text-error mx-2 flex items-center gap-3 px-4 py-3 font-label-md text-label-md transition-all rounded-lg hover:bg-error-container/20 font-bold">
                    <span class="material-symbols-outlined">logout</span>
                    Déconnexion
                </button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="ml-64 flex-1 flex flex-col h-screen overflow-hidden bg-background">
        <!-- Header -->
        <header class="bg-surface border-b border-outline-variant h-16 flex justify-between items-center px-lg w-full sticky top-0 z-40">
            <div>
                <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Bonjour, {{ auth()->user()->name }}</h2>
                <p class="font-body-sm text-body-sm text-on-surface-variant">{{ date('l d F Y') }}</p>
            </div>
            <div class="flex items-center gap-4">
                <button class="w-10 h-10 rounded-full hover:bg-surface-container-low transition-colors flex items-center justify-center text-on-surface-variant relative">
                    <span class="material-symbols-outlined">notifications</span>
                    <span class="absolute top-2 right-2 w-2 h-2 bg-error rounded-full border border-surface"></span>
                </button>
                <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-bold text-sm border-2 border-outline-variant">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            </div>
        </header>

        <!-- Canvas -->
        <div class="flex-1 overflow-y-auto p-lg space-y-lg">
            @if(session('success'))
                <div class="p-md rounded-lg bg-primary-container/20 border border-primary text-primary font-bold flex items-center gap-2">
                    <span class="material-symbols-outlined">check_circle</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</body>
</html>
