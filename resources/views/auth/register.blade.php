<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>PharmaGestion - Inscription Administrateur</title>

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
    </style>
</head>
<body class="bg-background text-on-background font-body-md min-h-screen flex items-center justify-center p-md lg:p-xl">
<div class="w-full max-w-[1024px] grid grid-cols-1 lg:grid-cols-2 gap-lg bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant overflow-hidden">
    <!-- Illustration/Branding Side -->
    <div class="hidden lg:flex flex-col justify-between bg-primary-container p-xl relative">
        <div class="z-10">
            <div class="flex items-center gap-sm mb-lg">
                <span class="material-symbols-outlined text-on-primary-container text-[32px]">medical_services</span>
                <span class="font-headline-md text-headline-md text-on-primary-container font-bold">PharmaGestion</span>
            </div>
            <h1 class="font-display-lg text-display-lg text-on-primary-container mb-sm font-bold">Moderniser<br/>votre gestion.</h1>
            <p class="font-body-md text-body-md text-on-primary-container opacity-90 max-w-[80%]">Rejoignez le réseau des postes de santé équipés pour une gestion efficace et sécurisée de leur stock.</p>
        </div>
        <div class="absolute inset-0 bg-gradient-to-br from-primary-container to-surface-tint opacity-80 mix-blend-multiply pointer-events-none"></div>
        <div class="z-10 mt-auto">
            <div class="flex items-center gap-sm text-on-primary-container">
                <span class="material-symbols-outlined text-on-primary-container">verified_user</span>
                <span class="font-label-md text-label-md">Système Sécurisé & Certifié</span>
            </div>
        </div>
    </div>

    <!-- Form Side -->
    <div class="p-lg lg:p-xl flex flex-col justify-center">
        <!-- Progress Indicator -->
        <div class="flex items-center gap-sm mb-lg">
            <div class="h-2 flex-1 bg-primary rounded-full"></div>
            <div class="h-2 flex-1 bg-surface-variant rounded-full"></div>
            <div class="h-2 flex-1 bg-surface-variant rounded-full"></div>
            <span class="font-label-md text-label-md text-outline ml-sm">Étape 1/3</span>
        </div>

        <h2 class="font-headline-sm text-headline-sm text-on-surface mb-xs font-bold">Créer un compte Administrateur</h2>
        <p class="font-body-sm text-body-sm text-on-surface-variant mb-xl">Veuillez renseigner vos informations personnelles pour configurer votre accès.</p>

        @if ($errors->any())
            <div class="mb-md p-md rounded bg-error-container text-on-error-container font-body-sm">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="space-y-md">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                <!-- Prénom -->
                <div class="flex flex-col gap-xs">
                    <label class="font-label-md text-label-md text-on-surface" for="firstName">Prénom</label>
                    <input class="bg-surface-container-lowest border border-outline-variant rounded px-md py-sm font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors" id="firstName" name="firstName" value="{{ old('firstName', 'Jean') }}" placeholder="Jean" required type="text"/>
                </div>
                <!-- Nom -->
                <div class="flex flex-col gap-xs">
                    <label class="font-label-md text-label-md text-on-surface" for="lastName">Nom</label>
                    <input class="bg-surface-container-lowest border border-outline-variant rounded px-md py-sm font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors" id="lastName" name="lastName" value="{{ old('lastName', 'Dupont') }}" placeholder="Dupont" required type="text"/>
                </div>
            </div>

            <!-- Email -->
            <div class="flex flex-col gap-xs">
                <label class="font-label-md text-label-md text-on-surface" for="email">Adresse Email</label>
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-md text-outline-variant">mail</span>
                    <input class="w-full bg-surface-container-lowest border border-outline-variant rounded pl-[44px] pr-md py-sm font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors" id="email" name="email" value="{{ old('email', 'jean.dupont@pharmacie.sn') }}" placeholder="jean.dupont@pharmacie.sn" required type="email"/>
                </div>
            </div>

            <!-- Téléphone -->
            <div class="flex flex-col gap-xs">
                <label class="font-label-md text-label-md text-on-surface" for="phone">Numéro de Téléphone</label>
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-md text-outline-variant">phone</span>
                    <input class="w-full bg-surface-container-lowest border border-outline-variant rounded pl-[44px] pr-md py-sm font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors" id="phone" name="phone" value="{{ old('phone', '+221 77 123 45 67') }}" placeholder="+221 77 123 45 67" required type="tel"/>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                <!-- Mot de passe -->
                <div class="flex flex-col gap-xs">
                    <label class="font-label-md text-label-md text-on-surface" for="password">Mot de passe</label>
                    <div class="relative flex items-center">
                        <span class="material-symbols-outlined absolute left-md text-outline-variant">lock</span>
                        <input class="w-full bg-surface-container-lowest border border-outline-variant rounded pl-[44px] pr-[44px] py-sm font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors" id="password" name="password" value="Password123!" placeholder="••••••••" required type="password"/>
                        <button aria-label="Toggle password visibility" class="absolute right-md text-outline-variant hover:text-on-surface-variant transition-colors" type="button">
                            <span class="material-symbols-outlined">visibility_off</span>
                        </button>
                    </div>
                </div>
                <!-- Confirmer Mot de passe -->
                <div class="flex flex-col gap-xs">
                    <label class="font-label-md text-label-md text-on-surface" for="password_confirmation">Confirmer mot de passe</label>
                    <div class="relative flex items-center">
                        <span class="material-symbols-outlined absolute left-md text-outline-variant">lock</span>
                        <input class="w-full bg-surface-container-lowest border border-outline-variant rounded pl-[44px] pr-[44px] py-sm font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors" id="password_confirmation" name="password_confirmation" value="Password123!" placeholder="••••••••" required type="password"/>
                    </div>
                </div>
            </div>

            <!-- Password Requirements -->
            <div class="bg-surface rounded p-sm border border-outline-variant mt-sm">
                <p class="font-label-md text-label-md text-on-surface-variant mb-xs">Le mot de passe doit contenir :</p>
                <ul class="font-body-sm text-body-sm text-outline space-y-[2px]">
                    <li class="flex items-center gap-[6px]">
                        <span class="material-symbols-outlined text-[16px] text-outline">circle</span>
                        Au moins 8 caractères
                    </li>
                    <li class="flex items-center gap-[6px]">
                        <span class="material-symbols-outlined text-[16px] text-outline">circle</span>
                        Une lettre majuscule
                    </li>
                    <li class="flex items-center gap-[6px]">
                        <span class="material-symbols-outlined text-[16px] text-outline">circle</span>
                        Un caractère spécial (!@#$%^&*)
                    </li>
                </ul>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between gap-sm mt-xl pt-md border-t border-surface-container-highest">
                <a href="{{ route('login') }}" class="font-label-md text-label-md text-primary hover:underline">Déjà un compte ? Se connecter</a>
                <button class="bg-primary text-on-primary font-label-md text-label-md px-lg py-sm rounded hover:bg-surface-tint shadow-sm transition-all flex items-center gap-xs font-bold" type="submit">
                    Continuer
                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
