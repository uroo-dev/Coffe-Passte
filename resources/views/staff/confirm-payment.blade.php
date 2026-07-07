<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Konfirmasi Pembayaran - Coffe Paste</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { lexend: ['Lexend', 'sans-serif'] },
                    colors: {
                        primary: '#481C23', 'primary-container': '#A64B5A', 'on-primary-container': '#FFFFFF',
                        secondary: '#7B5B60', 'surface-container-lowest': '#FFFFFF',
                        'surface-container-low': '#FFF8F6', 'surface-container': '#F7EFEC',
                        'surface-container-high': '#EDE0DC', 'on-surface': '#2A1B18', 'outline-variant': '#DAC4BE',
                    }
                }
            }
        }
    </script>
    <style>
        * { font-family: 'Lexend', sans-serif; }
        body { background: #F7EFEC; }
        .card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.08);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="card w-full max-w-md p-6 space-y-6">
        <div class="text-center">
            <div class="w-16 h-16 bg-[#A64B5A]/10 rounded-full flex items-center justify-center mx-auto mb-3">
                <span class="material-symbols-outlined text-[32px] text-[#A64B5A]">payments</span>
            </div>
            <h1 class="text-2xl font-bold text-[#481C23]">Konfirmasi Pembayaran</h1>
            <p class="text-[#7B5B60] mt-1">Meja {{ $order->table->table_number }}</p>
        </div>

        <div class="bg-[#FFF8F6] rounded-xl p-4 space-y-3">
            <div class="flex justify-between items-center pb-2 border-b border-[#DAC4BE]">
                <span class="font-semibold text-[#481C23]">Order #{{ $order->order_reference }}</span>
                <span class="text-sm px-2 py-0.5 bg-[#A64B5A]/10 text-[#A64B5A] rounded-full">Menunggu</span>
            </div>
            @foreach($order->items as $item)
            <div class="flex justify-between items-start">
                <div>
                    <p class="font-medium text-[#2A1B18]">{{ $item->menu->name }}</p>
                    <p class="text-sm text-[#7B5B60]">x{{ $item->quantity }}</p>
                </div>
                <p class="font-semibold text-[#2A1B18]">Rp {{ number_format($item->price_at_order * $item->quantity, 0, ',', '.') }}</p>
            </div>
            @endforeach
            <div class="flex justify-between items-center pt-2 border-t border-[#DAC4BE]">
                <span class="font-bold text-lg text-[#481C23]">Total</span>
                <span class="font-bold text-lg text-[#A64B5A]">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded-xl text-sm text-center">{{ session('error') }}</div>
        @endif

        <form action="{{ route('staff.confirm-payment', $order) }}" method="POST">
            @csrf
            <button type="submit"
                    onclick="this.disabled=true;this.innerHTML='<span class=\"inline-block w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin\"></span> Memproses...'"
                    class="w-full py-4 bg-[#A64B5A] text-white font-semibold rounded-xl text-lg hover:bg-[#481C23] transition-all duration-200 active:scale-[0.98]">
                Konfirmasi Pembayaran
            </button>
        </form>

        <p class="text-center text-sm text-[#7B5B60]">
            <span class="material-symbols-outlined text-[16px] align-text-bottom">qr_code_scanner</span>
            Scan oleh kasir untuk konfirmasi
        </p>
    </div>
</body>
</html>