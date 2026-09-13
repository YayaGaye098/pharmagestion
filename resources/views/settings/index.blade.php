@extends('layouts.app')

@section('title', 'PharmaGestion - Paramètres')
@section('page-title', 'Paramètres de l\'Etablissement')

@section('content')
<div class="mb-lg">
    <h2 class="font-headline-md text-headline-md text-on-surface font-bold">Configuration du Poste de Santé</h2>
    <p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">Gérez les informations, l'apparence et la sécurité de l'application.</p>
</div>

{{-- Tabs via Alpine.js or vanilla JS --}}
<div id="settingsTabs">
    {{-- Tab Nav --}}
    <div class="flex gap-xs border-b border-outline-variant mb-xl overflow-x-auto">
        <button onclick="showTab('general')" id="tab-btn-general"
            class="settings-tab-btn active flex items-center gap-xs px-md py-sm font-label-md text-label-md whitespace-nowrap transition-colors border-b-2 border-primary text-primary font-bold">
            <span class="material-symbols-outlined text-lg">business</span> Général
        </button>
        <button onclick="showTab('appearance')" id="tab-btn-appearance"
            class="settings-tab-btn flex items-center gap-xs px-md py-sm font-label-md text-label-md whitespace-nowrap transition-colors border-b-2 border-transparent text-on-surface-variant hover:text-on-surface">
            <span class="material-symbols-outlined text-lg">palette</span> Apparence
        </button>
        <button onclick="showTab('notifications')" id="tab-btn-notifications"
            class="settings-tab-btn flex items-center gap-xs px-md py-sm font-label-md text-label-md whitespace-nowrap transition-colors border-b-2 border-transparent text-on-surface-variant hover:text-on-surface">
            <span class="material-symbols-outlined text-lg">notifications</span> Notifications
        </button>
        <button onclick="showTab('security')" id="tab-btn-security"
            class="settings-tab-btn flex items-center gap-xs px-md py-sm font-label-md text-label-md whitespace-nowrap transition-colors border-b-2 border-transparent text-on-surface-variant hover:text-on-surface">
            <span class="material-symbols-outlined text-lg">lock</span> Sécurité
        </button>
    </div>

    {{-- ── TAB 1 : Général ── --}}
    <div id="tab-general" class="settings-tab-panel">
        <div class="card-level-1 rounded-xl p-xl max-w-2xl">
            <div class="flex items-center gap-sm mb-lg">
                <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center">
                    <span class="material-symbols-outlined">business</span>
                </div>
                <div>
                    <h3 class="font-title-lg text-title-lg text-on-surface font-bold">Informations de l'Établissement</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Nom, région et coordonnées de votre structure.</p>
                </div>
            </div>
            <form action="{{ route('settings.update') }}" method="POST" class="space-y-md">
                @csrf
                <div>
                    <label class="font-label-md text-label-md text-on-surface block mb-xs">Nom du Poste de Santé / Pharmacie</label>
                    <input name="facility_name" value="{{ $settings['facility_name'] }}"
                        class="w-full border border-outline-variant rounded-lg px-md py-sm font-body-sm font-bold text-on-surface bg-surface focus:outline-none focus:ring-2 focus:ring-primary" type="text"/>
                </div>
                <div>
                    <label class="font-label-md text-label-md text-on-surface block mb-xs">Région / District Médical</label>
                    <input name="region" value="{{ $settings['region'] }}"
                        class="w-full border border-outline-variant rounded-lg px-md py-sm font-body-sm text-on-surface bg-surface focus:outline-none focus:ring-2 focus:ring-primary" type="text"/>
                </div>
                <div>
                    <label class="font-label-md text-label-md text-on-surface block mb-xs">Devise de Gestion</label>
                    <input name="currency" value="{{ $settings['currency'] }}" readonly
                        class="w-full border border-outline-variant rounded-lg px-md py-sm font-body-sm bg-surface-container-low font-bold cursor-not-allowed" type="text"/>
                    <p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">La devise ne peut pas être modifiée après la configuration initiale.</p>
                </div>
                <div>
                    <label class="font-label-md text-label-md text-on-surface block mb-xs">Téléphone</label>
                    <input name="phone" value="{{ $settings['phone'] ?? '' }}"
                        class="w-full border border-outline-variant rounded-lg px-md py-sm font-body-sm text-on-surface bg-surface focus:outline-none focus:ring-2 focus:ring-primary" type="tel" placeholder="+221 77 000 00 00"/>
                </div>
                <div>
                    <label class="font-label-md text-label-md text-on-surface block mb-xs">Adresse physique</label>
                    <textarea name="address" rows="2"
                        class="w-full border border-outline-variant rounded-lg px-md py-sm font-body-sm text-on-surface bg-surface focus:outline-none focus:ring-2 focus:ring-primary resize-none">{{ $settings['address'] ?? '' }}</textarea>
                </div>
                <div class="pt-md">
                    <button type="submit" class="flex items-center gap-xs bg-primary text-on-primary font-bold px-xl py-sm rounded-lg shadow-sm hover:bg-surface-tint transition-colors">
                        <span class="material-symbols-outlined">save</span>
                        Enregistrer les Paramètres
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── TAB 2 : Apparence ── --}}
    <div id="tab-appearance" class="settings-tab-panel hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-lg max-w-4xl">
            {{-- Dark / Light mode card --}}
            <div class="card-level-1 rounded-xl p-xl">
                <div class="flex items-center gap-sm mb-lg">
                    <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center">
                        <span class="material-symbols-outlined">brightness_6</span>
                    </div>
                    <div>
                        <h3 class="font-title-lg text-title-lg text-on-surface font-bold">Mode d'affichage</h3>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">Choisissez votre thème préféré.</p>
                    </div>
                </div>
                <div class="space-y-sm">
                    <button onclick="setTheme('light')" id="btn-theme-light"
                        class="w-full flex items-center gap-sm px-md py-sm rounded-lg border-2 border-outline-variant hover:border-primary transition-all text-left">
                        <span class="material-symbols-outlined text-xl" style="color:#f59e0b">light_mode</span>
                        <div>
                            <div class="font-label-md text-label-md text-on-surface font-bold">Mode Clair</div>
                            <div class="font-body-sm text-body-sm text-on-surface-variant">Interface lumineuse</div>
                        </div>
                    </button>
                    <button onclick="setTheme('dark')" id="btn-theme-dark"
                        class="w-full flex items-center gap-sm px-md py-sm rounded-lg border-2 border-outline-variant hover:border-primary transition-all text-left">
                        <span class="material-symbols-outlined text-xl" style="color:#818cf8">dark_mode</span>
                        <div>
                            <div class="font-label-md text-label-md text-on-surface font-bold">Mode Sombre</div>
                            <div class="font-body-sm text-body-sm text-on-surface-variant">Interface sombre, reposante</div>
                        </div>
                    </button>
                    <button onclick="setTheme('system')" id="btn-theme-system"
                        class="w-full flex items-center gap-sm px-md py-sm rounded-lg border-2 border-outline-variant hover:border-primary transition-all text-left">
                        <span class="material-symbols-outlined text-xl text-primary">computer</span>
                        <div>
                            <div class="font-label-md text-label-md text-on-surface font-bold">Automatique</div>
                            <div class="font-body-sm text-body-sm text-on-surface-variant">Suit les préférences du système</div>
                        </div>
                    </button>
                </div>
            </div>

            {{-- Sidebar card --}}
            <div class="card-level-1 rounded-xl p-xl">
                <div class="flex items-center gap-sm mb-lg">
                    <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center">
                        <span class="material-symbols-outlined">view_sidebar</span>
                    </div>
                    <div>
                        <h3 class="font-title-lg text-title-lg text-on-surface font-bold">Menu latéral</h3>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">Comportement du panneau de navigation.</p>
                    </div>
                </div>
                <div class="space-y-md">
                    <div class="flex items-center justify-between p-md rounded-lg bg-surface-container">
                        <div>
                            <div class="font-label-md text-label-md text-on-surface font-bold">Sidebar réduite par défaut</div>
                            <div class="font-body-sm text-body-sm text-on-surface-variant">Afficher uniquement les icônes</div>
                        </div>
                        <button onclick="toggleSidebarFromSettings()" id="sidebarToggleBtn" role="switch"
                            class="relative w-12 h-6 rounded-full bg-outline-variant transition-colors focus:outline-none focus:ring-2 focus:ring-primary">
                            <span class="absolute inset-y-1 left-1 w-4 h-4 rounded-full bg-white shadow transition-transform duration-300" id="sidebarToggleThumb"></span>
                        </button>
                    </div>
                    <p class="font-body-sm text-body-sm text-on-surface-variant px-xs">
                        Astuce : cliquez sur l'icône <strong class="font-bold">☰</strong> dans la barre du haut pour réduire ou étendre la sidebar à tout moment.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- ── TAB 3 : Notifications ── --}}
    <div id="tab-notifications" class="settings-tab-panel hidden">
        <div class="card-level-1 rounded-xl p-xl max-w-2xl">
            <div class="flex items-center gap-sm mb-lg">
                <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center">
                    <span class="material-symbols-outlined">notifications_active</span>
                </div>
                <div>
                    <h3 class="font-title-lg text-title-lg text-on-surface font-bold">Alertes & Notifications</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Configurez les destinataires et les seuils d'alerte.</p>
                </div>
            </div>
            <form action="{{ route('settings.update') }}" method="POST" class="space-y-md">
                @csrf
                <div>
                    <label class="font-label-md text-label-md text-on-surface block mb-xs">Email des notifications d'urgence</label>
                    <input name="alert_email" value="{{ $settings['alert_email'] }}"
                        class="w-full border border-outline-variant rounded-lg px-md py-sm font-body-sm text-on-surface bg-surface focus:outline-none focus:ring-2 focus:ring-primary" type="email" placeholder="admin@poste-sante.sn"/>
                </div>
                <div>
                    <label class="font-label-md text-label-md text-on-surface block mb-xs">Alerte avant expiration (jours)</label>
                    <input name="expiry_alert_days" value="{{ $settings['expiry_alert_days'] ?? 30 }}"
                        class="w-full border border-outline-variant rounded-lg px-md py-sm font-body-sm text-on-surface bg-surface focus:outline-none focus:ring-2 focus:ring-primary" type="number" min="1" max="365"/>
                </div>
                <div>
                    <label class="font-label-md text-label-md text-on-surface block mb-xs">Seuil de stock critique (quantité)</label>
                    <input name="low_stock_threshold" value="{{ $settings['low_stock_threshold'] ?? 10 }}"
                        class="w-full border border-outline-variant rounded-lg px-md py-sm font-body-sm text-on-surface bg-surface focus:outline-none focus:ring-2 focus:ring-primary" type="number" min="1"/>
                </div>
                <div class="space-y-sm pt-xs">
                    <label class="font-label-md text-label-md text-on-surface block font-bold">Activer les alertes</label>
                    <label class="flex items-center gap-sm cursor-pointer p-sm rounded-lg hover:bg-surface-container transition-colors">
                        <input type="checkbox" name="alert_expiry" value="1" {{ ($settings['alert_expiry'] ?? true) ? 'checked' : '' }}
                            class="w-4 h-4 rounded accent-primary"/>
                        <div>
                            <div class="font-label-md text-label-md text-on-surface">Alertes d'expiration des médicaments</div>
                            <div class="font-body-sm text-body-sm text-on-surface-variant">Notification quand un médicament va expirer</div>
                        </div>
                    </label>
                    <label class="flex items-center gap-sm cursor-pointer p-sm rounded-lg hover:bg-surface-container transition-colors">
                        <input type="checkbox" name="alert_low_stock" value="1" {{ ($settings['alert_low_stock'] ?? true) ? 'checked' : '' }}
                            class="w-4 h-4 rounded accent-primary"/>
                        <div>
                            <div class="font-label-md text-label-md text-on-surface">Alertes de stock bas</div>
                            <div class="font-body-sm text-body-sm text-on-surface-variant">Notification quand le stock atteint le seuil critique</div>
                        </div>
                    </label>
                    <label class="flex items-center gap-sm cursor-pointer p-sm rounded-lg hover:bg-surface-container transition-colors">
                        <input type="checkbox" name="alert_daily_report" value="1" {{ ($settings['alert_daily_report'] ?? false) ? 'checked' : '' }}
                            class="w-4 h-4 rounded accent-primary"/>
                        <div>
                            <div class="font-label-md text-label-md text-on-surface">Rapport quotidien par email</div>
                            <div class="font-body-sm text-body-sm text-on-surface-variant">Résumé envoyé chaque matin à 8h</div>
                        </div>
                    </label>
                </div>
                <div class="pt-md">
                    <button type="submit" class="flex items-center gap-xs bg-primary text-on-primary font-bold px-xl py-sm rounded-lg shadow-sm hover:bg-surface-tint transition-colors">
                        <span class="material-symbols-outlined">save</span>
                        Enregistrer les Notifications
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── TAB 4 : Sécurité ── --}}
    <div id="tab-security" class="settings-tab-panel hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-lg max-w-4xl">
            {{-- Change password --}}
            <div class="card-level-1 rounded-xl p-xl">
                <div class="flex items-center gap-sm mb-lg">
                    <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center">
                        <span class="material-symbols-outlined">password</span>
                    </div>
                    <div>
                        <h3 class="font-title-lg text-title-lg text-on-surface font-bold">Modifier le mot de passe</h3>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">Mettez à jour votre mot de passe régulièrement.</p>
                    </div>
                </div>
                <form action="{{ route('settings.update') }}" method="POST" class="space-y-md">
                    @csrf
                    <div>
                        <label class="font-label-md text-label-md text-on-surface block mb-xs">Mot de passe actuel</label>
                        <input name="current_password" type="password"
                            class="w-full border border-outline-variant rounded-lg px-md py-sm font-body-sm text-on-surface bg-surface focus:outline-none focus:ring-2 focus:ring-primary" placeholder="••••••••"/>
                    </div>
                    <div>
                        <label class="font-label-md text-label-md text-on-surface block mb-xs">Nouveau mot de passe</label>
                        <input name="new_password" type="password"
                            class="w-full border border-outline-variant rounded-lg px-md py-sm font-body-sm text-on-surface bg-surface focus:outline-none focus:ring-2 focus:ring-primary" placeholder="••••••••"/>
                    </div>
                    <div>
                        <label class="font-label-md text-label-md text-on-surface block mb-xs">Confirmer le nouveau mot de passe</label>
                        <input name="new_password_confirmation" type="password"
                            class="w-full border border-outline-variant rounded-lg px-md py-sm font-body-sm text-on-surface bg-surface focus:outline-none focus:ring-2 focus:ring-primary" placeholder="••••••••"/>
                    </div>
                    <div class="pt-xs">
                        <button type="submit" class="flex items-center gap-xs bg-primary text-on-primary font-bold px-lg py-sm rounded-lg shadow-sm hover:bg-surface-tint transition-colors">
                            <span class="material-symbols-outlined">lock_reset</span>
                            Changer le mot de passe
                        </button>
                    </div>
                </form>
            </div>

            {{-- Session info --}}
            <div class="card-level-1 rounded-xl p-xl space-y-lg">
                <div class="flex items-center gap-sm">
                    <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center">
                        <span class="material-symbols-outlined">shield</span>
                    </div>
                    <div>
                        <h3 class="font-title-lg text-title-lg text-on-surface font-bold">Session & Sécurité</h3>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">Informations et options de session.</p>
                    </div>
                </div>
                <div class="space-y-xs">
                    <div class="flex items-center justify-between py-sm border-b border-outline-variant">
                        <div class="flex items-center gap-xs text-on-surface-variant">
                            <span class="material-symbols-outlined text-lg">person</span>
                            <span class="font-body-sm text-body-sm">Utilisateur</span>
                        </div>
                        <span class="font-label-md text-label-md font-bold text-on-surface">{{ auth()->user()->name ?? 'Admin' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-sm border-b border-outline-variant">
                        <div class="flex items-center gap-xs text-on-surface-variant">
                            <span class="material-symbols-outlined text-lg">badge</span>
                            <span class="font-body-sm text-body-sm">Rôle</span>
                        </div>
                        <span class="font-label-md text-label-md font-bold text-primary capitalize">{{ auth()->user()->role ?? 'admin' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-sm border-b border-outline-variant">
                        <div class="flex items-center gap-xs text-on-surface-variant">
                            <span class="material-symbols-outlined text-lg">email</span>
                            <span class="font-body-sm text-body-sm">Email</span>
                        </div>
                        <span class="font-label-md text-label-md text-on-surface">{{ auth()->user()->email ?? '—' }}</span>
                    </div>
                </div>
                <div class="pt-xs">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center gap-xs w-full justify-center border-2 border-error text-error font-bold px-lg py-sm rounded-lg hover:bg-error/10 transition-colors">
                            <span class="material-symbols-outlined">logout</span>
                            Se déconnecter
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // ── Tabs ──────────────────────────────────────────────────────────────
    function showTab(tabId) {
        document.querySelectorAll('.settings-tab-panel').forEach(p => p.classList.add('hidden'));
        document.querySelectorAll('.settings-tab-btn').forEach(b => {
            b.classList.remove('border-primary', 'text-primary', 'font-bold');
            b.classList.add('border-transparent', 'text-on-surface-variant');
        });
        document.getElementById('tab-' + tabId).classList.remove('hidden');
        const btn = document.getElementById('tab-btn-' + tabId);
        btn.classList.add('border-primary', 'text-primary', 'font-bold');
        btn.classList.remove('border-transparent', 'text-on-surface-variant');
    }

    // ── Theme ─────────────────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', function() {
        updateThemeButtons();
        updateSidebarToggleUI();
    });

    function setTheme(theme) {
        const html = document.documentElement;
        if (theme === 'dark') {
            html.classList.add('dark');
            localStorage.setItem('pharma-theme', 'dark');
        } else if (theme === 'light') {
            html.classList.remove('dark');
            localStorage.setItem('pharma-theme', 'light');
        } else {
            localStorage.removeItem('pharma-theme');
            if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                html.classList.add('dark');
            } else {
                html.classList.remove('dark');
            }
        }
        updateThemeButtons();
    }

    function updateThemeButtons() {
        const stored = localStorage.getItem('pharma-theme');
        const ids    = ['btn-theme-light', 'btn-theme-dark', 'btn-theme-system'];
        ids.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.classList.remove('border-primary', 'bg-surface-container');
        });
        const activeId = stored === 'dark' ? 'btn-theme-dark'
                        : stored === 'light' ? 'btn-theme-light'
                        : 'btn-theme-system';
        const el = document.getElementById(activeId);
        if (el) el.classList.add('border-primary', 'bg-surface-container');
    }

    // ── Sidebar toggle from settings page ────────────────────────────────
    function toggleSidebarFromSettings() {
        if (typeof toggleDesktopSidebar === 'function') toggleDesktopSidebar();
        updateSidebarToggleUI();
    }

    function updateSidebarToggleUI() {
        const btn   = document.getElementById('sidebarToggleBtn');
        const thumb = document.getElementById('sidebarToggleThumb');
        if (!btn) return;
        const isCollapsed = localStorage.getItem('pharma-sidebar-collapsed') === 'true';
        if (isCollapsed) {
            btn.style.backgroundColor = 'var(--color-primary, #006565)';
            thumb.style.transform = 'translateX(24px)';
        } else {
            btn.style.backgroundColor = '';
            thumb.style.transform = 'translateX(0)';
        }
    }
</script>
@endsection

