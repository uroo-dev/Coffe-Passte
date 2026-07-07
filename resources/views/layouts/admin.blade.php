<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Coffe Paste')</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('styles')
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "surface-container": "#e7eefe", "secondary": "#5f5e5e",
                        "secondary-fixed-dim": "#c8c6c5", "on-secondary-fixed": "#1c1b1b",
                        "error-container": "#ffdad6", "on-secondary-container": "#656464",
                        "error": "#ba1a1a", "surface-tint": "#a73a00",
                        "inverse-surface": "#2a313d", "primary-fixed-dim": "#ffb59a",
                        "surface-variant": "#dce2f3", "on-error": "#ffffff",
                        "inverse-primary": "#ffb59a", "primary-container": "#ff5c00",
                        "on-surface": "#151c27", "surface": "#f9f9ff",
                        "on-secondary-fixed-variant": "#474646", "surface-container-lowest": "#ffffff",
                        "on-primary-fixed": "#370e00", "on-tertiary-container": "#2a2c2c",
                        "tertiary": "#5d5f5f", "on-secondary": "#ffffff",
                        "background": "#f9f9ff", "on-tertiary": "#ffffff",
                        "tertiary-container": "#929393", "surface-container-high": "#e2e8f8",
                        "surface-dim": "#d3daea", "outline-variant": "#e4beb1",
                        "on-surface-variant": "#5b4137", "on-tertiary-fixed-variant": "#454747",
                        "outline": "#8f7065", "on-error-container": "#93000a",
                        "on-primary-fixed-variant": "#802a00", "primary": "#a73a00",
                        "inverse-on-surface": "#ebf1ff", "secondary-fixed": "#e5e2e1",
                        "on-primary-container": "#521800", "surface-container-highest": "#dce2f3",
                        "surface-container-low": "#f0f3ff", "secondary-container": "#e5e2e1",
                        "on-tertiary-fixed": "#1a1c1c", "primary-fixed": "#ffdbce",
                        "surface-bright": "#f9f9ff", "tertiary-fixed": "#e2e2e2",
                        "on-primary": "#ffffff", "tertiary-fixed-dim": "#c6c6c7",
                        "on-background": "#151c27"
                    },
                    borderRadius: { DEFAULT: "0.25rem", lg: "0.5rem", xl: "0.75rem", full: "9999px" },
                    spacing: { "stack-sm": "0.5rem", "stack-md": "1rem", "stack-lg": "2rem", "container-margin": "1.25rem", gutter: "1rem" },
                    fontFamily: { "headline-xl": ["Lexend"], "body-md": ["Lexend"], "price-display": ["Lexend"], "label-md": ["Lexend"], "headline-lg": ["Lexend"], "body-sm": ["Lexend"], "headline-lg-mobile": ["Lexend"] },
                    fontSize: {
                        "headline-xl": ["32px", { lineHeight: "40px", letterSpacing: "-0.02em", fontWeight: "700" }],
                        "body-md": ["16px", { lineHeight: "24px", fontWeight: "400" }],
                        "price-display": ["18px", { lineHeight: "24px", fontWeight: "700" }],
                        "label-md": ["14px", { lineHeight: "16px", letterSpacing: "0.02em", fontWeight: "600" }],
                        "headline-lg": ["24px", { lineHeight: "32px", letterSpacing: "-0.01em", fontWeight: "600" }],
                        "body-sm": ["14px", { lineHeight: "20px", fontWeight: "400" }],
                        "headline-lg-mobile": ["20px", { lineHeight: "28px", fontWeight: "600" }]
                    }
                }
            }
        }
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #dce2f3; border-radius: 10px; }
        body { min-height: 100dvh; }
    </style>
</head>
<body class="bg-background text-on-surface font-body-md">
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
