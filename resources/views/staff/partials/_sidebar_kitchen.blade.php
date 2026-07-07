<aside class="fixed left-0 top-0 h-full border-r border-outline-variant bg-surface-container-lowest w-72 flex flex-col z-50 shadow-sm">
    <div class="p-6 border-b border-outline-variant/30 bg-primary">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-white">restaurant</span>
            </div>
            <div>
                <h1 class="font-bold text-lg text-white leading-tight">Coffe Paste</h1>
                <p class="text-xs text-white/70 uppercase tracking-wider font-medium">Dapur</p>
            </div>
        </div>
    </div>
    <nav class="flex-grow py-4">
        <ul class="space-y-1 px-3">
            @php $current = request()->path(); @endphp
            <li>
                <a href="{{ route('staff.kitchen') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
                       {{ $current === 'staff/kitchen' ? 'bg-primary text-white shadow-md' : 'text-secondary hover:bg-surface-container-low hover:text-on-surface' }}">
                    <span class="material-symbols-outlined">cooking</span>
                    <span class="font-medium text-sm">Live Orders</span>
                    <span class="ml-auto px-2 py-0.5 text-[10px] font-bold bg-primary-container/20 text-primary-container rounded-full">LIVE</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.transactions') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
                       {{ $current === 'staff/transactions' ? 'bg-primary text-white shadow-md' : 'text-secondary hover:bg-surface-container-low hover:text-on-surface' }}">
                    <span class="material-symbols-outlined">history</span>
                    <span class="font-medium text-sm">Riwayat</span>
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
                <p class="text-[10px] text-secondary uppercase tracking-wider">Dapur</p>
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