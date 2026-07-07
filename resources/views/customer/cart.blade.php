@extends('layouts.customer')

@section('title', 'Keranjang - Coffe Paste')

@section('content')
<header class="fixed top-0 left-0 w-full z-50 flex justify-between items-center px-container-margin py-stack-md bg-surface/80 backdrop-blur-md shadow-sm">
    <div class="flex items-center gap-stack-sm">
        <a href="{{ route('customer.menu', $table->qr_token) }}"
           class="material-symbols-outlined text-primary p-2 hover:bg-surface-variant/50 rounded-full transition-colors active:scale-95">arrow_back</a>
        <h1 class="font-headline-lg-mobile text-headline-lg-mobile font-bold text-primary">Coffe Paste</h1>
    </div>
    <button class="material-symbols-outlined text-primary p-2 hover:bg-surface-variant/50 rounded-full">notifications</button>
</header>

<main class="mt-20 px-container-margin flex flex-col gap-stack-lg max-w-2xl mx-auto">
    <section class="flex flex-col gap-1">
        <h2 class="font-headline-xl text-headline-xl text-on-surface">Keranjang Saya</h2>
        <p class="font-body-md text-secondary">Pastikan pesanan Anda sudah sesuai sebelum dikirim.</p>
    </section>

    <section class="bg-surface-container rounded-xl p-4 flex items-center justify-between border border-primary/10">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center text-white">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">table_restaurant</span>
            </div>
            <div>
                <p class="font-label-md text-on-surface-variant">Pesanan Aktif</p>
                <p class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">Meja {{ $table->table_number }}</p>
            </div>
        </div>
    </section>

    <section class="flex flex-col gap-stack-md">
        <div class="flex justify-between items-center">
            <h3 class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">Pilihan Anda</h3>
            <span class="font-label-md text-primary bg-primary-fixed px-3 py-1 rounded-full cart-count-label">0 Item</span>
        </div>
        <div class="flex flex-col gap-stack-sm" id="cartItems"></div>
    </section>

    <section class="flex flex-col gap-stack-sm">
        <label class="font-label-md text-on-surface flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">edit_note</span>
            Catatan Tambahan
        </label>
        <textarea id="orderNotes"
                  class="w-full bg-surface-container-low border border-outline-variant/30 rounded-xl p-4 font-body-md text-on-surface placeholder:text-secondary-fixed-dim focus:ring-2 focus:ring-primary-container focus:border-transparent transition-all outline-none min-h-[100px]"
                  placeholder="Contoh: Tanpa daun bawang, pisahkan saus..."></textarea>
    </section>

    <section class="bg-surface-container-high rounded-2xl p-6 flex flex-col gap-3">
        <div class="flex justify-between font-body-md text-on-surface-variant">
            <span>Subtotal</span>
            <span class="cart-subtotal">Rp 0</span>
        </div>
        <div class="h-px bg-outline-variant/30 my-2"></div>
        <div class="flex justify-between font-headline-lg text-headline-lg text-primary">
            <span>Total</span>
            <span class="cart-total">Rp 0</span>
        </div>
    </section>
</main>

<div class="fixed bottom-0 left-0 w-full z-50">
    <div class="px-container-margin pb-24">
        <button onclick="checkout()"
                class="w-full bg-primary-container text-white py-4 rounded-full font-headline-lg-mobile shadow-lg shadow-primary-container/20 flex items-center justify-center gap-3 hover:scale-[1.02] active:scale-95 transition-all duration-200 group">
            Konfirmasi & Kirim ke Dapur
            <span class="material-symbols-outlined transition-transform group-hover:translate-x-1">restaurant</span>
        </button>
    </div>
    <nav class="fixed bottom-0 left-0 w-full flex justify-around items-center px-4 pb-4 pt-2 bg-surface-container/95 backdrop-blur-lg border-t border-outline-variant/30 rounded-t-xl">
        <a href="{{ route('customer.menu', $table->qr_token) }}"
           class="flex flex-col items-center justify-center text-on-surface-variant px-4 py-1">
            <span class="material-symbols-outlined">restaurant_menu</span>
            <span class="font-label-md text-label-md">Menu</span>
        </a>
        <a href="{{ route('customer.cart', $table->qr_token) }}"
           class="flex flex-col items-center justify-center bg-primary-container text-white rounded-full px-4 py-1 active:scale-90 transition-transform">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">receipt_long</span>
            <span class="font-label-md text-label-md">Pesanan</span>
        </a>
        <a href="{{ route('customer.call-staff', $table->qr_token) }}"
           class="flex flex-col items-center justify-center text-on-surface-variant px-4 py-1">
            <span class="material-symbols-outlined">person_raised_hand</span>
            <span class="font-label-md text-label-md">Panggil</span>
        </a>
        <button onclick="showPayment()"
           class="flex flex-col items-center justify-center text-on-surface-variant px-4 py-1">
            <span class="material-symbols-outlined">payments</span>
            <span class="font-label-md text-label-md">Billing</span>
        </button>
    </nav>
