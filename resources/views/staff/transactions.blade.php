@extends('layouts.cashier')

@section('title', 'Transaksi - Coffe Paste')

@section('content')
<header class="fixed top-0 left-0 w-full z-50 flex justify-between items-center px-container-margin h-16 bg-surface border-b border-outline-variant shadow-sm">
    <div class="flex items-center gap-4">
        <span class="font-headline-xl text-headline-xl text-primary">Coffe Paste Transaksi</span>
    </div>
    <div class="flex items-center gap-6">
        <div class="hidden md:flex items-center bg-surface-container rounded-full px-4 py-1.5 gap-2 border border-outline-variant">
            <span class="material-symbols-outlined text-on-surface-variant">search</span>
            <input class="bg-transparent border-none focus:ring-0 text-body-sm w-64" placeholder="Cari transaksi..." type="text">
        </div>
        <div class="h-10 w-10 rounded-full bg-primary-fixed overflow-hidden flex items-center justify-center text-primary font-bold">SF</div>
    </div>
</header>

<div class="flex flex-1 pt-16 overflow-hidden">
    <nav class="hidden lg:flex fixed left-0 top-16 h-[calc(100vh-4rem)] flex-col p-stack-md z-40 bg-surface-container w-64 shadow-md">
        <div class="flex flex-col gap-1 mb-8 p-2">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-primary-container flex items-center justify-center text-white">
                    <span class="material-symbols-outlined">restaurant</span>
                </div>
                <div>
                    <h2 class="font-headline-lg text-headline-lg text-on-surface leading-tight">Main Kitchen</h2>
                    <p class="text-label-md text-on-surface-variant">Shift: Lunch</p>
                </div>
            </div>
        </div>
        <nav class="flex-1 flex flex-col gap-2">
            <a href="{{ route('staff.dashboard') }}" class="flex items-center gap-3 p-3 text-on-surface-variant hover:bg-surface-container-highest rounded-lg">
                <span class="material-symbols-outlined">grid_view</span>
                <span class="font-label-md">Floor Plan</span>
            </a>
            <a href="{{ route('staff.pos') }}" class="flex items-center gap-3 p-3 text-on-surface-variant hover:bg-surface-container-highest rounded-lg">
                <span class="material-symbols-outlined">point_of_sale</span>
                <span class="font-label-md">POS System</span>
            </a>
            <a href="{{ route('staff.kitchen') }}" class="flex items-center gap-3 p-3 text-on-surface-variant hover:bg-surface-container-highest rounded-lg">
                <span class="material-symbols-outlined">restaurant</span>
                <span class="font-label-md">Kitchen (KDS)</span>
            </a>
            <a href="{{ route('staff.transactions') }}" class="flex items-center gap-3 p-3 bg-primary-container text-on-primary-container rounded-lg font-bold shadow-sm">
                <span class="material-symbols-outlined">receipt_long</span>
                <span class="font-label-md">Transactions</span>
            </a>
        </nav>
        <div class="mt-auto flex flex-col gap-2 border-t border-outline-variant pt-4">
            <form method="POST" action="/logout" class="block">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 p-3 text-on-surface-variant hover:bg-surface-container-highest rounded-lg">
                    <span class="material-symbols-outlined">logout</span>
                    <span class="font-label-md">Logout</span>
                </button>
            </form>
        </div>
    </nav>

    <main class="lg:ml-64 mt-0 p-container-margin min-h-screen w-full overflow-y-auto">
        <div class="space-y-stack-lg">
            <div class="flex flex-col gap-stack-md">
                <div class="flex justify-between items-end">
                    <div>
                        <h1 class="font-headline-xl text-headline-xl text-on-surface">Riwayat Transaksi</h1>
                        <p class="text-on-surface-variant text-body-md">Kelola semua aktivitas pembayaran hari ini.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-stack-md">
                    <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant shadow-sm">
                        <span class="text-on-surface-variant font-label-md">Total Transaksi Hari Ini</span>
                        <span class="text-headline-xl font-headline-xl text-on-surface block mt-2">{{ $orders->total() }}</span>
                    </div>
                    <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant shadow-sm">
                        <span class="text-on-surface-variant font-label-md">Total Pendapatan</span>
                        <span class="text-headline-xl font-headline-xl text-primary block mt-2">Rp {{ number_format($orders->sum('total_amount'), 0, ',', '.') }}</span>
                    </div>
                    <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant shadow-sm">
                        <span class="text-on-surface-variant font-label-md">Metode Terpopuler</span>
                        <span class="text-headline-xl font-headline-xl text-on-surface block mt-2">QRIS</span>
                    </div>
                </div>
            </div>

            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant overflow-hidden shadow-md">
                <table class="w-full text-left">
                    <thead class="bg-surface-container text-on-surface font-label-md">
                        <tr>
                            <th class="px-6 py-4">Order ID</th>
                            <th class="px-6 py-4">Waktu</th>
                            <th class="px-6 py-4">Meja</th>
                            <th class="px-6 py-4">Metode</th>
                            <th class="px-6 py-4">Total</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @foreach($orders as $order)
                        <tr class="hover:bg-surface-container-low cursor-pointer transition-colors">
                            <td class="px-6 py-4 font-bold text-primary">{{ $order->order_reference }}</td>
                            <td class="px-6 py-4 text-body-sm">{{ $order->created_at->format('H:i') }}</td>
                            <td class="px-6 py-4 text-body-sm">Meja {{ $order->table->table_number }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="text-body-sm">{{ $order->payment_method ? ucfirst($order->payment_method) : '-' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-price-display">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="px-3 py-1 rounded-full text-[12px] font-bold
                                        @if($order->order_status === 'completed') bg-green-100 text-green-800
                                        @elseif($order->order_status === 'cancelled') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ ucfirst($order->order_status) }}
                                    </span>

                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-6 py-4 bg-surface-container-lowest border-t border-outline-variant flex items-center justify-between">
                    <span class="text-body-sm text-on-surface-variant">Menampilkan {{ $orders->firstItem() ?? 0 }} - {{ $orders->lastItem() ?? 0 }} dari {{ $orders->total() }} transaksi</span>
                    <div class="flex gap-2">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
