@extends('layouts.customer')

@section('title', 'Modern Gastronomy - Meja ' . $table->table_number)

@push('styles')
<style>
    @keyframes slow-zoom { from { transform: scale(1.0); } to { transform: scale(1.1); } }
    .animate-slow-zoom { animation: slow-zoom 20s infinite alternate ease-in-out; }
</style>
@endpush

@section('content')
<div class="fixed inset-0 z-0 overflow-hidden">
    <div class="absolute inset-0 bg-cover bg-center animate-slow-zoom"
         style="background-image: url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1600&q=80')">
    </div>
    <div class="absolute inset-0 bg-gradient-to-t from-on-surface/80 via-on-surface/20 to-transparent"></div>
</div>

<header class="fixed top-0 left-0 w-full z-50 flex justify-between items-center px-container-margin py-stack-md bg-surface/10 backdrop-blur-md">
    <div class="font-headline-lg-mobile text-headline-lg-mobile font-bold text-white drop-shadow-md">
        Coffe Paste
    </div>
    <button class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-white backdrop-blur-md">
        <span class="material-symbols-outlined">notifications</span>
    </button>
</header>

<main class="relative z-10 h-screen w-full flex flex-col items-center justify-end pb-24 px-container-margin">
    <div class="glass-card rounded-xl px-6 py-3 mb-8 flex items-center gap-3 self-start animate-fade-in shadow-xl">
        <div class="bg-primary-container text-white w-12 h-12 rounded-lg flex items-center justify-center">
            <span class="material-symbols-outlined text-2xl">restaurant</span>
        </div>
        <div>
            <p class="font-label-md text-label-md text-primary opacity-80">Nomor Meja</p>
            <h2 class="font-headline-lg text-headline-lg text-on-surface leading-none">Meja {{ $table->table_number }}</h2>
            <p class="text-xs mt-1 font-medium flex items-center gap-1
                @if($table->status === 'empty') text-green-400
                @elseif($table->status === 'occupied') text-yellow-400
                @else text-red-400 @endif">
                <span class="w-2 h-2 rounded-full inline-block
                    @if($table->status === 'empty') bg-green-400
                    @elseif($table->status === 'occupied') bg-yellow-400
                    @else bg-red-400 @endif">
                </span>
                @if($table->status === 'empty') Tersedia — Silakan pesan
                @elseif($table->status === 'occupied') Sedang digunakan
                @else Menunggu pembayaran @endif
            </p>
        </div>
    </div>

    <div class="w-full text-left space-y-6">
        <h1 class="font-headline-xl text-headline-xl text-white drop-shadow-lg max-w-[280px]">
            Selamat Datang di Coffe Paste
        </h1>
        <p class="font-body-md text-body-md text-white/90 max-w-sm leading-relaxed">
            Nikmati pengalaman kuliner terbaik langsung dari genggaman Anda.
        </p>

        <div class="flex flex-col gap-4 w-full">
            <a href="{{ route('customer.menu', $table->qr_token) }}"
               class="group relative flex items-center justify-between bg-primary-container text-white font-headline-lg-mobile text-headline-lg-mobile px-8 py-5 rounded-full shadow-2xl transition-all duration-200 active:scale-95 overflow-hidden">
                <span class="relative z-10 text-white font-bold">Lihat Menu & Pesan</span>
                <span class="material-symbols-outlined relative z-10 text-white transition-transform group-hover:translate-x-2">arrow_forward</span>
                <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </a>

            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('customer.call-staff', $table->qr_token) }}"
                   class="glass-card flex items-center justify-center gap-2 py-4 rounded-xl text-on-surface font-label-md text-label-md shadow-lg transition-transform active:scale-95">
                    <span class="material-symbols-outlined">person_raised_hand</span>
                    <span>Panggil Pelayan</span>
                </a>
                <a href="{{ route('customer.orders', $table->qr_token) }}"
                   class="glass-card flex items-center justify-center gap-2 py-4 rounded-xl text-on-surface font-label-md text-label-md shadow-lg transition-transform active:scale-95">
                    <span class="material-symbols-outlined">history</span>
                    <span>Pesanan Saya</span>
                </a>
            </div>
        </div>
    </div>
</main>

<div class="fixed bottom-0 left-0 w-full h-32 bg-gradient-to-t from-black/40 to-transparent pointer-events-none z-0"></div>

@push('scripts')
<script>
    document.addEventListener('mousemove', (e) => {
        const moveX = (e.clientX - window.innerWidth / 2) * 0.01;
        const moveY = (e.clientY - window.innerHeight / 2) * 0.01;
        const el = document.querySelector('.animate-slow-zoom');
        if (el) el.style.transform = 'scale(1.05) translate(' + moveX + 'px, ' + moveY + 'px)';
    });
</script>
@endpush
@endsection
