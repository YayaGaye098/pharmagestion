<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>PharmaGestion - Connexion</title>

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
            },
          },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="bg-background min-h-screen flex items-center justify-center p-md text-on-surface">
<main class="w-full max-w-[480px] bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden flex flex-col relative z-10 p-xl">
    <!-- Header -->
    <div class="text-center mb-xl">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-surface-container-low text-primary mb-md">
            <span class="material-symbols-outlined text-4xl" style="font-variation-settings: 'FILL' 1;">medical_services</span>
        </div>
        <h1 class="font-headline-md text-headline-md text-on-surface mb-xs font-bold">PharmaGestion</h1>
        <p class="font-body-md text-body-md text-on-surface-variant">Connectez-vous à votre espace de gestion</p>
    </div>

    <!-- Errors -->
    @if ($errors->any())
        <div class="mb-md p-md rounded bg-error-container text-on-error-container font-body-sm">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form -->
    <form action="{{ route('login') }}" class="flex flex-col gap-lg" method="POST">
        @csrf
        <!-- Email Input -->
        <div class="flex flex-col gap-xs">
            <label class="font-label-md text-label-md text-on-surface-variant" for="email">Adresse E-mail</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-sm flex items-center pointer-events-none text-on-surface-variant">
                    <span class="material-symbols-outlined text-xl">mail</span>
                </div>
                <input class="w-full bg-surface-container-lowest border border-outline-variant rounded-lg pl-xl pr-sm py-sm font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors placeholder:text-on-surface-variant/50" id="email" name="email" value="{{ old('email', 'dr.diallo@pharmacie.sn') }}" placeholder="exemple@pharmacie.sn" required type="email"/>
            </div>
        </div>

        <!-- Password Input -->
        <div class="flex flex-col gap-xs">
            <div class="flex justify-between items-center">
                <label class="font-label-md text-label-md text-on-surface-variant" for="password">Mot de passe</label>
                <a class="font-label-md text-label-md text-primary hover:underline focus:outline-none focus:underline" href="#">Mot de passe oublié ?</a>
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-sm flex items-center pointer-events-none text-on-surface-variant">
                    <span class="material-symbols-outlined text-xl">lock</span>
                </div>
                <input class="w-full bg-surface-container-lowest border border-outline-variant rounded-lg pl-xl pr-xl py-sm font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors placeholder:text-on-surface-variant/50" id="password" name="password" value="password123" placeholder="••••••••" required type="password"/>
                <button class="absolute inset-y-0 right-0 pr-sm flex items-center text-on-surface-variant hover:text-on-surface focus:outline-none" onclick="togglePasswordVisibility()" type="button">
                    <span class="material-symbols-outlined text-xl" id="togglePasswordIcon">visibility_off</span>
                </button>
            </div>
        </div>

        <!-- Remember Me -->
        <div class="flex items-center gap-sm">
            <input class="w-4 h-4 text-primary bg-surface-container-lowest border-outline-variant rounded focus:ring-primary focus:ring-offset-0 cursor-pointer" id="remember" name="remember" type="checkbox"/>
            <label class="font-body-sm text-body-sm text-on-surface-variant cursor-pointer" for="remember">Se souvenir de moi</label>
        </div>

        <!-- Submit Button -->
        <button class="w-full bg-primary text-on-primary font-title-lg text-title-lg py-sm rounded-lg hover:bg-surface-tint focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-colors flex items-center justify-center gap-sm active:opacity-80 font-bold" type="submit">
            Se connecter
            <span class="material-symbols-outlined">login</span>
        </button>
    </form>

    <!-- Footer Links -->
    <div class="mt-xl text-center border-t border-outline-variant pt-md">
        <p class="font-body-sm text-body-sm text-on-surface-variant">
            Vous n'avez pas de compte ? Contactez votre administrateur.
        </p>
    </div>
</main>

<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('togglePasswordIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.textContent = 'visibility';
        } else {
            passwordInput.type = 'password';
            toggleIcon.textContent = 'visibility_off';
        }
    }
</script>
</body>
</html>
