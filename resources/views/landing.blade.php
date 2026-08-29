<!DOCTYPE html>
<html class="scroll-smooth" lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>PharmaGestion - Gestion de Pharmacie pour Postes de Santé</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

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
        .icon-filled {
            font-variation-settings: 'FILL' 1;
        }
    </style>
</head>
<body class="bg-background text-on-background font-body-md antialiased selection:bg-primary-container selection:text-on-primary-container">

<!-- Top Navigation -->
<nav class="bg-surface-container-lowest border-b border-outline-variant flex justify-between items-center w-full px-lg h-16 sticky top-0 z-40 shadow-sm">
    <div class="flex items-center gap-md">
        <span class="font-headline-md text-headline-md text-primary font-bold">PharmaGestion</span>
    </div>
    <div class="hidden md:flex items-center gap-lg">
        <a class="font-label-md text-label-md text-on-surface-variant hover:text-primary transition-colors" href="#features">Fonctionnalités</a>
        <a class="font-label-md text-label-md text-on-surface-variant hover:text-primary transition-colors" href="#benefits">Avantages</a>
    </div>
    <div class="flex items-center gap-md">
        <a href="{{ route('login') }}" class="hidden md:flex items-center justify-center px-md py-sm font-label-md text-label-md text-primary border border-primary rounded hover:bg-surface-container-low transition-colors">
            Voir la démo
        </a>
        <a href="{{ route('login') }}" class="flex items-center justify-center px-md py-sm font-label-md text-label-md bg-primary text-on-primary rounded hover:opacity-90 transition-opacity">
            Créer un compte
        </a>
    </div>
</nav>