</div>

@push('scripts')
<script>
    const cart = JSON.parse(localStorage.getItem('cart_{{ $table->id }}') || '[]');
    function renderCart() {
        const container = document.getElementById('cartItems');
        if (cart.length === 0) {
            container.innerHTML = '<div class="text-center py-8 text-secondary"><span class="material-symbols-outlined text-4xl">shopping_cart</span><p class="mt-2">Keranjang kosong</p></div>';
            return;
        }
        container.innerHTML = cart.map((item, i) => `
            <div class="bg-surface-container-lowest p-3 rounded-xl shadow-sm flex gap-4 items-center">
                <div class="flex-grow flex flex-col justify-between h-full">
                    <div>
                        <h4 class="font-label-md text-on-surface">${item.name}</h4>
                    </div>
                    <div class="flex justify-between items-end mt-2">
                        <span class="font-price-display text-price-display text-primary">Rp ${(item.price * item.qty).toLocaleString('id-ID')}</span>
                        <div class="flex items-center bg-surface-container rounded-full px-2 py-1 gap-3">
                            <button class="w-6 h-6 flex items-center justify-center text-primary material-symbols-outlined text-sm" onclick="updateQty(${i}, -1)">remove</button>
                            <span class="font-label-md text-on-surface min-w-[20px] text-center">${item.qty}</span>
                            <button class="w-6 h-6 flex items-center justify-center bg-primary-container text-white rounded-full material-symbols-outlined text-sm" onclick="updateQty(${i}, 1)">add</button>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
        updateTotals();
    }
    function updateQty(index, delta) {
        cart[index].qty += delta;
        if (cart[index].qty <= 0) cart.splice(index, 1);
        localStorage.setItem('cart_{{ $table->id }}', JSON.stringify(cart));
        renderCart();
    }
    function updateTotals() {
        const subtotal = cart.reduce((s, i) => s + i.price * i.qty, 0);
        document.querySelector('.cart-count-label').textContent = cart.reduce((s, i) => s + i.qty, 0) + ' Item';
        document.querySelector('.cart-subtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
        document.querySelector('.cart-total').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
    }
    function checkout() {
        if (cart.length === 0) { alert('Keranjang masih kosong!'); return; }
        const notes = document.getElementById('orderNotes').value;
        const items = cart.map(i => ({ menu_id: i.id, quantity: i.qty, notes: notes }));
        const btn = event.currentTarget;
        btn.innerHTML = '<div class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div> Mengirim...';
        btn.disabled = true;
        fetch('{{ route("customer.checkout", $table->qr_token) }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ items })
        })
        .then(r => r.json())
        .then(data => {
            localStorage.removeItem('cart_{{ $table->id }}');
            window.location.href = '{{ url("/customer/order") }}/' + data.order.id + '/confirmation?token={{ $table->qr_token }}';
        })
        .catch(err => { alert('Gagal mengirim pesanan'); btn.disabled = false; btn.innerHTML = 'Konfirmasi & Kirim ke Dapur'; });
    }
    function showPayment() { window.location.href = '{{ route("customer.payment", $table->qr_token) }}'; }
    renderCart();
</script>
@endpush
@endsection
