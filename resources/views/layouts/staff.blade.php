<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Staff - Coffe Paste')</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @stack('styles')
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "on-surface": "#151c27", "on-error-container": "#93000a",
                        "on-tertiary-fixed-variant": "#454747", "outline-variant": "#e4beb1",
                        "inverse-primary": "#ffb59a", "error-container": "#ffdad6",
                        "on-tertiary-fixed": "#1a1c1c", "secondary-fixed": "#e5e2e1",
                        "secondary-container": "#e5e2e1", "tertiary-fixed-dim": "#c6c6c7",
                        "on-secondary-fixed": "#1c1b1b", "surface-variant": "#dce2f3",
                        "on-surface-variant": "#5b4137", "on-primary": "#ffffff",
                        "surface-tint": "#a73a00", "surface-dim": "#d3daea",
                        "surface-bright": "#f9f9ff", "outline": "#8f7065",
                        "background": "#f9f9ff", "on-tertiary": "#ffffff",
                        "surface-container": "#e7eefe", "surface": "#f9f9ff",
                        "error": "#ba1a1a", "tertiary": "#5d5f5f",
                        "surface-container-highest": "#dce2f3", "primary-container": "#ff5c00",
                        "on-secondary-fixed-variant": "#474646", "inverse-on-surface": "#ebf1ff",
                        "on-tertiary-container": "#2a2c2c", "surface-container-high": "#e2e8f8",
                        "on-primary-fixed": "#370e00", "on-error": "#ffffff",
                        "primary-fixed": "#ffdbce", "tertiary-fixed": "#e2e2e2",
                        "secondary-fixed-dim": "#c8c6c5", "primary-fixed-dim": "#ffb59a",
                        "surface-container-lowest": "#ffffff", "on-primary-container": "#521800",
                        "tertiary-container": "#929393", "inverse-surface": "#2a313d",
                        "on-secondary": "#ffffff", "on-background": "#151c27",
                        "primary": "#a73a00", "surface-container-low": "#f0f3ff",
                        "on-secondary-container": "#656464", "secondary": "#5f5e5e",
                        "on-primary-fixed-variant": "#802a00"
                    },
                    borderRadius: { DEFAULT: "0.25rem", lg: "0.5rem", xl: "0.75rem", full: "9999px" },
                    spacing: { "stack-sm": "0.5rem", "container-margin": "1.25rem", "gutter": "1rem", "stack-lg": "2rem", "stack-md": "1rem" },
                    fontFamily: { "headline-lg-mobile": ["Lexend"], "body-md": ["Lexend"], "price-display": ["Lexend"], "label-md": ["Lexend"], "headline-xl": ["Lexend"], "headline-lg": ["Lexend"], "body-sm": ["Lexend"] },
                    fontSize: {
                        "headline-lg-mobile": ["20px", { lineHeight: "28px", fontWeight: "600" }],
                        "body-md": ["16px", { lineHeight: "24px", fontWeight: "400" }],
                        "price-display": ["18px", { lineHeight: "24px", fontWeight: "700" }],
                        "label-md": ["14px", { lineHeight: "16px", letterSpacing: "0.02em", fontWeight: "600" }],
                        "headline-xl": ["32px", { lineHeight: "40px", letterSpacing: "-0.02em", fontWeight: "700" }],
                        "headline-lg": ["24px", { lineHeight: "32px", letterSpacing: "-0.01em", fontWeight: "600" }],
                        "body-sm": ["14px", { lineHeight: "20px", fontWeight: "400" }]
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Lexend', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e4beb1; border-radius: 10px; }
        body { min-height: 100dvh; }
    </style>
</head>
<body class="bg-background text-on-background selection:bg-primary-fixed selection:text-on-primary-fixed">
    @yield('content')
    <script>
        document.querySelectorAll('button').forEach(btn => {
            btn.addEventListener('click', function() {
                if (!this.classList.contains('no-scale')) {
                    this.style.transform = 'scale(0.97)';
                    setTimeout(() => this.style.transform = '', 100);
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
