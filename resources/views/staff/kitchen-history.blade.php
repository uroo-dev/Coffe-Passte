@extends('layouts.kitchen')

@section('title', 'Riwayat Dapur - Coffe Paste')

@section('content')
@include('staff.partials._sidebar_kitchen')

<main class="ml-72 min-h-screen flex flex-col">
    <header class="sticky top-0 z-40 bg-surface flex justify-between items-center px-container-margin h-20 w-full">
        <div>
            <h1 class="font-headline-xl text-headline-xl text-on-surface">Riwayat Pesanan</h1>
            <p class="text-body-sm text-secondary">{{ $orders->count() }} pesanan terakhir</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="h-10 w-10 rounded-full bg-primary-fixed-dim flex items-center justify-center text-primary font-bold text-sm">
                {{ substr(auth()->user()->name, 0, 2) }}
            </div>
        </div>
    </header>

    <div class="p-8">
        <div class="max-w-4xl mx-auto space-y-4">
            @forelse($orders as $order)
            <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant/20 p-5 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-surface-container-high flex items-center justify-center">
                        <span class="font-bold text-on-surface">M{{ $order->table->table_number }}</span>
                    </div>
                    <div>
                        <p class="font-label-md text-label-md text-on-surface">#{{ $order->order_reference }}</p>
                        <p class="text-body-sm text-secondary">{{ $order->items->count() }} item • Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 text-label-md font-label-md rounded-full
                        @if($order->order_status === 'completed') bg-green-100 text-green-800
                        @elseif($order->order_status === 'cancelled') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ $order->order_status }}
                    </span>
                    <span class="text-body-sm text-secondary">{{ $order->updated_at->format('H:i') }}</span>
                </div>
            </div>
            @empty
            <div class="text-center py-16 text-secondary">
                <span class="material-symbols-outlined text-5xl mb-4">history</span>
                <p class="font-headline-lg text-headline-lg">Belum ada riwayat</p>
            </div>
            @endforelse
        </div>
    </div>
</main>
@endsection