@extends('layouts.kitchen')

@section('title', 'KDS - Dapur Coffe Paste')

@push('styles')
<style>
    .kds-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem; }
    .timer-warning { animation: pulse-red 2s infinite; }
    @keyframes pulse-red { 0% { color: inherit; } 50% { color: #ba1a1a; } 100% { color: inherit; } }
</style>
@endpush

@section('content')
@include('staff.partials._sidebar_kitchen')

<main class="ml-72 min-h-screen flex flex-col">
    <header class="sticky top-0 z-40 bg-surface flex justify-between items-center px-container-margin h-20 w-full">
        <div>
            <h1 class="font-headline-xl text-headline-xl text-on-surface">Live Orders</h1>
            <p class="text-body-sm text-secondary">Memantau {{ $orders->count() }} pesanan</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="flex items-center bg-surface-container-high px-4 py-2 rounded-xl border border-outline-variant">
                <span class="material-symbols-outlined mr-2 text-primary">timer</span>
                <span class="font-bold text-primary">Antrean: {{ $orders->where('order_status', 'pending')->count() }}</span>
            </div>
            <div class="h-10 w-10 rounded-full bg-primary-fixed-dim flex items-center justify-center text-primary font-bold text-sm">
                {{ substr(auth()->user()->name, 0, 2) }}
            </div>
        </div>
    </header>

    <div class="p-8">
        <div class="max-w-7xl mx-auto">
            <div class="kds-grid">
                @forelse($orders as $order)
                <div class="bg-surface-container-lowest rounded-xl shadow-md flex flex-col overflow-hidden
                    @if($order->order_status === 'cooking') border-l-8 border-primary
                    @else border-l-8 border-outline-variant @endif">
                    <div class="p-4 border-b border-outline-variant flex justify-between items-start
                        @if($order->order_status === 'cooking') bg-primary-fixed/20 @endif">
                        <div>
                            <div class="text-label-md font-bold tracking-wider uppercase mb-1
                                @if($order->order_status === 'cooking') text-primary
                                @else text-on-surface-variant @endif">
                                {{ $order->order_status === 'cooking' ? 'Cooking...' : 'New Order' }}
                            </div>
                            <h3 class="font-headline-lg-mobile text-headline-lg-mobile font-bold">Meja {{ $order->table->table_number }}</h3>
                        </div>
                        <div class="text-right">
                            <div class="font-price-display text-price-display text-on-surface">{{ $order->created_at->format('H:i') }}</div>
                            <div class="text-body-sm text-on-surface-variant">{{ $order->order_reference }}</div>
                        </div>
                    </div>

                    <div class="p-4 flex-1 space-y-3">
                        @foreach($order->items as $item)
                        <div class="flex items-start gap-3">
                            <span class="font-bold text-lg min-w-[20px]">{{ $item->quantity }}x</span>
                            <div class="flex-1">
                                <p class="font-bold text-on-surface">{{ $item->menu->name }}</p>
                                @if($item->notes)
                                <p class="text-error font-bold text-sm tracking-wide">{{ $item->notes }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="p-4 bg-surface-container-low grid grid-cols-2 gap-2">
                        @if($order->order_status === 'pending')
                        <form action="{{ route('staff.kitchen.start', $order) }}" method="POST" class="contents">
                            @csrf
                            <button type="submit" class="bg-secondary-container text-on-secondary-container py-3 rounded-lg font-bold hover:bg-secondary-fixed transition-colors active:scale-95">Mulai Masak</button>
                        </form>
                        <form action="{{ route('staff.kitchen.finish', $order) }}" method="POST" class="contents">
                            @csrf
                            <button type="submit" class="bg-primary text-on-primary py-3 rounded-lg font-bold hover:opacity-90 transition-opacity active:scale-95">Selesai Masak</button>
                        </form>
                        @elseif($order->order_status === 'cooking')
                        <div class="col-span-1 bg-primary/10 text-primary py-3 rounded-lg font-bold flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-sm">check_circle</span> Dimasak
                        </div>
                        <form action="{{ route('staff.kitchen.finish', $order) }}" method="POST" class="contents">
                            @csrf
                            <button type="submit" class="bg-primary text-on-primary py-3 rounded-lg font-bold hover:opacity-90 transition-opacity active:scale-95">Selesai Masak</button>
                        </form>
                        @endif
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-16 text-secondary">
                    <span class="material-symbols-outlined text-6xl">check_circle</span>
                    <p class="font-headline-lg-mobile mt-4">Tidak ada pesanan masuk</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</main>
@endsection
