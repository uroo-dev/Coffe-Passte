@extends('layouts.customer')

@section('title', 'Pembayaran - Coffe Paste')

@push('styles')
<style>
    .fade-in { animation: fadeIn 0.4s ease-out forwards; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .qr-pulse { animation: qrPulse 2s ease-in-out infinite; }
    @keyframes qrPulse { 0%, 100% { box-shadow: 0 0 0 0 rgba(166, 75, 90, 0.3); } 50% { box-shadow: 0 0 0 12px rgba(166, 75, 90, 0); } }
    .checkmark { stroke-dasharray: 50; stroke-dashoffset: 50; animation: drawCheck 0.6s ease forwards; }
    @keyframes drawCheck { to { stroke-dashoffset: 0; } }
</style>
@endpush

@section('content')
<header class="fixed top-0 w-full z-50 flex items-center justify-between px-container-margin h-16 bg-surface/80 backdrop-blur-md">
    <div class="flex items-center gap-4">
        <a href="{{ route('customer.landing', $table->qr_token) }}"
           class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-surface-container-low active:scale-95 text-primary">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <h1 class="font-headline-lg-mobile text-headline-lg-mobile text-primary">Pembayaran</h1>
    </div>
    <div class="px-3 py-1 bg-primary-fixed text-on-primary-fixed rounded-full text-label-md font-label-md">
        Meja {{ $table->table_number }}
    </div>
</header>

<main class="pt-20 px-container-margin max-w-lg mx-auto space-y-6 pb-32" id="payment-main">
    @if($activeOrder)
    <section class="fade-in" style="animation-delay: 0.1s">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">Ringkasan Pesanan</h2>
            <span class="text-body-sm text-secondary">{{ $activeOrder->items->count() }} Item</span>
        </div>
        <div class="bg-surface-container-lowest rounded-xl p-4 shadow-[0_4px_12px_rgba(0,0,0,0.05)] space-y-4">
            @foreach($activeOrder->items as $item)
            <div class="flex justify-between items-start">
                <div class="flex gap-4">
                    <div>
                        <p class="font-label-md text-label-md text-on-surface">{{ $item->menu->name }}</p>
                        <p class="text-body-sm text-secondary">x{{ $item->quantity }} @if($item->notes) • {{ $item->notes }} @endif</p>
                    </div>
                </div>
                <p class="font-price-display text-price-display text-on-surface">Rp {{ number_format($item->price_at_order * $item->quantity, 0, ',', '.') }}</p>
            </div>
            @endforeach
            <div class="pt-4 border-t border-outline-variant space-y-2">
                <div class="flex justify-between items-center pt-2">
                    <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">Total</span>
                    <span class="font-headline-xl text-headline-xl text-primary-container">Rp {{ number_format($activeOrder->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </section>

    <section class="fade-in" style="animation-delay: 0.3s">
        <div class="text-center mb-6">
            <h2 class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface mb-2">Scan untuk Bayar</h2>
            <p class="text-body-sm text-secondary">Tunjukkan QR ini ke kasir untuk pembayaran</p>
        </div>

        <div id="qr-container" class="flex flex-col items-center gap-4">
            <div class="qr-pulse bg-surface-container-lowest rounded-2xl p-4 inline-block">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode(route('staff.scan-payment', $activeOrder)) }}"
                     alt="QR Payment"
                     class="w-56 h-56"
                     id="qr-image">
            </div>
            <p class="text-body-sm text-secondary text-center max-w-xs">
                <span class="material-symbols-outlined text-[16px] align-text-bottom">info</span>
                Kasir akan scan QR ini untuk memproses pembayaran Anda
            </p>
        </div>

        <div id="paid-container" class="hidden flex flex-col items-center gap-4 py-8">
            <div class="w-24 h-24 bg-primary-container/10 rounded-full flex items-center justify-center">
                <svg class="w-14 h-14 text-primary-container" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline class="checkmark" points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            <h2 class="font-headline-lg-mobile text-headline-lg-mobile text-primary-container">Pembayaran Berhasil!</h2>
            <p class="text-body-sm text-secondary">Mengalihkan ke invoice...</p>
            <div class="w-6 h-6 border-2 border-primary-container/30 border-t-primary-container rounded-full animate-spin"></div>
        </div>
    </section>
    @else
    <section class="fade-in py-12 text-center">
        <div class="w-20 h-20 bg-surface-container-high rounded-full flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-[40px] text-secondary">receipt_long</span>
        </div>
        <h2 class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface mb-2">Tidak Ada Pesanan Aktif</h2>
        <p class="text-body-sm text-secondary mb-6">Silakan pesan terlebih dahulu dari menu</p>
        <a href="{{ route('customer.menu', $table->qr_token) }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-primary-container text-white rounded-xl font-label-md">
            <span class="material-symbols-outlined">restaurant_menu</span>
            Lihat Menu
        </a>
    </section>
    @endif
</main>

@push('scripts')
<script>
    @if($activeOrder && $activeOrder->payment_status !== 'paid')
    const orderId = {{ $activeOrder->id }};
    const invoiceUrl = '{{ route("customer.invoice", $activeOrder) }}';
    let pollCount = 0;

    function checkPayment() {
        fetch('{{ route("customer.payment-status", $activeOrder) }}')
            .then(r => r.json())
            .then(data => {
                pollCount++;
                if (data.payment_status === 'paid') {
                    document.getElementById('qr-container').classList.add('hidden');
                    document.getElementById('paid-container').classList.remove('hidden');
                    setTimeout(() => { window.location.href = invoiceUrl; }, 1500);
                } else if (pollCount > 120) {
                    clearInterval(polling);
                }
            })
            .catch(() => {});
    }

    const polling = setInterval(checkPayment, 3000);
    @endif

    function confirmPay(btn) {
        btn.disabled = true;
        btn.innerHTML = '<div class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div> Memproses...';
        return true;
    }
</script>
@endpush
@endsection