@extends('layouts.customer')

@section('title', 'Invoice - Coffe Paste')

@push('styles')
<style>
    .receipt-cut {
        clip-path: polygon(0 0, 100% 0, 100% 98%, 98% 100%, 96% 98%, 94% 100%, 92% 98%, 90% 100%, 88% 98%, 86% 100%, 84% 98%, 82% 100%, 80% 98%, 78% 100%, 76% 98%, 74% 100%, 72% 98%, 70% 100%, 68% 98%, 66% 100%, 64% 98%, 62% 100%, 60% 98%, 58% 100%, 56% 98%, 54% 100%, 52% 98%, 50% 100%, 48% 98%, 46% 100%, 44% 98%, 42% 100%, 40% 98%, 38% 100%, 36% 98%, 34% 100%, 32% 98%, 30% 100%, 28% 98%, 26% 100%, 24% 98%, 22% 100%, 20% 98%, 18% 100%, 16% 98%, 14% 100%, 12% 98%, 10% 100%, 8% 98%, 6% 100%, 4% 98%, 2% 100%, 0 98%);
    }
</style>
@endpush

@section('content')
<header class="fixed top-0 w-full z-50 bg-surface/80 backdrop-blur-md flex items-center justify-between px-container-margin h-16">
    <button onclick="window.history.back()" class="text-primary hover:bg-surface-container-low transition-colors p-2 rounded-full active:scale-95">
        <span class="material-symbols-outlined">arrow_back</span>
    </button>
    <h1 class="font-headline-lg-mobile text-headline-lg-mobile font-bold text-primary">Invoice</h1>
    <div class="w-10"></div>
</header>

<main class="pt-24 px-container-margin max-w-lg mx-auto pb-32">
    <div class="flex flex-col items-center mb-stack-lg">
        <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mb-stack-sm">
            <span class="material-symbols-outlined text-primary text-5xl" style="font-variation-settings: 'FILL' 1;">check_circle</span>
        </div>
        <p class="font-label-md text-label-md text-primary uppercase tracking-widest">Pembayaran Berhasil</p>
    </div>

    <div class="bg-surface-container-lowest rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.05)] receipt-cut p-6 border border-outline-variant/30">
        <div class="text-center mb-stack-lg border-b border-dashed border-outline-variant pb-stack-lg">
            <div class="flex justify-center mb-2">
                <span class="material-symbols-outlined text-primary-container text-4xl">restaurant</span>
            </div>
            <h2 class="font-headline-lg-mobile text-headline-lg-mobile font-bold text-on-surface uppercase">Coffe Paste</h2>
            <p class="font-body-sm text-body-sm text-secondary">Terima kasih atas kunjungan Anda</p>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-stack-lg font-body-sm text-body-sm text-on-surface-variant">
            <div>
                <span class="block text-secondary text-[12px] uppercase font-bold mb-1">Order ID</span>
                <span class="font-semibold text-on-surface">{{ $order->order_reference }}</span>
            </div>
            <div class="text-right">
                <span class="block text-secondary text-[12px] uppercase font-bold mb-1">Meja</span>
                <span class="font-semibold text-on-surface">Meja {{ $order->table->table_number }}</span>
            </div>
            <div>
                <span class="block text-secondary text-[12px] uppercase font-bold mb-1">Tanggal</span>
                <span class="font-semibold text-on-surface">{{ $order->created_at->format('d M Y') }}</span>
            </div>
            <div class="text-right">
                <span class="block text-secondary text-[12px] uppercase font-bold mb-1">Jam</span>
                <span class="font-semibold text-on-surface">{{ $order->created_at->format('H:i') }} WIB</span>
            </div>
        </div>

        <div class="space-y-4 mb-stack-lg">
            @foreach($order->items as $item)
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h4 class="font-body-md text-body-md font-semibold text-on-surface">{{ $item->menu->name }}</h4>
                    <p class="font-body-sm text-body-sm text-secondary">{{ $item->quantity }}x @if($item->notes) • {{ $item->notes }} @endif</p>
                </div>
                <span class="font-price-display text-price-display text-on-surface">Rp {{ number_format($item->price_at_order * $item->quantity, 0, ',', '.') }}</span>
            </div>
            @endforeach
        </div>

        <div class="border-t border-dashed border-outline-variant pt-stack-lg space-y-2">
            <div class="flex justify-between font-body-sm">
                <span class="text-secondary">Subtotal</span>
                <span class="text-on-surface">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between pt-stack-md font-headline-lg-mobile text-headline-lg-mobile text-primary-container font-bold">
                <span>Total</span>
                <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="mt-stack-lg bg-surface-container-low rounded-lg p-4 flex items-center gap-4">
            <div class="w-12 h-12 bg-primary-fixed rounded-full flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-on-primary-fixed-variant" style="font-variation-settings: 'FILL' 1;">payments</span>
            </div>
            <div>
                <h5 class="font-label-md text-label-md text-on-surface-variant">Metode Pembayaran</h5>
                <p class="font-headline-lg-mobile text-headline-lg-mobile font-bold text-on-surface uppercase">{{ $order->payment_method }}</p>
            </div>
        </div>
    </div>

    <div class="mt-stack-lg flex flex-col gap-stack-md">
        <a href="{{ route('customer.orders', $order->table->qr_token) }}"
           class="w-full bg-primary-container text-white font-label-md text-label-md py-4 rounded-xl flex items-center justify-center gap-2 shadow-lg active:scale-95 duration-200">
            <span class="material-symbols-outlined">receipt_long</span>
            Lihat Pesanan Lain
        </a>
        <a href="{{ route('customer.landing', $order->table->qr_token) }}"
           class="w-full bg-surface-container-high text-on-surface font-label-md text-label-md py-4 rounded-xl flex items-center justify-center gap-2 active:scale-95 duration-200">
            <span class="material-symbols-outlined">home</span>
            Kembali ke Beranda
        </a>
    </div>
</main>
@endsection
