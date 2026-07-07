<aside class="fixed left-0 top-0 h-full border-r border-outline-variant bg-surface-container-lowest w-72 flex flex-col z-50">
    <div class="p-6">
        <h1 class="font-headline-lg text-headline-lg font-bold text-primary">Coffe Paste</h1>
        <p class="text-body-sm text-secondary">{{ auth()->user()->role === 'staff_cashier' ? 'Staff Kasir' : 'Staff Dapur' }}</p>
    </div>
    <nav class="flex-grow py-4">
        <ul class="space-y-1">
            @php $current = request()->path(); @endphp
            <li>
                <a href="{{ route('staff.dashboard') }}" class="flex items-center gap-3 px-4 py-3 {{ $current === 'staff/dashboard' ? 'bg-primary-container text-on-primary-container' : 'text-secondary hover:bg-surface-container-low' }} rounded-lg mx-2 transition-colors">
                    <span class="material-symbols-outlined">grid_view</span>
                    <span class="font-label-md text-label-md">Floor Plan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.cashier') }}" class="flex items-center gap-3 px-4 py-3 {{ str_starts_with($current, 'cashier') ? 'bg-primary-container text-on-primary-container' : 'text-secondary hover:bg-surface-container-low' }} rounded-lg mx-2 transition-colors">
                    <span class="material-symbols-outlined">payments</span>
                    <span class="font-label-md text-label-md">Kasir</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.pos') }}" class="flex items-center gap-3 px-4 py-3 {{ $current === 'staff/pos' ? 'bg-primary-container text-on-primary-container' : 'text-secondary hover:bg-surface-container-low' }} rounded-lg mx-2 transition-colors">
                    <span class="material-symbols-outlined">point_of_sale</span>
                    <span class="font-label-md text-label-md">POS System</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.kitchen') }}" class="flex items-center gap-3 px-4 py-3 {{ $current === 'staff/kitchen' ? 'bg-primary-container text-on-primary-container' : 'text-secondary hover:bg-surface-container-low' }} rounded-lg mx-2 transition-colors">
                    <span class="material-symbols-outlined">restaurant</span>
                    <span class="font-label-md text-label-md">Kitchen (KDS)</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.transactions') }}" class="flex items-center gap-3 px-4 py-3 {{ $current === 'staff/transactions' ? 'bg-primary-container text-on-primary-container' : 'text-secondary hover:bg-surface-container-low' }} rounded-lg mx-2 transition-colors">
                    <span class="material-symbols-outlined">receipt_long</span>
                    <span class="font-label-md text-label-md">Transactions</span>
                </a>
            </li>
        </ul>
    </nav>
    <div class="p-4 border-t border-outline-variant mt-auto">
        <div class="flex items-center gap-3 p-2 bg-surface-container-low rounded-xl">
            <div class="w-10 h-10 rounded-full bg-primary-fixed-dim flex items-center justify-center text-primary font-bold text-sm">
                {{ substr(auth()->user()->name, 0, 2) }}
            </div>
            <div class="flex-1">
                <p class="font-label-md text-label-md text-on-surface">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-secondary uppercase tracking-wider">{{ auth()->user()->role === 'staff_cashier' ? 'Kasir' : 'Dapur' }}</p>
            </div>
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="p-2 text-secondary hover:text-error rounded-lg transition-colors" title="Logout">
                    <span class="material-symbols-outlined">logout</span>
                </button>
            </form>
        </div>
    </div>
</aside>
