<aside class="fixed left-0 top-0 h-full border-r border-outline-variant bg-surface-container-lowest w-72 flex flex-col z-50 shadow-sm">
    <div class="p-6 border-b border-outline-variant/30">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center text-white shadow-sm">
                <span class="material-symbols-outlined">receipt</span>
            </div>
            <div>
                <h1 class="font-bold text-lg text-primary leading-tight">Coffe Paste</h1>
                <p class="text-xs text-secondary uppercase tracking-wider font-medium">Kasir</p>
            </div>
        </div>
    </div>
    <nav class="flex-grow py-4">
        <ul class="space-y-1 px-3">
            @php $current = request()->path(); @endphp
            <li>
                <a href="{{ route('staff.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
                       {{ $current === 'staff/dashboard' ? 'bg-primary text-white shadow-md' : 'text-secondary hover:bg-surface-container-low hover:text-on-surface' }}">
                    <span class="material-symbols-outlined">grid_view</span>
                    <span class="font-medium text-sm">Floor Plan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.cashier') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
                       {{ str_starts_with($current, 'cashier') ? 'bg-primary text-white shadow-md' : 'text-secondary hover:bg-surface-container-low hover:text-on-surface' }}">
                    <span class="material-symbols-outlined">payments</span>
                    <span class="font-medium text-sm">Kasir</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.pos') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
                       {{ $current === 'staff/pos' ? 'bg-primary text-white shadow-md' : 'text-secondary hover:bg-surface-container-low hover:text-on-surface' }}">
                    <span class="material-symbols-outlined">point_of_sale</span>
                    <span class="font-medium text-sm">POS System</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.service-requests') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
                       {{ $current === 'staff/service-requests' ? 'bg-primary text-white shadow-md' : 'text-secondary hover:bg-surface-container-low hover:text-on-surface' }}">
                    <span class="material-symbols-outlined">person_raised_hand</span>
                    <span class="font-medium text-sm">Panggilan</span>
                    <span class="ml-auto px-2 py-0.5 text-[10px] font-bold bg-primary-container/20 text-primary-container rounded-full" id="pendingBadge">0</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.transactions') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
                       {{ $current === 'staff/transactions' ? 'bg-primary text-white shadow-md' : 'text-secondary hover:bg-surface-container-low hover:text-on-surface' }}">
                    <span class="material-symbols-outlined">receipt_long</span>
                    <span class="font-medium text-sm">Transaksi</span>
                </a>
            </li>
        </ul>
    </nav>
    <div class="p-4 border-t border-outline-variant/30 mt-auto">
        <div class="flex items-center gap-3 p-3 bg-surface-container-low rounded-xl">
            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-sm">
                {{ substr(auth()->user()->name, 0, 2) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-medium text-sm text-on-surface truncate">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-secondary uppercase tracking-wider">Kasir</p>
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