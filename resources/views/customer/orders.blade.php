@extends('layouts.customer')

@section('title', 'Live Orders - Coffe Paste')

@push('styles')
<style>
    .live-dot { width: 8px; height: 8px; border-radius: 50%; animation: livePulse 1.5s ease-in-out infinite; }
    @keyframes livePulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }
</style>
@endpush

@section('content')
<header class="fixed top-0 left-0 w-full z-50 flex justify-between items-center px-container-margin py-stack-md bg-surface/80 backdrop-blur-md shadow-sm">
    <div class="flex items-center gap-3">
        <a href="{{ route('customer.landing', $table->qr_token) }}"
           class="flex items-center justify-center p-2 rounded-full hover:bg-surface-variant/50 transition-colors">
            <span class="material-symbols-outlined text-primary">arrow_back</span>
        </a>
        <h1 class="font-headline-lg-mobile text-headline-lg-mobile font-bold text-primary">Live Orders</h1>
    </div>
</header>

<main class="mt-20 px-container-margin space-y-4 pb-32">
    @php $liveOrders = $orders->whereIn('order_status', ['pending', 'cooking', 'completed']); @endphp

    @if($liveOrders->isNotEmpty())
    <div class="flex items-center gap-2 mb-2">
        <span class="live-dot bg-primary-container inline-block"></span>
        <span class="font-label-md text-label-md text-primary-container font-bold">Live — {{ $liveOrders->count() }} pesanan aktif</span>
    </div>

    @foreach($liveOrders as $order)
    <div class="block bg-surface-container-lowest rounded-xl p-4 shadow-sm border border-primary-container/20 active:scale-[0.98] transition-transform">
        <div class="flex justify-between items-start mb-3">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary-container text-sm">
                    @if($order->order_status === 'pending') schedule
                    @elseif($order->order_status === 'cooking') cooking
                    @else check_circle @endif
                </span>
                <p class="font-label-md text-on-surface">{{ $order->order_reference }}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-[11px] font-bold
                @if($order->order_status === 'pending') bg-yellow-100 text-yellow-800
                @elseif($order->order_status === 'cooking') bg-blue-100 text-blue-800
                @else bg-green-100 text-green-800 @endif">
                @if($order->order_status === 'pending') Menunggu
                @elseif($order->order_status === 'cooking') Dimasak
                @else Siap @endif
            </span>
            @if($order->order_status !== 'pending')
            <div class="flex items-center gap-1 mt-1">
                <span class="material-symbols-outlined text-[10px] text-green-600" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                <span class="text-[10px] text-green-600 font-medium">{{ $order->updated_at->format('H:i') }}</span>
            </div>
            @endif
        </div>
        <div class="space-y-1">
            @foreach($order->items as $item)
            <div class="flex justify-between text-body-sm">
                <span class="text-on-surface">{{ $item->menu->name }} x{{ $item->quantity }}</span>
                <span class="text-on-surface-variant">Rp {{ number_format($item->price_at_order * $item->quantity, 0, ',', '.') }}</span>
            </div>
            @endforeach
        </div>
        <div class="flex justify-between items-center mt-3 pt-3 border-t border-outline-variant/10">
            <span class="text-body-sm text-on-surface-variant">{{ $order->created_at->format('H:i') }}</span>
            <span class="font-price-display text-price-display text-primary">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
        </div>
    </div>
    @endforeach

    @else
    <div class="text-center py-16">
        <span class="material-symbols-outlined text-6xl text-secondary">receipt_long</span>
        <p class="font-headline-lg-mobile text-on-surface mt-4">Belum ada pesanan</p>
        <p class="text-body-sm text-secondary mt-2">Silakan lihat menu untuk memesan</p>
        <a href="{{ route('customer.menu', $table->qr_token) }}"
           class="inline-block mt-6 px-8 py-3 bg-primary-container text-white rounded-full font-label-md">
            Lihat Menu
        </a>
    </div>
    @endif
</main>

<nav class="fixed bottom-0 left-0 w-full z-50 flex justify-around items-center px-4 pb-4 pt-2 bg-surface-container/95 backdrop-blur-lg border-t border-outline-variant/30 rounded-t-xl">
    <a href="{{ route('customer.menu', $table->qr_token) }}"
       class="flex flex-col items-center justify-center text-on-surface-variant px-4 py-1">
        <span class="material-symbols-outlined">restaurant_menu</span>
        <span class="font-label-md text-label-md">Menu</span>
    </a>
    <a href="{{ route('customer.orders', $table->qr_token) }}"
       class="flex flex-col items-center justify-center bg-primary-container text-white rounded-full px-4 py-1 active:scale-90 transition-transform">
        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">receipt_long</span>
        <span class="font-label-md text-label-md">Pesanan</span>
    </a>
    <a href="{{ route('customer.call-staff', $table->qr_token) }}"
       class="flex flex-col items-center justify-center text-on-surface-variant px-4 py-1">
        <span class="material-symbols-outlined">person_raised_hand</span>
        <span class="font-label-md text-label-md">Panggil</span>
    </a>
    <a href="{{ route('customer.payment', $table->qr_token) }}"
       class="flex flex-col items-center justify-center text-on-surface-variant px-4 py-1">
        <span class="material-symbols-outlined">payments</span>
        <span class="font-label-md text-label-md">Billing</span>
    </a>
</nav>
@push('scripts')
<style>
    .order-toast { position: fixed; bottom: 90px; left: 50%; transform: translateX(-50%); z-index: 999; animation: slideUpToast 0.4s cubic-bezier(0.175,0.885,0.32,1.275) forwards; }
    @keyframes slideUpToast { from { transform: translateX(-50%) translateY(100%); opacity: 0; } to { transform: translateX(-50%) translateY(0); opacity: 1; } }
</style>
<script>
    let prevStatuses = JSON.parse(sessionStorage.getItem('orderStatuses') || '{}');
    setInterval(() => {
        fetch('{{ route("customer.orders", $table->qr_token) }}?json=1')
            .then(r => r.json())
            .then(orders => {
                orders.filter(o => o.order_status === 'completed').forEach(o => {
                    if (prevStatuses[o.id] !== 'completed') {
                        const toast = document.createElement('div');
                        toast.className = 'order-toast bg-green-600 text-white px-5 py-3 rounded-xl shadow-2xl flex items-center gap-3';
                        toast.innerHTML = '<span class="material-symbols-outlined" style="font-variation-settings:\'FILL\' 1;">check_circle</span><div><p class="font-bold text-sm">Pesanan Siap!</p><p class="text-xs text-white/80">' + o.order_reference + '</p></div>';
                        document.body.appendChild(toast);
                        setTimeout(() => { toast.style.transform = 'translateX(-50%) translateY(100%)'; toast.style.opacity = '0'; setTimeout(() => toast.remove(), 400); }, 4000);
                        if (Notification.permission === 'granted') {
                            new Notification('Pesanan Siap!', { body: o.order_reference + ' siap diantar', icon: '/favicon.ico' });
                        }
                    }
                    prevStatuses[o.id] = 'completed';
                });
                sessionStorage.setItem('orderStatuses', JSON.stringify(prevStatuses));
            })
            .catch(() => {});
    }, 8000);
    if (Notification.permission === 'default') Notification.requestPermission();
</script>
@endpush
@endsection
