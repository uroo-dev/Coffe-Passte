@extends('layouts.customer')

@section('title', 'Menu - Coffe Paste')

@section('content')
<header class="fixed top-0 left-0 w-full z-50 flex justify-between items-center px-container-margin py-stack-md bg-surface/80 backdrop-blur-md shadow-sm">
    <div class="flex items-center gap-3">
        <a href="{{ route('customer.landing', $table->qr_token) }}"
           class="flex items-center justify-center p-2 rounded-full hover:bg-surface-variant/50 transition-colors">
            <span class="material-symbols-outlined text-primary">arrow_back</span>
        </a>
        <h1 class="font-headline-lg-mobile text-headline-lg-mobile font-bold text-primary">Coffe Paste</h1>
    </div>
    <button class="flex items-center justify-center p-2 rounded-full hover:bg-surface-variant/50 transition-colors relative" onclick="showCart()">
        <span class="material-symbols-outlined text-primary">shopping_basket</span>
        <span class="absolute -top-1 -right-1 w-5 h-5 bg-primary-container text-white text-[10px] font-bold rounded-full flex items-center justify-center cart-count hidden">0</span>
    </button>
</header>

<main class="mt-20 px-container-margin space-y-stack-md mb-32">
    <section class="relative">
        <div class="flex items-center bg-surface-container-low rounded-xl px-4 py-3 border border-outline-variant/20 focus-within:border-primary-container transition-all">
            <span class="material-symbols-outlined text-secondary mr-3">search</span>
            <input id="searchMenu" class="bg-transparent border-none focus:ring-0 w-full text-body-md placeholder-secondary/60"
                   placeholder="Cari hidangan favoritmu..." type="text">
        </div>
    </section>

    <section class="-mx-container-margin overflow-x-auto hide-scrollbar flex items-center px-container-margin py-2 gap-4" id="categoryTabs">
        <button class="flex-shrink-0 font-label-md text-label-md text-primary active-tab-indicator" data-category="all">
            Semua
        </button>
        @foreach($categories as $category)
        <button class="flex-shrink-0 font-label-md text-label-md text-secondary hover:text-primary transition-colors" data-category="{{ $category->slug }}">
            {{ $category->name }}
        </button>
        @endforeach
    </section>

    <div class="grid grid-cols-2 gap-gutter" id="menuGrid">
        @foreach($categories as $category)
            @foreach($category->menus as $menu)
            <div class="menu-item bg-surface-container-lowest rounded-2xl overflow-hidden shadow-[0_4px_12px_rgba(0,0,0,0.05)] border border-outline-variant/10 active:scale-[0.98] transition-transform duration-100"
                 data-category="{{ $category->slug }}"
                 data-name="{{ strtolower($menu->name) }}">
                <div class="relative aspect-square bg-surface-variant">
                    @if($menu->image)
                    <img class="w-full h-full object-cover" src="{{ $menu->image }}" alt="{{ $menu->name }}">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-secondary">
                        <span class="material-symbols-outlined text-4xl">restaurant_menu</span>
                    </div>
                    @endif
                </div>
                <div class="p-3 flex flex-col justify-between">
                    <div>
                        <h3 class="font-label-md text-on-surface truncate">{{ $menu->name }}</h3>
                        @if($menu->description)
                        <p class="text-[12px] leading-tight text-secondary line-clamp-2 mt-1">{{ $menu->description }}</p>
                        @endif
                    </div>
                    <div class="flex justify-between items-center mt-3">
                        <span class="font-price-display text-price-display text-primary">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                        <button class="w-7 h-7 rounded-full bg-primary-container text-white flex items-center justify-center active:scale-90 transition-transform"
                                onclick="addToCart({{ $menu->id }}, '{{ $menu->name }}', {{ $menu->price }})">
                            <span class="material-symbols-outlined text-xs">add</span>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        @endforeach
    </div>
</main>

