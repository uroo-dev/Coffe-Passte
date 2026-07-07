@php
    $colors = [
        'surface-container' => '#e7eefe', 'primary' => '#a73a00', 'primary-container' => '#ff5c00',
        'on-primary' => '#ffffff', 'on-surface' => '#151c27', 'background' => '#f9f9ff',
        'surface' => '#f9f9ff', 'surface-container-low' => '#f0f3ff', 'outline' => '#8f7065',
        'error' => '#ba1a1a', 'error-container' => '#ffdad6', 'on-error' => '#ffffff',
        'surface-variant' => '#dce2f3', 'on-surface-variant' => '#5b4137', 'primary-fixed' => '#ffdbce',
        'on-primary-fixed' => '#370e00',
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Coffe Paste</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Lexend', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; }
        .btn-scale:active { transform: scale(0.97); }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center" style="background: {{ $colors['surface-container'] }};">
    <div class="w-full max-w-md mx-4">
        <div class="p-8 rounded-xl shadow-lg" style="background: {{ $colors['surface'] }};">
            <div class="text-center mb-8">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center" style="background: {{ $colors['primary-fixed'] }};">
                    <span class="material-symbols-outlined text-3xl" style="color: {{ $colors['primary'] }};">local_cafe</span>
                </div>
                <h1 class="text-2xl font-bold" style="color: {{ $colors['on-surface'] }};">Coffe Paste</h1>
                <p class="text-sm mt-1" style="color: {{ $colors['on-surface-variant'] }};">Masuk ke akun Anda</p>
            </div>

            @if ($errors->any())
                <div class="p-4 rounded-lg mb-6 flex items-center gap-3" style="background: {{ $colors['error-container'] }}; color: {{ $colors['error'] }};">
                    <span class="material-symbols-outlined">error</span>
                    <span class="text-sm font-medium">{{ $errors->first('email') }}</span>
                </div>
            @endif

            @if (session('success'))
                <div class="p-4 rounded-lg mb-6 flex items-center gap-3" style="background: #d9f9d9; color: #1a7a1a;">
                    <span class="material-symbols-outlined">check_circle</span>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <form method="POST" action="/login" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium mb-1.5" style="color: {{ $colors['on-surface'] }};">Email</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-lg" style="color: {{ $colors['outline'] }};">mail</span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border text-sm outline-none transition-all focus:ring-2"
                            style="border-color: {{ $colors['outline'] }}; color: {{ $colors['on-surface'] }}; background: {{ $colors['surface-container-low'] }}; focus:border-color: {{ $colors['primary'] }};"
                            onfocus="this.style.borderColor='{{ $colors['primary'] }}'; this.style.setProperty('--tw-ring-color', '{{ $colors['primary'] }}33')"
                            onblur="this.style.borderColor='{{ $colors['outline'] }}'">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium mb-1.5" style="color: {{ $colors['on-surface'] }};">Password</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-lg" style="color: {{ $colors['outline'] }};">lock</span>
                        <input type="password" id="password" name="password" required
                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border text-sm outline-none transition-all focus:ring-2"
                            style="border-color: {{ $colors['outline'] }}; color: {{ $colors['on-surface'] }}; background: {{ $colors['surface-container-low'] }};"
                            onfocus="this.style.borderColor='{{ $colors['primary'] }}'; this.style.setProperty('--tw-ring-color', '{{ $colors['primary'] }}33')"
                            onblur="this.style.borderColor='{{ $colors['outline'] }}'">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm cursor-pointer" style="color: {{ $colors['on-surface-variant'] }};">
                        <input type="checkbox" name="remember" class="rounded border" style="border-color: {{ $colors['outline'] }}; color: {{ $colors['primary'] }};">
                        Ingat saya
                    </label>
                </div>

                <button type="submit" class="btn-scale w-full py-2.5 rounded-lg text-sm font-semibold transition-all hover:opacity-90"
                    style="background: {{ $colors['primary'] }}; color: {{ $colors['on-primary'] }};">
                    <span class="material-symbols-outlined text-lg mr-1">login</span>
                    Masuk
                </button>
            </form>

            <p class="text-center text-xs mt-6" style="color: {{ $colors['outline'] }};">&copy; {{ date('Y') }} Coffe Paste</p>
        </div>
    </div>
</body>
</html>
