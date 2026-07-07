@extends('layouts.cashier')

@section('title', 'POS - Coffe Paste')

@push('styles')
<style>
    .active-ring { box-shadow: 0 0 0 2px #ff5c00; }
</style>
@endpush

@section('content')
<header class="fixed top-0 left-0 w-full z-50 flex justify-between items-center px-container-margin h-16 bg-surface border-b border-outline-variant shadow-sm">
    <div class="flex items-center gap-stack-md">
        <span class="font-headline-xl text-headline-xl text-primary">Coffe Paste POS</span>
    </div>
    <div class="flex items-center gap-stack-md">
        <button class="material-symbols-outlined p-2 text-primary hover:bg-secondary-container rounded-full">notifications</button>
        <button class="material-symbols-outlined p-2 text-primary hover:bg-secondary-container rounded-full">settings</button>
        <div class="h-10 w-10 rounded-full bg-primary-fixed overflow-hidden flex items-center justify-center text-primary font-bold">SF</div>
    </div>
</header>

<div class="flex flex-1 pt-16 overflow-hidden">
    <nav class="hidden lg:flex fixed left-0 top-16 h-[calc(100vh-4rem)] flex-col p-stack-md z-40 bg-surface-container w-64 shadow-md">
        <div class="flex items-center gap-3 mb-stack-lg p-2">
            <div class="w-10 h-10 bg-primary-container rounded-lg flex items-center justify-center text-white">
                <span class="material-symbols-outlined">restaurant</span>
            </div>
            <div>
                <h2 class="font-headline-lg text-headline-lg text-on-surface leading-none">Main Kitchen</h2>
                <p class="text-label-md text-on-surface-variant">Shift: Lunch</p>
            </div>
        </div>
        <nav class="flex-1 flex flex-col gap-2">
            <a href="{{ route('staff.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-on-surface-variant hover:text-on-surface hover:bg-surface-container-highest rounded-lg">
                <span class="material-symbols-outlined">grid_view</span> Floor Plan
            </a>
            <a href="{{ route('staff.pos') }}" class="flex items-center gap-3 px-4 py-3 bg-primary-container text-on-primary-container rounded-lg font-bold shadow-sm">
                <span class="material-symbols-outlined">point_of_sale</span> POS System
            </a>
            <a href="{{ route('staff.kitchen') }}" class="flex items-center gap-3 px-4 py-3 text-on-surface-variant hover:text-on-surface hover:bg-surface-container-highest rounded-lg">
                <span class="material-symbols-outlined">restaurant</span> Kitchen (KDS)
            </a>
            <a href="{{ route('staff.transactions') }}" class="flex items-center gap-3 px-4 py-3 text-on-surface-variant hover:text-on-surface hover:bg-surface-container-highest rounded-lg">
                <span class="material-symbols-outlined">receipt_long</span> Transactions
            </a>
        </nav>
        <div class="mt-auto pt-stack-md border-t border-outline-variant/30">
            <form method="POST" action="/logout" class="block">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-on-surface-variant hover:text-on-surface rounded-lg">
                    <span class="material-symbols-outlined">logout</span> Logout
                </button>
            </form>
        </div>
    </nav>

    <main class="lg:ml-64 mt-0 p-stack-md lg:p-stack-lg bg-background min-h-[calc(100vh-4rem)] w-full overflow-y-auto">
        <div class="max-w-7xl mx-auto flex flex-col lg:grid lg:grid-cols-12 gap-stack-lg">
            <div class="lg:col-span-7 flex flex-col gap-stack-md">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="font-headline-xl text-headline-xl text-on-surface">Pilih Meja</h1>
                    </div>
                </div>

                <div class="grid grid-cols-5 gap-3">
                    @foreach($tables as $table)
                    <button onclick="selectTable({{ $table->id }}, '{{ $table->table_number }}')"
                        class="p-4 rounded-xl border-2 text-center transition-all
                        @if($table->status === 'empty') border-green-500/30 bg-green-50 hover:border-green-500
                        @elseif($table->status === 'occupied') border-yellow-500/30 bg-yellow-50 hover:border-yellow-500
                        @else border-red-500/30 bg-red-50 hover:border-red-500 @endif">
                        <span class="font-headline-lg text-headline-lg
                            @if($table->status === 'empty') text-green-600
                            @elseif($table->status === 'occupied') text-yellow-600
                            @else text-red-600 @endif">
                            {{ $table->table_number }}
                        </span>
                        <p class="text-[10px] font-bold uppercase mt-1
                            @if($table->status === 'empty') text-green-600
                            @elseif($table->status === 'occupied') text-yellow-600
                            @else text-red-600 @endif">
                            {{ str_replace('_', ' ', $table->status) }}
                        </p>
                    </button>
                    @endforeach
                </div>

                <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant/20 flex-1" id="orderPanel">
                    <div class="p-stack-md bg-surface-container-low border-b border-outline-variant/20 flex justify-between items-center">
                        <span class="font-bold text-on-surface">Pesanan <span id="selectedTableLabel">-</span></span>
                    </div>
                    <div class="p-8 text-center text-secondary">
                        <span class="material-symbols-outlined text-4xl">touch_app</span>
                        <p class="mt-2">Pilih meja untuk mulai membuat pesanan</p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5 flex flex-col gap-stack-md">
                <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant/20 p-stack-md">
                    <h3 class="font-bold text-on-surface mb-stack-md">Daftar Menu</h3>
                    <div class="space-y-2 max-h-[500px] overflow-y-auto">
                        @foreach($menus as $menu)
                        <div class="flex items-center justify-between p-3 rounded-lg bg-surface-container-low hover:bg-surface-container-high transition-colors cursor-pointer"
                             onclick="addItemToOrder({{ $menu->id }}, '{{ $menu->name }}', {{ $menu->price }})">
                            <div>
                                <p class="font-label-md text-on-surface">{{ $menu->name }}</p>
                                <p class="text-body-sm text-secondary">{{ $menu->category->name }}</p>
                            </div>
                            <span class="font-price-display text-price-display text-primary">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="space-y-stack-sm">
                    <form id="posOrderForm" method="POST" action="{{ route('staff.pos.create') }}">
                        @csrf
                        <input type="hidden" name="table_id" id="posTableId">
                        <input type="hidden" name="items" id="posItems">
                        <button type="submit" onclick="return submitPosOrder()"
                                class="w-full py-4 bg-primary text-white rounded-xl font-bold text-headline-lg-mobile shadow-lg shadow-primary/20 hover:brightness-110 active:scale-[0.98] transition-all flex items-center justify-center gap-3">
                            <span class="material-symbols-outlined">point_of_sale</span>
                            Buat Pesanan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

@push('scripts')
<script>
    let selectedTable = null;
    let orderItems = [];

    function selectTable(id, number) {
        selectedTable = id;
        document.getElementById('selectedTableLabel').textContent = 'Meja ' + number;
        document.getElementById('posTableId').value = id;
    }

    function addItemToOrder(id, name, price) {
        if (!selectedTable) { alert('Pilih meja terlebih dahulu!'); return; }
        const idx = orderItems.findIndex(i => i.menu_id === id);
        if (idx > -1) orderItems[idx].quantity++;
        else orderItems.push({ menu_id: id, quantity: 1, notes: null });
        renderOrderItems();
    }

    function renderOrderItems() {
        const panel = document.getElementById('orderPanel');
        if (orderItems.length === 0) {
            panel.innerHTML = `
                <div class="p-stack-md bg-surface-container-low border-b border-outline-variant/20 flex justify-between items-center">
                    <span class="font-bold text-on-surface">Pesanan <span id="selectedTableLabel">${document.getElementById('selectedTableLabel').textContent}</span></span>
                </div>
                <div class="p-8 text-center text-secondary">
                    <span class="material-symbols-outlined text-4xl">add_shopping_cart</span>
                    <p class="mt-2">Tambahkan item menu</p>
                </div>`;
            return;
        }
        let html = `<div class="p-stack-md bg-surface-container-low border-b border-outline-variant/20 flex justify-between items-center">
                        <span class="font-bold text-on-surface">Pesanan <span id="selectedTableLabel">${document.getElementById('selectedTableLabel').textContent}</span></span>
                        <span class="text-body-sm">${orderItems.reduce((s,i) => s + i.quantity, 0)} item</span>
                    </div>
                    <div class="p-4 space-y-3">`;
        let total = 0;
        orderItems.forEach((item, idx) => {
            total += item.price_at_order ? item.price_at_order * item.quantity : 0;
            html += `<div class="flex justify-between items-center p-2 bg-surface-container-low rounded-lg">
                        <div>
                            <p class="font-label-md">${item.name || 'Item'}</p>
                            <p class="text-body-sm text-secondary">x${item.quantity}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="font-price-display">Rp ${((item.price_at_order || 0) * item.quantity).toLocaleString('id-ID')}</span>
                            <button onclick="removePosItem(${idx})" class="text-error material-symbols-outlined text-sm">delete</button>
                        </div>
                    </div>`;
        });
        html += `<div class="flex justify-between font-bold pt-2 border-t border-outline-variant/20">
                    <span>Total</span>
                    <span class="text-primary">Rp ${total.toLocaleString('id-ID')}</span>
                 </div></div>`;
        panel.innerHTML = html;
    }

    function removePosItem(index) {
        orderItems.splice(index, 1);
        renderOrderItems();
    }

    function submitPosOrder() {
        if (!selectedTable) { alert('Pilih meja!'); return false; }
        if (orderItems.length === 0) { alert('Tambahkan item!'); return false; }
        document.getElementById('posItems').value = JSON.stringify(orderItems);
        return true;
    }
</script>
@endpush
@endsection