<div class="fixed bottom-24 left-1/2 -translate-x-1/2 w-[calc(100%-2.5rem)] max-w-md z-40 hidden" id="cartFloating">
    <button class="w-full bg-primary-container text-white px-6 py-4 rounded-2xl flex items-center justify-between shadow-2xl active:scale-95 transition-all duration-150" onclick="showCart()">
        <div class="flex items-center gap-3">
            <div class="bg-white/20 p-2 rounded-lg">
                <span class="material-symbols-outlined">shopping_basket</span>
            </div>
            <div class="text-left">
                <p class="font-label-md text-xs text-white/80 uppercase tracking-wider">
                    <span class="cart-count-label">0</span> Item dipilih
                </p>
                <p class="font-headline-lg-mobile text-lg cart-total-label">Rp 0</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="font-label-md">Lihat Keranjang</span>
            <span class="material-symbols-outlined">arrow_forward_ios</span>
        </div>
    </button>
</div>

<nav class="fixed bottom-0 left-0 w-full z-50 flex justify-around items-center px-4 pb-4 pt-2 bg-surface-container/95 backdrop-blur-lg border-t border-outline-variant/30 shadow-[0_-4px_12px_rgba(0,0,0,0.05)] rounded-t-xl">
    <a href="{{ route('customer.menu', $table->qr_token) }}"
       class="flex flex-col items-center justify-center bg-primary-container text-white rounded-full px-4 py-1 active:scale-90 transition-transform">
        <span class="material-symbols-outlined">restaurant_menu</span>
        <span class="font-label-md text-[10px] mt-0.5">Menu</span>
    </a>
    <a href="{{ route('customer.orders', $table->qr_token) }}"
       class="flex flex-col items-center justify-center text-on-surface-variant active:scale-90 transition-transform">
        <span class="material-symbols-outlined">receipt_long</span>
        <span class="font-label-md text-[10px] mt-0.5">Pesanan</span>
    </a>
    <a href="{{ route('customer.call-staff', $table->qr_token) }}"
       class="flex flex-col items-center justify-center text-on-surface-variant active:scale-90 transition-transform">
        <span class="material-symbols-outlined">person_raised_hand</span>
        <span class="font-label-md text-[10px] mt-0.5">Panggil</span>
    </a>
    <button onclick="showCart()"
       class="flex flex-col items-center justify-center text-on-surface-variant active:scale-90 transition-transform">
        <span class="material-symbols-outlined">payments</span>
        <span class="font-label-md text-[10px] mt-0.5">Billing</span>
    </button>
</nav>

@push('scripts')
<script>
    let cart = JSON.parse(localStorage.getItem('cart_{{ $table->id }}') || '[]');
    function updateCartUI() {
        const count = cart.reduce((s, i) => s + i.qty, 0);
        const total = cart.reduce((s, i) => s + i.price * i.qty, 0);
        document.querySelector('.cart-count').textContent = count;
        document.querySelector('.cart-count').classList.toggle('hidden', count === 0);
        document.querySelector('.cart-count-label').textContent = count;
        document.querySelector('.cart-total-label').textContent = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('cartFloating').classList.toggle('hidden', count === 0);
    }
    function addToCart(id, name, price) {
        const idx = cart.findIndex(i => i.id === id);
        if (idx > -1) cart[idx].qty++;
        else cart.push({ id, name, price, qty: 1 });
        localStorage.setItem('cart_{{ $table->id }}', JSON.stringify(cart));
        updateCartUI();
    }
    function showCart() { window.location.href = '{{ route("customer.cart", $table->qr_token) }}'; }
    updateCartUI();

    document.getElementById('searchMenu').addEventListener('input', function() {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.menu-item').forEach(el => {
            el.style.display = el.dataset.name.includes(q) ? '' : 'none';
        });
    });

    document.querySelectorAll('#categoryTabs button').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('#categoryTabs button').forEach(b => {
                b.classList.remove('text-primary', 'active-tab-indicator');
                b.classList.add('text-secondary');
            });
            this.classList.remove('text-secondary');
            this.classList.add('text-primary', 'active-tab-indicator');
            const cat = this.dataset.category;
            document.querySelectorAll('.menu-item').forEach(el => {
                el.style.display = (cat === 'all' || el.dataset.category === cat) ? '' : 'none';
            });
        });
    });
</script>
<style>
    .active-tab-indicator { position: relative; }
    .active-tab-indicator::after {
        content: ''; position: absolute; bottom: -4px; left: 50%;
        transform: translateX(-50%); width: 4px; height: 4px;
        background-color: #ff5c00; border-radius: 50%;
    }
</style>
@endpush
@endsection
