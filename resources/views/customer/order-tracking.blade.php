@extends('layouts.customer')

@section('title', 'Lacak Pesanan - Coffe Paste')

@push('styles')
<style>
    .step { display: flex; align-items: flex-start; gap: 1rem; padding: 1rem 0; }
    .step-line { width: 2px; flex-shrink: 0; margin: 0.5rem 0; }
    .step-dot { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .fade-in { animation: fadeIn 0.4s ease-out forwards; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endpush

@section('content')
<header class="fixed top-0 w-full z-50 flex items-center justify-between px-container-margin h-16 bg-surface/80 backdrop-blur-md">
    <div class="flex items-center gap-4">
        <a href="javascript:history.back()"
           class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-surface-container-low active:scale-95 text-primary">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <h1 class="font-headline-lg-mobile text-headline-lg-mobile text-primary">Lacak Pesanan</h1>
    </div>
    <div class="px-3 py-1 bg-primary-fixed text-on-primary-fixed rounded-full text-label-md font-label-md">
        #{{ $order->order_reference }}
    </div>
</header>

<main class="pt-20 px-container-margin max-w-lg mx-auto space-y-6 pb-32">
    <section class="fade-in">
        <div class="bg-surface-container-lowest rounded-xl p-6 shadow-[0_4px_12px_rgba(0,0,0,0.05)]">
            <div class="text-center mb-6">
                <div class="w-20 h-20 mx-auto rounded-full bg-primary-fixed flex items-center justify-center mb-3">
                    <span class="material-symbols-outlined text-[40px] text-primary">
                        @if($order->order_status === 'pending') schedule
                        @elseif($order->order_status === 'cooking') cooking
                        @elseif($order->order_status === 'ready') restaurant
                        @else check_circle @endif
                    </span>
                </div>
                <h2 class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">
                    @if($order->order_status === 'pending') Pesanan Diterima
                    @elseif($order->order_status === 'cooking') Sedang Dimasak
                    @elseif($order->order_status === 'ready') Siap Diantar
                    @elseif($order->order_status === 'completed') Selesai
                    @else Dibatalkan @endif
                </h2>
                <p class="text-body-sm text-secondary mt-1">{{ $order->created_at->format('d M Y H:i') }}</p>
            </div>

            <div class="space-y-0">
                <div class="step">
                    <div class="step-dot {{ in_array($order->order_status, ['pending','cooking','ready','completed']) ? 'bg-primary-container text-white' : 'bg-surface-container-high text-secondary' }}">
                        <span class="material-symbols-outlined text-sm">receipt</span>
                    </div>
                    <div class="flex-1">
                        <p class="font-label-md text-label-md {{ in_array($order->order_status, ['pending','cooking','ready','completed']) ? 'text-on-surface' : 'text-secondary' }}">Pesanan Dibuat</p>
                        <p class="text-body-sm text-secondary">{{ $order->created_at->format('H:i') }}</p>
                    </div>
                </div>
                <div class="ml-4 pl-8">
                    <div class="step-line h-6 {{ in_array($order->order_status, ['cooking','ready','completed']) ? 'bg-primary-container' : 'bg-outline-variant' }}"></div>
                </div>
                <div class="step">
                    <div class="step-dot {{ in_array($order->order_status, ['cooking','ready','completed']) ? 'bg-primary-container text-white' : 'bg-surface-container-high text-secondary' }}">
                        <span class="material-symbols-outlined text-sm">cooking</span>
                    </div>
                    <div class="flex-1">
                        <p class="font-label-md text-label-md {{ in_array($order->order_status, ['cooking','ready','completed']) ? 'text-on-surface' : 'text-secondary' }}">Sedang Dimasak</p>
                    </div>
                </div>
                <div class="ml-4 pl-8">
                    <div class="step-line h-6 {{ in_array($order->order_status, ['ready','completed']) ? 'bg-primary-container' : 'bg-outline-variant' }}"></div>
                </div>
                <div class="step">
                    <div class="step-dot {{ in_array($order->order_status, ['ready','completed']) ? 'bg-primary-container text-white' : 'bg-surface-container-high text-secondary' }}">
                        <span class="material-symbols-outlined text-sm">delivery</span>
                    </div>
                    <div class="flex-1">
                        <p class="font-label-md text-label-md {{ in_array($order->order_status, ['ready','completed']) ? 'text-on-surface' : 'text-secondary' }}">Siap Diantar</p>
                    </div>
                </div>
                <div class="ml-4 pl-8">
                    <div class="step-line h-6 {{ $order->order_status === 'completed' ? 'bg-primary-container' : 'bg-outline-variant' }}"></div>
                </div>
                <div class="step">
                    <div class="step-dot {{ $order->order_status === 'completed' ? 'bg-primary-container text-white' : 'bg-surface-container-high text-secondary' }}">
                        <span class="material-symbols-outlined text-sm">check_circle</span>
                    </div>
                    <div class="flex-1">
                        <p class="font-label-md text-label-md {{ $order->order_status === 'completed' ? 'text-on-surface' : 'text-secondary' }}">Selesai</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="fade-in" style="animation-delay: 0.2s">
        <div class="bg-surface-container-lowest rounded-xl p-4 shadow-[0_4px_12px_rgba(0,0,0,0.05)] space-y-3">
            <h3 class="font-label-md text-label-md text-on-surface">Item Pesanan</h3>
            @foreach($order->items as $item)
            <div class="flex justify-between items-start">
                <div>
                    <p class="font-label-md text-label-md text-on-surface">{{ $item->menu->name }}</p>
                    <p class="text-body-sm text-secondary">x{{ $item->quantity }}</p>
                </div>
                <span class="font-price-display text-price-display text-on-surface">Rp {{ number_format($item->price_at_order * $item->quantity, 0, ',', '.') }}</span>
            </div>
            @endforeach
            <div class="pt-3 border-t border-outline-variant flex justify-between items-center">
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">Total</span>
                <span class="font-headline-xl text-headline-xl text-primary-container">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>
    </section>
</main>
@endsection