@extends('layouts.customer')

@section('title', 'Panggil Staf - Coffe Paste')

@push('styles')
<style>
    @keyframes ripple-animation { 0% { transform: scale(0); opacity: 0.5; } 100% { transform: scale(4); opacity: 0; } }
    .ripple-effect { position: absolute; border-radius: 50%; background: rgba(255,255,255,0.4); animation: ripple-animation 0.6s linear; pointer-events: none; }
    @keyframes slide-up { from { transform: translateY(100%); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    .toast-animate { animation: slide-up 0.3s cubic-bezier(0.175,0.885,0.32,1.275) forwards; }
</style>
@endpush

@section('content')
<header class="fixed top-0 w-full z-50 bg-surface/80 backdrop-blur-md flex items-center justify-between px-container-margin h-16">
    <div class="flex items-center gap-4">
        <a href="{{ route('customer.landing', $table->qr_token) }}"
           class="active:scale-95 transition-transform duration-150 p-2 hover:bg-surface-container-low rounded-full">
            <span class="material-symbols-outlined text-primary">arrow_back</span>
        </a>
        <h1 class="font-headline-lg-mobile text-headline-lg-mobile font-bold text-primary">Panggil Staf</h1>
    </div>
    <div class="bg-primary-container text-white px-3 py-1 rounded-full font-label-md text-label-md">
        Meja {{ $table->table_number }}
    </div>
</header>

<main class="pt-24 px-container-margin max-w-md mx-auto">
    <section class="flex flex-col items-center justify-center text-center mb-10">
        <div class="relative group">
            <div class="absolute inset-0 bg-primary/10 rounded-full animate-ping group-active:animate-none"></div>
            <button onclick="sendRequest('Panggil Pelayan')"
                    class="relative w-48 h-48 bg-primary-container rounded-full flex flex-col items-center justify-center shadow-[0_8px_32px_rgba(255,92,0,0.3)] active:scale-90 transition-all duration-200"
                    id="callWaiterBtn">
                <span class="material-symbols-outlined text-white text-[64px] mb-2" style="font-variation-settings: 'FILL' 1;">person_raised_hand</span>
                <span class="text-white font-headline-lg-mobile text-headline-lg-mobile">Panggil!</span>
            </button>
        </div>
        <p class="mt-6 text-on-surface-variant font-body-md text-body-md max-w-[280px]">
            Tekan tombol untuk memanggil staf ke meja Anda.
        </p>
    </section>

    <section class="mb-12">
        <h2 class="font-headline-lg-mobile text-headline-lg-mobile mb-6">Permintaan Cepat</h2>
        <div class="grid grid-cols-2 gap-4">
            <button onclick="sendRequest('Tambah Air Minum')"
                    class="flex flex-col items-start p-5 bg-surface-container-lowest rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.05)] hover:bg-surface-container-low transition-colors active:scale-95 border border-outline-variant/30">
                <span class="material-symbols-outlined text-primary mb-3 bg-primary-fixed p-2 rounded-lg">water_full</span>
                <span class="font-label-md text-label-md text-on-surface">Tambah Air</span>
            </button>
            <button onclick="sendRequest('Minta Alat Makan')"
                    class="flex flex-col items-start p-5 bg-surface-container-lowest rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.05)] hover:bg-surface-container-low transition-colors active:scale-95 border border-outline-variant/30">
                <span class="material-symbols-outlined text-primary mb-3 bg-primary-fixed p-2 rounded-lg">restaurant</span>
                <span class="font-label-md text-label-md text-on-surface">Alat Makan</span>
            </button>
            <button onclick="sendRequest('Bersihkan Meja')"
                    class="flex flex-col items-start p-5 bg-surface-container-lowest rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.05)] hover:bg-surface-container-low transition-colors active:scale-95 border border-outline-variant/30">
                <span class="material-symbols-outlined text-primary mb-3 bg-primary-fixed p-2 rounded-lg">mop</span>
                <span class="font-label-md text-label-md text-on-surface">Bersihkan</span>
            </button>
            <button onclick="sendRequest('Bantuan Menu')"
                    class="flex flex-col items-start p-5 bg-surface-container-lowest rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.05)] hover:bg-surface-container-low transition-colors active:scale-95 border border-outline-variant/30">
                <span class="material-symbols-outlined text-primary mb-3 bg-primary-fixed p-2 rounded-lg">menu_book</span>
                <span class="font-label-md text-label-md text-on-surface">Bantuan</span>
            </button>
        </div>
    </section>
</main>

<nav class="fixed bottom-0 left-0 w-full z-50 flex justify-around items-center px-4 py-2 bg-surface shadow-[0_-4px_12px_rgba(0,0,0,0.05)] rounded-t-xl">
    <a href="{{ route('customer.menu', $table->qr_token) }}"
       class="flex flex-col items-center justify-center text-secondary hover:bg-surface-container-low p-2 rounded-lg active:scale-90 transition-all">
        <span class="material-symbols-outlined">restaurant_menu</span>
        <span class="font-label-md text-label-md mt-1">Menu</span>
    </a>
    <a href="{{ route('customer.orders', $table->qr_token) }}"
       class="flex flex-col items-center justify-center text-secondary hover:bg-surface-container-low p-2 rounded-lg active:scale-90 transition-all">
        <span class="material-symbols-outlined">receipt_long</span>
        <span class="font-label-md text-label-md mt-1">Pesanan</span>
    </a>
    <a href="{{ route('customer.call-staff', $table->qr_token) }}"
       class="flex flex-col items-center justify-center text-primary font-bold hover:bg-surface-container-low p-2 rounded-lg active:scale-90 transition-all">
        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">person_raised_hand</span>
        <span class="font-label-md text-label-md mt-1">Panggil</span>
    </a>
    <a href="{{ route('customer.payment', $table->qr_token) }}"
       class="flex flex-col items-center justify-center text-secondary hover:bg-surface-container-low p-2 rounded-lg active:scale-90 transition-all">
        <span class="material-symbols-outlined">payments</span>
        <span class="font-label-md text-label-md mt-1">Billing</span>
    </a>
</nav>

<div class="fixed bottom-24 left-1/2 -translate-x-1/2 w-[90%] max-w-sm bg-inverse-surface text-inverse-on-surface px-4 py-3 rounded-xl shadow-2xl flex items-center justify-between z-[60] hidden" id="toast">
    <div class="flex items-center gap-3">
        <span class="material-symbols-outlined text-primary-fixed-dim">check_circle</span>
        <p class="font-body-sm text-body-sm"><span id="request-type">Permintaan</span> terkirim. Staf akan segera datang.</p>
    </div>
    <button class="text-primary-fixed-dim font-label-md text-label-md uppercase tracking-wider ml-4" onclick="hideToast()">Tutup</button>
</div>

@push('scripts')
<script>
    function sendRequest(type) {
        const btn = event.currentTarget;
        const circle = document.createElement('span');
        const diameter = Math.max(btn.clientWidth, btn.clientHeight);
        circle.style.width = circle.style.height = diameter + 'px';
        circle.classList.add('ripple-effect');
        const oldRipple = btn.querySelector('.ripple-effect');
        if (oldRipple) oldRipple.remove();
        btn.appendChild(circle);
        document.getElementById('request-type').textContent = type;
        const toast = document.getElementById('toast');
        toast.classList.remove('hidden');
        toast.classList.add('toast-animate');
        if (navigator.vibrate) navigator.vibrate(50);
        fetch('{{ route("customer.call-staff.api", $table->qr_token) }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ type: type, label: type })
        });
        setTimeout(() => hideToast(), 5000);
    }
    function hideToast() {
        const toast = document.getElementById('toast');
        toast.style.transform = 'translate(-50%, 150%)';
        toast.style.opacity = '0';
        setTimeout(() => { toast.classList.add('hidden'); toast.style.transform = ''; toast.style.opacity = ''; }, 300);
    }
</script>
@endpush
@endsection
