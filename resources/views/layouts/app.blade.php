<!DOCTYPE html>
<html class="light" lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'PharmaGestion')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="{{ asset('js/tailwindcss.js') }}"></script>

    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "primary": "#006565",
                        "outline": "#6e7979",
                        "surface-container-highest": "#d3e4fe",
                        "secondary-fixed-dim": "#adc7ff",
                        "tertiary": "#8b4823",
                        "surface-bright": "#f8f9ff",
                        "secondary-fixed": "#d8e2ff",
                        "on-secondary-fixed-variant": "#004493",
                        "on-secondary-container": "#fefcff",
                        "inverse-surface": "#213145",
                        "outline-variant": "#bdc9c8",
                        "surface-container-lowest": "#ffffff",
                        "on-error": "#ffffff",
                        "surface-container-low": "#eff4ff",
                        "error-container": "#ffdad6",
                        "surface-tint": "#006a6a",
                        "primary-fixed": "#93f2f2",
                        "surface-container-high": "#dce9ff",
                        "tertiary-container": "#a96039",
                        "on-tertiary-fixed-variant": "#733512",
                        "tertiary-fixed-dim": "#ffb692",
                        "tertiary-fixed": "#ffdbcb",
                        "secondary": "#0059bb",
                        "background": "#f8f9ff",
                        "on-secondary": "#ffffff",
                        "surface-variant": "#d3e4fe",
                        "secondary-container": "#0070ea",
                        "inverse-on-surface": "#eaf1ff",
                        "surface": "#f8f9ff",
                        "on-tertiary-container": "#fff9f7",
                        "surface-dim": "#cbdbf5",
                        "on-secondary-fixed": "#001a41",
                        "on-primary-fixed": "#002020",
                        "on-primary-fixed-variant": "#004f4f",
                        "on-surface-variant": "#3e4949",
                        "on-error-container": "#93000a",
                        "on-primary": "#ffffff",
                        "on-primary-container": "#e3fffe",
                        "primary-container": "#008080",
                        "on-surface": "#0b1c30",
                        "error": "#ba1a1a",
                        "on-tertiary": "#ffffff",
                        "inverse-primary": "#76d6d5",
                        "on-tertiary-fixed": "#341100",
                        "primary-fixed-dim": "#76d6d5",
                        "on-background": "#0b1c30",
                        "surface-container": "#e5eeff"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "xl": "32px",
                        "md": "16px",
                        "unit": "4px",
                        "lg": "24px",
                        "gutter": "12px",
                        "xs": "4px",
                        "sm": "8px",
                        "container-margin": "16px"
                    },
                    "fontFamily": {
                        "label-md": ["Inter"],
                        "body-md": ["Inter"],
                        "headline-md": ["Inter"],
                        "headline-sm": ["Inter"],
                        "currency-md": ["Inter"],
                        "title-lg": ["Inter"],
                        "body-sm": ["Inter"],
                        "display-lg": ["Inter"]
                    },
                    "fontSize": {
                        "label-md": ["12px", { "lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600" }],
                        "body-md": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                        "headline-md": ["24px", { "lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "600" }],
                        "headline-sm": ["20px", { "lineHeight": "28px", "fontWeight": "600" }],
                        "currency-md": ["16px", { "lineHeight": "24px", "fontWeight": "700" }],
                        "title-lg": ["18px", { "lineHeight": "24px", "fontWeight": "600" }],
                        "body-sm": ["14px", { "lineHeight": "20px", "fontWeight": "400" }],
                        "display-lg": ["32px", { "lineHeight": "40px", "letterSpacing": "-0.02em", "fontWeight": "700" }]
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
        .card-level-1 {
            background: #ffffff;
            border: 1px solid #E2E8F0;
            box-shadow: 0px 2px 4px rgba(0,0,0,0.05);
        }
        /* Sidebar transition */
        #mobileSidebar {
            transition: transform 0.3s ease-in-out;
        }
        #mobileSidebar.sidebar-closed {
            transform: translateX(-100%);
        }
        #mobileSidebar.sidebar-open {
            transform: translateX(0);
        }
        /* Overlay transition */
        #sidebarOverlay {
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
<body class="bg-background text-on-background font-body-md antialiased min-h-screen flex">
    <!-- Mobile/Tablet Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 lg:hidden opacity-0 pointer-events-none" onclick="closeSidebar()"></div>

    <!-- Mobile/Tablet Sidebar (Slide-out) -->
    <aside id="mobileSidebar" class="fixed left-0 top-0 h-screen w-72 bg-surface shadow-xl z-50 flex flex-col py-md space-y-sm overflow-y-auto border-r border-outline-variant lg:hidden sidebar-closed">
        <!-- Close button -->
        <div class="flex justify-between items-center px-lg pb-md mb-md border-b border-outline-variant">
            <div class="flex items-center space-x-sm">
                <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-headline-sm">
                    <span class="material-symbols-outlined" data-weight="fill">local_pharmacy</span>
                </div>
                <div>
                    <h1 class="font-headline-sm text-headline-sm font-bold text-on-surface">PharmaGestion</h1>
                    <p class="font-label-md text-label-md text-on-surface-variant">Poste de Santé (Admin)</p>
                </div>
            </div>
            <button onclick="closeSidebar()" class="p-1 rounded-lg text-on-surface-variant hover:bg-surface-container transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <ul class="flex-1 px-sm space-y-xs">
            <li>
                <a class="flex items-center space-x-sm px-md py-sm rounded-lg {{ request()->routeIs('dashboard') ? 'text-primary font-bold border-r-4 border-primary bg-surface-container' : 'text-on-surface-variant hover:bg-surface-container' }} transition-all" href="{{ route('dashboard') }}" onclick="closeSidebar()">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span class="font-label-md text-label-md">Dashboard Admin</span>
                </a>
            </li>
            <li>
                <a class="flex items-center space-x-sm px-md py-sm rounded-lg {{ request()->routeIs('admin.vendors.*') ? 'text-primary font-bold border-r-4 border-primary bg-surface-container' : 'text-on-surface-variant hover:bg-surface-container' }} transition-all" href="{{ route('admin.vendors.index') }}" onclick="closeSidebar()">
                    <span class="material-symbols-outlined">group</span>
                    <span class="font-label-md text-label-md">Gestion Vendeuses</span>
                </a>
            </li>
            <li>
                <a class="flex items-center space-x-sm px-md py-sm rounded-lg {{ request()->routeIs('medications.*') ? 'text-primary font-bold border-r-4 border-primary bg-surface-container' : 'text-on-surface-variant hover:bg-surface-container' }} transition-all" href="{{ route('medications.index') }}" onclick="closeSidebar()">
                    <span class="material-symbols-outlined">medical_services</span>
                    <span class="font-label-md text-label-md">Médicaments</span>
                </a>
            </li>
            <li>
                <a class="flex items-center space-x-sm px-md py-sm rounded-lg {{ request()->routeIs('stock.*') ? 'text-primary font-bold border-r-4 border-primary bg-surface-container' : 'text-on-surface-variant hover:bg-surface-container' }} transition-all" href="{{ route('stock.index') }}" onclick="closeSidebar()">
                    <span class="material-symbols-outlined">inventory_2</span>
                    <span class="font-label-md text-label-md">Stock</span>
                </a>
            </li>
            <li>
                <a class="flex items-center space-x-sm px-md py-sm rounded-lg {{ request()->routeIs('entries.*') ? 'text-primary font-bold border-r-4 border-primary bg-surface-container' : 'text-on-surface-variant hover:bg-surface-container' }} transition-all" href="{{ route('entries.index') }}" onclick="closeSidebar()">
                    <span class="material-symbols-outlined">login</span>
                    <span class="font-label-md text-label-md">Entrées</span>
                </a>
            </li>
            <li>
                <a class="flex items-center space-x-sm px-md py-sm rounded-lg {{ request()->routeIs('exits.*') ? 'text-primary font-bold border-r-4 border-primary bg-surface-container' : 'text-on-surface-variant hover:bg-surface-container' }} transition-all" href="{{ route('exits.index') }}" onclick="closeSidebar()">
                    <span class="material-symbols-outlined">logout</span>
                    <span class="font-label-md text-label-md">Sorties</span>
                </a>
            </li>
            <li>
                <a class="flex items-center space-x-sm px-md py-sm rounded-lg {{ request()->routeIs('inventories.*') ? 'text-primary font-bold border-r-4 border-primary bg-surface-container' : 'text-on-surface-variant hover:bg-surface-container' }} transition-all" href="{{ route('inventories.index') }}" onclick="closeSidebar()">
                    <span class="material-symbols-outlined">assignment</span>
                    <span class="font-label-md text-label-md">Inventaires</span>
                </a>
            </li>
            <li>
                <a class="flex items-center space-x-sm px-md py-sm rounded-lg {{ request()->routeIs('traceability.*') ? 'text-primary font-bold border-r-4 border-primary bg-surface-container' : 'text-on-surface-variant hover:bg-surface-container' }} transition-all" href="{{ route('traceability.index') }}" onclick="closeSidebar()">
                    <span class="material-symbols-outlined">history</span>
                    <span class="font-label-md text-label-md">Traçabilité</span>
                </a>
            </li>
            <li>
                <a class="flex items-center space-x-sm px-md py-sm rounded-lg {{ request()->routeIs('prices.*') ? 'text-primary font-bold border-r-4 border-primary bg-surface-container' : 'text-on-surface-variant hover:bg-surface-container' }} transition-all" href="{{ route('prices.index') }}" onclick="closeSidebar()">
                    <span class="material-symbols-outlined">payments</span>
                    <span class="font-label-md text-label-md">Prix</span>
                </a>
            </li>
            <li>
                <a class="flex items-center space-x-sm px-md py-sm rounded-lg {{ request()->routeIs('reports.*') ? 'text-primary font-bold border-r-4 border-primary bg-surface-container' : 'text-on-surface-variant hover:bg-surface-container' }} transition-all" href="{{ route('reports.index') }}" onclick="closeSidebar()">
                    <span class="material-symbols-outlined">assessment</span>
                    <span class="font-label-md text-label-md">Rapports</span>
                </a>
            </li>
            <li>
                <a class="flex items-center space-x-sm px-md py-sm rounded-lg {{ request()->routeIs('alerts.*') ? 'text-primary font-bold border-r-4 border-primary bg-surface-container' : 'text-on-surface-variant hover:bg-surface-container' }} transition-all" href="{{ route('alerts.index') }}" onclick="closeSidebar()">
                    <span class="material-symbols-outlined">notification_important</span>
                    <span class="font-label-md text-label-md">Alertes</span>
                </a>
            </li>
            <li>
                <a class="flex items-center space-x-sm px-md py-sm rounded-lg {{ request()->routeIs('users.*') ? 'text-primary font-bold border-r-4 border-primary bg-surface-container' : 'text-on-surface-variant hover:bg-surface-container' }} transition-all" href="{{ route('users.index') }}" onclick="closeSidebar()">
                    <span class="material-symbols-outlined">group</span>
                    <span class="font-label-md text-label-md">Utilisateurs</span>
                </a>
            </li>
            <li>
                <a class="flex items-center space-x-sm px-md py-sm rounded-lg {{ request()->routeIs('settings.*') ? 'text-primary font-bold border-r-4 border-primary bg-surface-container' : 'text-on-surface-variant hover:bg-surface-container' }} transition-all" href="{{ route('settings.index') }}" onclick="closeSidebar()">
                    <span class="material-symbols-outlined">settings</span>
                    <span class="font-label-md text-label-md">Paramètres</span>
                </a>
            </li>
            <li class="pt-md border-t border-outline-variant">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center space-x-sm px-md py-sm rounded-lg text-error hover:bg-error-container/20 transition-all text-left font-label-md text-label-md cursor-pointer">
                        <span class="material-symbols-outlined">logout</span>
                        <span>Déconnexion</span>
                    </button>
                </form>
            </li>
        </ul>
    </aside>

    <!-- Desktop SideNavBar Admin (hidden on mobile/tablet) -->
    <aside class="hidden lg:flex flex-col h-screen fixed left-0 top-0 py-md space-y-sm overflow-y-auto w-64 bg-surface shadow-sm z-50 border-r border-outline-variant">
        <div class="px-lg pb-md mb-md border-b border-outline-variant">
            <div class="flex items-center space-x-sm">
                <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-headline-sm">
                    <span class="material-symbols-outlined" data-weight="fill">local_pharmacy</span>
                </div>
                <div>
                    <h1 class="font-headline-sm text-headline-sm font-bold text-on-surface">PharmaGestion</h1>
                    <p class="font-label-md text-label-md text-on-surface-variant">Poste de Santé (Admin)</p>
                </div>
            </div>
        </div>
        <ul class="flex-1 px-sm space-y-xs">
            <li>
                <a class="flex items-center space-x-sm px-md py-sm rounded-lg {{ request()->routeIs('dashboard') ? 'text-primary font-bold border-r-4 border-primary bg-surface-container' : 'text-on-surface-variant hover:bg-surface-container' }} transition-all scale-95 duration-75" href="{{ route('dashboard') }}">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span class="font-label-md text-label-md">Dashboard Admin</span>
                </a>
            </li>
            <li>
                <a class="flex items-center space-x-sm px-md py-sm rounded-lg {{ request()->routeIs('admin.vendors.*') ? 'text-primary font-bold border-r-4 border-primary bg-surface-container' : 'text-on-surface-variant hover:bg-surface-container' }} transition-all scale-95 duration-75" href="{{ route('admin.vendors.index') }}">
                    <span class="material-symbols-outlined">group</span>
                    <span class="font-label-md text-label-md">Gestion Vendeuses</span>
                </a>
            </li>
            <li>
                <a class="flex items-center space-x-sm px-md py-sm rounded-lg {{ request()->routeIs('medications.*') ? 'text-primary font-bold border-r-4 border-primary bg-surface-container' : 'text-on-surface-variant hover:bg-surface-container' }} transition-all scale-95 duration-75" href="{{ route('medications.index') }}">
                    <span class="material-symbols-outlined">medical_services</span>
                    <span class="font-label-md text-label-md">Médicaments</span>
                </a>
            </li>
            <li>
                <a class="flex items-center space-x-sm px-md py-sm rounded-lg {{ request()->routeIs('stock.*') ? 'text-primary font-bold border-r-4 border-primary bg-surface-container' : 'text-on-surface-variant hover:bg-surface-container' }} transition-all scale-95 duration-75" href="{{ route('stock.index') }}">
                    <span class="material-symbols-outlined">inventory_2</span>
                    <span class="font-label-md text-label-md">Stock</span>
                </a>
            </li>
            <li>
                <a class="flex items-center space-x-sm px-md py-sm rounded-lg {{ request()->routeIs('entries.*') ? 'text-primary font-bold border-r-4 border-primary bg-surface-container' : 'text-on-surface-variant hover:bg-surface-container' }} transition-all scale-95 duration-75" href="{{ route('entries.index') }}">
                    <span class="material-symbols-outlined">login</span>
                    <span class="font-label-md text-label-md">Entrées</span>
                </a>
            </li>
            <li>
                <a class="flex items-center space-x-sm px-md py-sm rounded-lg {{ request()->routeIs('exits.*') ? 'text-primary font-bold border-r-4 border-primary bg-surface-container' : 'text-on-surface-variant hover:bg-surface-container' }} transition-all scale-95 duration-75" href="{{ route('exits.index') }}">
                    <span class="material-symbols-outlined">logout</span>
                    <span class="font-label-md text-label-md">Sorties</span>
                </a>
            </li>
            <li>
                <a class="flex items-center space-x-sm px-md py-sm rounded-lg {{ request()->routeIs('inventories.*') ? 'text-primary font-bold border-r-4 border-primary bg-surface-container' : 'text-on-surface-variant hover:bg-surface-container' }} transition-all scale-95 duration-75" href="{{ route('inventories.index') }}">
                    <span class="material-symbols-outlined">assignment</span>
                    <span class="font-label-md text-label-md">Inventaires</span>
                </a>
            </li>
            <li>
                <a class="flex items-center space-x-sm px-md py-sm rounded-lg {{ request()->routeIs('traceability.*') ? 'text-primary font-bold border-r-4 border-primary bg-surface-container' : 'text-on-surface-variant hover:bg-surface-container' }} transition-all scale-95 duration-75" href="{{ route('traceability.index') }}">
                    <span class="material-symbols-outlined">history</span>
                    <span class="font-label-md text-label-md">Traçabilité</span>
                </a>
            </li>
            <li>
                <a class="flex items-center space-x-sm px-md py-sm rounded-lg {{ request()->routeIs('prices.*') ? 'text-primary font-bold border-r-4 border-primary bg-surface-container' : 'text-on-surface-variant hover:bg-surface-container' }} transition-all scale-95 duration-75" href="{{ route('prices.index') }}">
                    <span class="material-symbols-outlined">payments</span>
                    <span class="font-label-md text-label-md">Prix</span>
                </a>
            </li>
            <li>
                <a class="flex items-center space-x-sm px-md py-sm rounded-lg {{ request()->routeIs('reports.*') ? 'text-primary font-bold border-r-4 border-primary bg-surface-container' : 'text-on-surface-variant hover:bg-surface-container' }} transition-all scale-95 duration-75" href="{{ route('reports.index') }}">
                    <span class="material-symbols-outlined">assessment</span>
                    <span class="font-label-md text-label-md">Rapports</span>
                </a>
            </li>
            <li>
                <a class="flex items-center space-x-sm px-md py-sm rounded-lg {{ request()->routeIs('alerts.*') ? 'text-primary font-bold border-r-4 border-primary bg-surface-container' : 'text-on-surface-variant hover:bg-surface-container' }} transition-all scale-95 duration-75" href="{{ route('alerts.index') }}">
                    <span class="material-symbols-outlined">notification_important</span>
                    <span class="font-label-md text-label-md">Alertes</span>
                </a>
            </li>
            <li>
                <a class="flex items-center space-x-sm px-md py-sm rounded-lg {{ request()->routeIs('users.*') ? 'text-primary font-bold border-r-4 border-primary bg-surface-container' : 'text-on-surface-variant hover:bg-surface-container' }} transition-all scale-95 duration-75" href="{{ route('users.index') }}">
                    <span class="material-symbols-outlined">group</span>
                    <span class="font-label-md text-label-md">Utilisateurs</span>
                </a>
            </li>
            <li>
                <a class="flex items-center space-x-sm px-md py-sm rounded-lg {{ request()->routeIs('settings.*') ? 'text-primary font-bold border-r-4 border-primary bg-surface-container' : 'text-on-surface-variant hover:bg-surface-container' }} transition-all scale-95 duration-75" href="{{ route('settings.index') }}">
                    <span class="material-symbols-outlined">settings</span>
                    <span class="font-label-md text-label-md">Paramètres</span>
                </a>
            </li>
            <li class="pt-md border-t border-outline-variant">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center space-x-sm px-md py-sm rounded-lg text-error hover:bg-error-container/20 transition-all text-left font-label-md text-label-md cursor-pointer">
                        <span class="material-symbols-outlined">logout</span>
                        <span>Déconnexion</span>
                    </button>
                </form>
            </li>
        </ul>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 lg:ml-64 flex flex-col min-h-screen bg-surface-bright">
        <!-- Mobile/Tablet Header with hamburger -->
        <div class="flex lg:hidden justify-between items-center px-md py-sm bg-surface-lowest border-b border-outline-variant sticky top-0 z-30">
            <div class="flex items-center gap-sm">
                <button onclick="openSidebar()" class="p-sm rounded-lg text-on-surface hover:bg-surface-container transition-colors">
                    <span class="material-symbols-outlined text-2xl">menu</span>
                </button>
                <div class="flex items-center gap-xs">
                    <div class="w-8 h-8 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-bold text-xs">
                        <span class="material-symbols-outlined text-sm" data-weight="fill">local_pharmacy</span>
                    </div>
                    <span class="font-headline-sm text-headline-sm font-bold text-on-surface text-base">PharmaGestion</span>
                </div>
            </div>
            <div class="flex items-center gap-sm">
                <div class="w-9 h-9 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-bold text-xs">
                    {{ substr(auth()->user()->name ?? 'P', 0, 1) }}
                </div>
            </div>
        </div>

        <!-- Desktop Header area -->
        <div class="hidden lg:flex justify-between items-center px-lg py-md bg-surface-lowest border-b border-outline-variant sticky top-0 z-30">
            <h2 class="font-headline-md text-headline-md text-on-surface font-bold">@yield('page-title', 'Vue d\'ensemble')</h2>
            <div class="flex items-center space-x-md">
                <div class="flex items-center gap-sm">
                    <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-bold text-sm">
                        {{ substr(auth()->user()->name ?? 'P', 0, 1) }}
                    </div>
                    <div class="flex flex-col">
                        <span class="font-label-md text-label-md font-bold text-on-surface">{{ auth()->user()->name ?? 'Dr. Diallo' }}</span>
                        <span class="font-body-sm text-body-sm text-on-surface-variant text-xs capitalize">{{ auth()->user()->role ?? 'Admin' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-md lg:p-xl space-y-xl max-w-7xl mx-auto w-full">
            {{-- Welcome banner after login --}}
            @if(session('welcome'))
                <div id="welcomeBanner" class="welcome-banner p-md rounded-xl bg-gradient-to-r from-primary-container to-primary text-on-primary flex items-center gap-md shadow-lg">
                    <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-3xl text-white">waving_hand</span>
                    </div>
                    <div class="flex-1">
                        <p class="font-headline-sm text-headline-sm font-bold text-white">{{ session('welcome') }}</p>
                        <p class="font-body-sm text-body-sm text-white/80 mt-1">Votre session est active. Bonne gestion !</p>
                    </div>
                    <button onclick="dismissWelcome()" class="p-sm rounded-lg text-white/70 hover:text-white hover:bg-white/10 transition-colors shrink-0">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
            @endif

            @if(session('success'))
                <div class="p-md rounded-lg bg-primary-container/20 border border-primary text-primary flex items-center gap-sm font-bold">
                    <span class="material-symbols-outlined">check_circle</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

<script>
    // Mobile/Tablet sidebar toggle
    function openSidebar() {
        const sidebar = document.getElementById('mobileSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.remove('sidebar-closed');
        sidebar.classList.add('sidebar-open');
        overlay.classList.remove('opacity-0', 'pointer-events-none');
        overlay.classList.add('opacity-100');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        const sidebar = document.getElementById('mobileSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.remove('sidebar-open');
        sidebar.classList.add('sidebar-closed');
        overlay.classList.remove('opacity-100');
        overlay.classList.add('opacity-0', 'pointer-events-none');
        document.body.style.overflow = '';
    }

    // Auto-dismiss welcome banner after 6 seconds
    function dismissWelcome() {
        const banner = document.getElementById('welcomeBanner');
        if (banner) {
            banner.classList.add('hiding');
            setTimeout(() => banner.remove(), 500);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const banner = document.getElementById('welcomeBanner');
        if (banner) {
            setTimeout(() => dismissWelcome(), 6000);
        }
    });
</script>
</body>
</html>
