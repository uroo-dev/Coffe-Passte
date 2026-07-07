@extends('layouts.cashier')

@section('title', 'Kasir - Coffe Paste')

@push('styles')
<style>
    .table-card { transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
    .table-card:active { transform: scale(0.95); }
</style>
@endpush

@section('content')
@include('staff.partials._sidebar_cashier')

<main class="ml-72 min-h-screen flex flex-col">
    <header class="sticky top-0 z-40 bg-surface flex justify-between items-center px-container-margin h-20 w-full">
        <div>
            <h1 class="font-headline-xl text-headline-xl text-on-surface">Kasir</h1>
            <p class="text-body-sm text-secondary">{{ $tables->count() }} Meja</p>
        </div>
        <div class="flex items-center gap-4">
            <span class="font-label-md text-label-md text-on-surface-variant">{{ auth()->user()->name }}</span>
            <div class="h-10 w-10 rounded-full bg-primary-fixed-dim flex items-center justify-center text-primary font-bold text-sm">
                {{ substr(auth()->user()->name, 0, 2) }}
            </div>
        </div>
    </header>

    <div class="p-8">
        <div class="max-w-5xl mx-auto">
            <div class="flex flex-wrap gap-4 mb-8 bg-surface-container-low p-4 rounded-2xl border border-outline-variant/30">
                <div class="flex items-center gap-2"><span class="w-4 h-4 rounded-full bg-green-500"></span><span class="text-label-md text-on-surface-variant">Kosong</span></div>
                <div class="flex items-center gap-2"><span class="w-4 h-4 rounded-full bg-yellow-400"></span><span class="text-label-md text-on-surface-variant">Terisi</span></div>
                <div class="flex items-center gap-2"><span class="w-4 h-4 rounded-full bg-red-500"></span><span class="text-label-md text-on-surface-variant">Minta Bayar</span></div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach($tables as $table)
                <a href="{{ route('staff.cashier-orders', $table) }}"
                   class="table-card bg-surface-container-lowest border-2 rounded-2xl p-5 flex flex-col items-center justify-center gap-3 shadow-sm hover:shadow-md
                       @if($table->status === 'empty') border-green-500/20 hover:border-green-500/50
                       @elseif($table->status === 'occupied') border-yellow-400/20 hover:border-yellow-400/50
                       @else border-red-500/20 hover:border-red-500/50 @endif">
                    <span class="font-headline-lg text-headline-lg
                        @if($table->status === 'empty') text-green-600
                        @elseif($table->status === 'occupied') text-yellow-600
                        @else text-red-600 @endif">
                        {{ $table->table_number }}
                    </span>
                    <span class="px-2 py-0.5 text-[10px] font-bold rounded uppercase
                        @if($table->status === 'empty') bg-green-100 text-green-800
                        @elseif($table->status === 'occupied') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800 @endif">
                        {{ str_replace('_', ' ', $table->status) }}
                    </span>
                    @if($table->orders_count > 0)
                    <span class="text-label-sm text-on-surface-variant">{{ $table->orders_count }} pesanan</span>
                    @endif
                </a>
                @endforeach
            </div>
        </div>
    </div>
</main>
@endsection