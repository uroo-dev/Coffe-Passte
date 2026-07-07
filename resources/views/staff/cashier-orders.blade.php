@extends('layouts.cashier')

@section('title', 'Pesanan Meja ' . $table->table_number . ' - Coffe Paste')

@push('styles')
<style>
    .status-badge { font-size: 10px; font-weight: 700; letter-spacing: 0.05em; }
</style>
@endpush

@section('content')
@include('staff.partials._sidebar_cashier')

<main class="ml-72 min-h-screen flex flex-col">
    <header class="sticky top-0 z-40 bg-surface flex justify-between items-center px-container-margin h-20 w-full">
        <div class="flex items-center gap-4">
            <a href="{{ route('staff.cashier') }}" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-surface-container-low text-primary">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h1 class="font-headline-xl text-headline-xl text-on-surface">Meja {{ $table->table_number }}</h1>
                <p class="text-body-sm text-secondary">{{ $orders->count() }} pesanan</p>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <span class="px-3 py-1 text-label-md font-label-md rounded-full
                @if($table->status === 'empty') bg-green-100 text-green-800
                @elseif($table->status === 'occupied') bg-yellow-100 text-yellow-800
                @else bg-red-100 text-red-800 @endif">
                {{ str_replace('_', ' ', $table->status) }}
            </span>
            <span class="font-label-md text-label-md text-on-surface-variant">{{ auth()->user()->name }}</span>
            <div class="h-10 w-10 rounded-full bg-primary-fixed-dim flex items-center justify-center text-primary font-bold text-sm">
                {{ substr(auth()->user()->name, 0, 2) }}
            </div>
        </div>
    </header>

    <div class="p-8">
        <div class="max-w-3xl mx-auto space-y-6">
            @forelse($orders as $order)
            <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant/20 overflow-hidden">
                <div class="p-5 border-b border-outline-variant/20 flex justify-between items-center">
                    <div>
                        <span class="font-headline-lg text-headline-lg text-on-surface">#{{ $order->order_reference }}</span>
                        <span class="ml-3 px-2 py-0.5 status-badge rounded-full
                            @if($order->order_status === 'pending') bg-blue-100 text-blue-800
                            @elseif($order->order_status === 'cooking') bg-orange-100 text-orange-800
                            @elseif($order->order_status === 'ready') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $order->order_status }}
                        </span>
                    </div>
                    <span class="font-price-display text-price-display text-primary">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>

                <div class="p-5 space-y-3">
                    @foreach($order->items as $item)
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-label-md text-label-md text-on-surface">{{ $item->menu->name }}</p>
                            <p class="text-body-sm text-secondary">x{{ $item->quantity }} @if($item->notes) • {{ $item->notes }} @endif</p>
                        </div>
                        <span class="font-price-display text-price-display text-on-surface">Rp {{ number_format($item->price_at_order * $item->quantity, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>

                <div class="p-4 bg-surface-container-low border-t border-outline-variant/20 flex justify-end gap-3">
                    @if($order->payment_status === 'unpaid')
                    <form action="{{ route('staff.confirm-cash', $order) }}" method="POST">
                        @csrf
                        <button type="submit" onclick="return confirm('Konfirmasi pembayaran untuk #{{ $order->order_reference }}?')"
                                class="px-6 py-3 bg-primary-container text-white rounded-xl font-label-md hover:brightness-110 transition-all active:scale-[0.98]">
                            Konfirmasi Bayar
                        </button>
                    </form>
                    @else
                    <span class="px-4 py-2 bg-green-100 text-green-800 rounded-xl font-label-md flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">check_circle</span>
                        {{ ucfirst($order->payment_method) }} - {{ $order->payment_status }}
                    </span>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-16 text-secondary">
                <span class="material-symbols-outlined text-5xl mb-4">receipt_long</span>
                <p class="font-headline-lg text-headline-lg">Tidak ada pesanan</p>
                <p class="text-body-sm mt-1">Meja ini belum memiliki pesanan aktif</p>
            </div>
            @endforelse
        </div>
    </div>
</main>
@endsection