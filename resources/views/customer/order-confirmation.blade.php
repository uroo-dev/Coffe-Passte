@extends('layouts.customer')

@section('title', 'Konfirmasi Pesanan - Coffe Paste')

@push('styles')
<style>
    .animate-check { stroke-dasharray: 100; stroke-dashoffset: 100; animation: dash 0.8s ease-out forwards; animation-delay: 0.3s; }
    @keyframes dash { to { stroke-dashoffset: 0; } }
    .fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endpush

@section('content')
<header class="fixed top-0 w-full z-50 flex items-center justify-between px-container-margin h-16 bg-surface/80 backdrop-blur-md">
    <div class="flex items-center gap-4">
        <a href="{{ route('customer.landing', $qrToken) }}"
           class="active:scale-95 transition-transform duration-150 text-primary">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <h1 class="font-headline-lg-mobile text-headline-lg-mobile font-bold text-primary">Konfirmasi Pesanan</h1>
    </div>
</header>

<main class="flex-1 pt-24 pb-32 px-container-margin overflow-y-auto">
    <div class="flex flex-col items-center text-center max-w-md mx-auto">
        <div class="relative w-48 h-48 mb-8 flex items-center justify-center">
            <div class="absolute inset-0 bg-primary-fixed/30 rounded-full animate-ping opacity-25"></div>
            <div class="absolute inset-2 bg-primary-fixed/50 rounded-full scale-110"></div>
            <div class="relative bg-primary-container w-32 h-32 rounded-full flex items-center justify-center shadow-lg">
                <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="3" viewBox="0 0 24 24">
                    <path class="animate-check" d="M20 6L9 17l-5-5"></path>
                </svg>
            </div>
        </div>

        <div class="fade-in-up" style="animation-delay: 0.1s;">
            <h2 class="font-headline-xl text-headline-xl text-on-surface mb-2">Pesanan Diproses</h2>
            <p class="font-body-md text-body-md text-secondary mb-8">Terima kasih, pesanan Anda akan segera disiapkan.</p>
        </div>

        <div class="w-full bg-surface-container-lowest rounded-xl p-6 shadow-[0_4px_12px_rgba(0,0,0,0.05)] border border-outline-variant/30 mb-6 fade-in-up" style="animation-delay: 0.2s;">
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="font-body-sm text-body-sm text-secondary">No. Referensi</span>
                    <span class="font-label-md text-label-md text-on-surface">{{ $order->order_reference }}</span>
                </div>
                <div class="border-t border-dashed border-outline-variant"></div>
                <div class="flex justify-between items-center">
                    <span class="font-body-sm text-body-sm text-secondary">Total Pembayaran</span>
                    <span class="font-price-display text-price-display text-primary">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
                <div class="border-t border-dashed border-outline-variant"></div>
                <div class="flex justify-between items-center">
                    <span class="font-body-sm text-body-sm text-secondary">Status</span>
                    <div class="flex items-center gap-2">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-container opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-container"></span>
                        </span>
                        <span class="font-label-md text-label-md text-primary-container">{{ ucfirst($order->order_status) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full flex gap-4 p-4 bg-surface-container rounded-xl border-l-4 border-primary-container fade-in-up text-left" style="animation-delay: 0.3s;">
            <span class="material-symbols-outlined text-primary-container">info</span>
            <p class="font-body-sm text-body-sm text-on-surface-variant">
                Anda dapat memantau status pesanan melalui halaman <strong>Pesanan Saya</strong>.
            </p>
        </div>
    </div>
</main>

<div class="fixed bottom-0 left-0 w-full bg-surface px-container-margin py-6 border-t border-outline-variant/10">
    <div class="max-w-md mx-auto">
        <a href="{{ route('customer.orders', $qrToken) }}"
           class="w-full bg-primary-container text-white py-4 rounded-xl font-label-md text-label-md active:scale-95 transition-all duration-200 shadow-md flex items-center justify-center gap-2">
            <span>Lihat Status Pesanan</span>
            <span class="material-symbols-outlined">restaurant</span>
        </a>
    </div>
</div>
@endsection