<main>
    <!-- Hero Section -->
    <section class="relative pt-xl pb-32 px-md overflow-hidden flex flex-col md:flex-row items-center justify-center min-h-[80vh] gap-xl max-w-7xl mx-auto">
        <div class="w-full md:w-1/2 flex flex-col items-start gap-lg z-10">
            <div class="inline-flex items-center gap-sm px-sm py-xs bg-surface-container-high rounded-full border border-surface-dim">
                <span class="material-symbols-outlined text-[16px] text-secondary">verified</span>
                <span class="font-label-md text-label-md text-on-surface">Conçu pour le Sénégal</span>
            </div>
            <h1 class="font-display-lg text-display-lg md:text-[48px] md:leading-[56px] text-on-surface max-w-2xl font-bold">
                La gestion de pharmacie, <br/><span class="text-primary">simplifiée et sécurisée.</span>
            </h1>
            <p class="font-body-md text-body-md text-on-surface-variant max-w-xl">
                PharmaGestion est la solution moderne dédiée aux postes de santé. Gérez votre stock, suivez la traçabilité de vos médicaments et recevez des alertes de péremption en temps réel pour garantir la santé de vos patients.
            </p>
            <div class="flex flex-col sm:flex-row items-center gap-md w-full sm:w-auto mt-sm">
                <a href="{{ route('login') }}" class="w-full sm:w-auto flex items-center justify-center gap-sm px-lg py-md font-label-md text-label-md bg-primary text-on-primary rounded hover:opacity-90 transition-opacity shadow-sm">
                    Créer un compte gratuitement
                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </a>
                <a href="{{ route('login') }}" class="w-full sm:w-auto flex items-center justify-center gap-sm px-lg py-md font-label-md text-label-md text-on-surface border border-outline-variant bg-surface-container-lowest rounded hover:bg-surface-container-low transition-colors">
                    <span class="material-symbols-outlined text-[18px] text-secondary">play_circle</span>
                    Voir la démo
                </a>
            </div>
            <div class="flex items-center gap-md mt-lg text-on-surface-variant">
                <div class="flex items-center gap-xs">
                    <span class="material-symbols-outlined text-[20px] text-primary">check_circle</span>
                    <span class="font-body-sm text-body-sm">Installation rapide</span>
                </div>
                <div class="flex items-center gap-xs">
                    <span class="material-symbols-outlined text-[20px] text-primary">check_circle</span>
                    <span class="font-body-sm text-body-sm">Support local</span>
                </div>
            </div>
        </div>

        <div class="w-full md:w-1/2 relative z-10 flex justify-center">
            <!-- Decorative Medical UI Element (Bento-style visualization) -->
            <div class="relative w-full max-w-md aspect-square bg-surface-container-lowest rounded-xl shadow-lg border border-outline-variant p-lg flex flex-col gap-md">
                <!-- Dashboard Mock Header -->
                <div class="flex justify-between items-center pb-sm border-b border-surface-dim">
                    <span class="font-headline-sm text-headline-sm text-on-surface">Vue d'ensemble</span>
                    <span class="material-symbols-outlined text-outline">more_horiz</span>
                </div>
                <!-- Metric Cards Grid -->
                <div class="grid grid-cols-2 gap-md">
                    <div class="bg-surface-container-low p-md rounded-lg flex flex-col gap-xs border border-surface-dim">
                        <div class="flex items-center gap-sm text-secondary">
                            <span class="material-symbols-outlined text-[20px] icon-filled">inventory_2</span>
                            <span class="font-label-md text-label-md">Stock Total</span>
                        </div>
                        <span class="font-headline-md text-headline-md text-on-surface font-bold">1,245</span>
                    </div>
                    <div class="bg-error-container p-md rounded-lg flex flex-col gap-xs border border-red-200">
                        <div class="flex items-center gap-sm text-on-error-container">
                            <span class="material-symbols-outlined text-[20px] icon-filled">warning</span>
                            <span class="font-label-md text-label-md">Périmés</span>
                        </div>
                        <span class="font-headline-md text-headline-md text-on-error-container font-bold">3</span>
                    </div>
                </div>
                <!-- List Mock -->
                <div class="flex flex-col gap-sm mt-sm">
                    <span class="font-label-md text-label-md text-on-surface-variant">Dernières sorties</span>
                    <div class="flex justify-between items-center p-sm bg-surface-container-lowest rounded border border-outline-variant">
                        <div class="flex items-center gap-sm">
                            <div class="w-8 h-8 rounded bg-primary-container text-on-primary-container flex items-center justify-center">
                                <span class="material-symbols-outlined text-[16px]">medication</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-body-md text-body-md text-on-surface leading-tight font-bold">Paracétamol 500mg</span>
                                <span class="font-body-sm text-body-sm text-on-surface-variant">Boîte de 10</span>
                            </div>
                        </div>
                        <span class="font-currency-md text-currency-md text-on-surface">-2</span>
                    </div>
                    <div class="flex justify-between items-center p-sm bg-surface-container-lowest rounded border border-outline-variant">
                        <div class="flex items-center gap-sm">
                            <div class="w-8 h-8 rounded bg-surface-variant text-on-surface-variant flex items-center justify-center">
                                <span class="material-symbols-outlined text-[16px]">vaccines</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-body-md text-body-md text-on-surface leading-tight font-bold">Amoxicilline 1g</span>
                                <span class="font-body-sm text-body-sm text-on-surface-variant">Sirop</span>
                            </div>
                        </div>
                        <span class="font-currency-md text-currency-md text-on-surface">-5</span>
                    </div>
                </div>
                <!-- Decorative overlay gradient -->
                <div class="absolute inset-0 bg-gradient-to-tr from-primary/5 to-secondary/5 rounded-xl pointer-events-none"></div>
            </div>
            <!-- Background decorative shapes -->
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] h-[120%] bg-surface-container-high rounded-full blur-3xl opacity-50 -z-10"></div>
        </div>
    </section>

    <!-- Features Section (Bento Grid) -->
    <section class="py-xl px-md max-w-7xl mx-auto bg-surface-container-lowest" id="features">
        <div class="text-center mb-xl">
            <h2 class="font-display-lg text-display-lg text-on-surface mb-sm font-bold">Tout ce dont vous avez besoin</h2>
            <p class="font-body-md text-body-md text-on-surface-variant max-w-2xl mx-auto">Une interface clinique, épurée et pensée pour l'efficacité dans les environnements médicaux exigeants.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-md">
            <!-- Feature 1: Real-time stock (Spans 2 cols on md) -->
            <div class="md:col-span-2 bg-surface rounded-xl border border-outline-variant p-lg flex flex-col justify-between overflow-hidden relative group hover:border-primary-container transition-colors">
                <div class="relative z-10 flex flex-col gap-sm max-w-md">
                    <div class="w-12 h-12 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center mb-xs">
                        <span class="material-symbols-outlined icon-filled text-[24px]">inventory</span>
                    </div>
                    <h3 class="font-headline-md text-headline-md text-on-surface font-bold">Stock en Temps Réel</h3>
                    <p class="font-body-md text-body-md text-on-surface-variant">
                        Visualisez instantanément vos niveaux de stock. L'interface claire réduit la fatigue visuelle et minimise les erreurs de saisie lors des inventaires.
                    </p>
                </div>
                <!-- Decorative visual -->
                <div class="mt-lg md:mt-0 md:absolute md:right-lg md:bottom-lg md:w-1/3 bg-surface-container-lowest border border-outline-variant rounded-lg p-sm shadow-sm flex flex-col gap-xs z-10">
                    <div class="flex justify-between items-center pb-xs border-b border-surface-dim">
                        <span class="font-label-md text-label-md text-on-surface">Niveau Critique</span>
                    </div>
                    <div class="flex items-center gap-sm py-xs">
                        <div class="w-full bg-surface-dim h-2 rounded-full overflow-hidden">
                            <div class="bg-primary w-[15%] h-full rounded-full"></div>
                        </div>
                        <span class="font-currency-md text-currency-md text-on-surface">15%</span>
                    </div>
                </div>
                <div class="absolute inset-0 bg-gradient-to-br from-transparent to-primary/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </div>
            <!-- Feature 2: Traceability -->
            <div class="bg-surface rounded-xl border border-outline-variant p-lg flex flex-col gap-sm relative group hover:border-secondary transition-colors">
                <div class="w-12 h-12 rounded-full bg-secondary-container text-on-secondary-container flex items-center justify-center mb-xs">
                    <span class="material-symbols-outlined icon-filled text-[24px]">history</span>
                </div>
                <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold">Traçabilité Complète</h3>
                <p class="font-body-md text-body-md text-on-surface-variant">
                    Suivez chaque entrée et sortie. Historique détaillé par utilisateur et par médicament pour une transparence totale.
                </p>
            </div>
            <!-- Feature 3: Expiration Alerts -->
            <div class="bg-surface rounded-xl border border-outline-variant p-lg flex flex-col gap-sm relative group hover:border-error transition-colors">
                <div class="w-12 h-12 rounded-full bg-error-container text-on-error-container flex items-center justify-center mb-xs">
                    <span class="material-symbols-outlined icon-filled text-[24px]">event_busy</span>
                </div>
                <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold">Alertes de Péremption</h3>
                <p class="font-body-md text-body-md text-on-surface-variant">
                    Anticipez les pertes. Le système identifie et met en évidence les lots approchant de leur date d'expiration.
                </p>
            </div>
            <!-- Feature 4: Reports (Spans 2 cols on md) -->
            <div class="md:col-span-2 bg-surface rounded-xl border border-outline-variant p-lg flex flex-col sm:flex-row gap-lg items-center relative group hover:border-tertiary transition-colors">
                <div class="flex-1 flex flex-col gap-sm">
                    <div class="w-12 h-12 rounded-full bg-tertiary-container text-on-tertiary-container flex items-center justify-center mb-xs">
                        <span class="material-symbols-outlined icon-filled text-[24px]">bar_chart</span>
                    </div>
                    <h3 class="font-headline-md text-headline-md text-on-surface font-bold">Rapports Automatisés</h3>
                    <p class="font-body-md text-body-md text-on-surface-variant">
                        Générez des rapports d'activité d'un simple clic. Exportez vos données pour faciliter la comptabilité et la commande de réassort.
                    </p>
                </div>
                <!-- Decorative Chart Mockup -->
                <div class="w-full sm:w-1/2 flex items-end gap-xs h-32 px-md pt-lg pb-sm bg-surface-container-lowest border border-outline-variant rounded-lg shadow-sm">
                    <div class="flex-1 bg-surface-dim rounded-t h-[40%]"></div>
                    <div class="flex-1 bg-tertiary-container rounded-t h-[70%]"></div>
                    <div class="flex-1 bg-primary rounded-t h-[100%]"></div>
                    <div class="flex-1 bg-secondary rounded-t h-[60%]"></div>
                    <div class="flex-1 bg-surface-dim rounded-t h-[30%]"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-xl px-md mt-lg mb-xl max-w-4xl mx-auto text-center bg-surface-container-high rounded-2xl border border-surface-dim">
        <h2 class="font-display-lg text-display-lg text-on-surface mb-md font-bold">Prêt à moderniser votre poste de santé ?</h2>
        <p class="font-body-md text-body-md text-on-surface-variant mb-lg max-w-2xl mx-auto">
            Rejoignez les pharmacies qui utilisent PharmaGestion pour optimiser leur quotidien et sécuriser la distribution de médicaments.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-md">
            <a href="{{ route('login') }}" class="w-full sm:w-auto px-xl py-md font-label-md text-label-md bg-primary text-on-primary rounded hover:opacity-90 transition-opacity shadow-sm font-bold">
                Créer un compte maintenant
            </a>
        </div>
    </section>
</main>

<!-- Simple Footer -->
<footer class="bg-surface-container-lowest border-t border-outline-variant py-lg px-md text-center">
    <p class="font-body-sm text-body-sm text-on-surface-variant">
        © 2026 PharmaGestion. Conçu pour le Sénégal.
    </p>
</footer>

</body>
</html>
