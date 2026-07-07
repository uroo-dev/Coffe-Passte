<aside class="hidden lg:flex fixed left-0 top-0 h-full border-r border-outline-variant bg-surface-container-lowest w-72 flex-col z-50">
    <div class="p-6">
        <h1 class="font-headline-lg text-headline-lg font-bold text-primary">Coffe Paste</h1>
        <p class="text-body-sm text-secondary">{{ auth()->user()->isOwner() ? 'Owner' : 'Admin' }}</p>
    </div>
    <nav class="flex-grow py-4">
        <ul class="space-y-1">
            @php $current = request()->path(); @endphp
            <li>
                <a href="/admin/dashboard" class="flex items-center gap-3 px-4 py-3 {{ $current === 'admin/dashboard' ? 'bg-primary-container text-on-primary-container' : 'text-secondary hover:bg-surface-container-low' }} rounded-lg mx-2 transition-colors">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span class="font-label-md text-label-md">Dashboard</span>
                </a>
            </li>
            @if(auth()->user()->isOwner())
            <li>
                <a href="/admin/users" class="flex items-center gap-3 px-4 py-3 {{ str_starts_with($current, 'admin/users') ? 'bg-primary-container text-on-primary-container' : 'text-secondary hover:bg-surface-container-low' }} rounded-lg mx-2 transition-colors">
                    <span class="material-symbols-outlined">manage_accounts</span>
                    <span class="font-label-md text-label-md">Kelola Akun</span>
                </a>
            </li>
            @endif
            <li>
                <a href="/admin/categories" class="flex items-center gap-3 px-4 py-3 {{ $current === 'admin/categories' ? 'bg-primary-container text-on-primary-container' : 'text-secondary hover:bg-surface-container-low' }} rounded-lg mx-2 transition-colors">
                    <span class="material-symbols-outlined">category</span>
                    <span class="font-label-md text-label-md">Kategori</span>
                </a>
            </li>
            <li>
                <a href="/admin/menus" class="flex items-center gap-3 px-4 py-3 {{ $current === 'admin/menus' ? 'bg-primary-container text-on-primary-container' : 'text-secondary hover:bg-surface-container-low' }} rounded-lg mx-2 transition-colors">
                    <span class="material-symbols-outlined">menu_book</span>
                    <span class="font-label-md text-label-md">Menu Catalog</span>
                </a>
            </li>
            <li>
                <a href="/admin/tables" class="flex items-center gap-3 px-4 py-3 {{ $current === 'admin/tables' ? 'bg-primary-container text-on-primary-container' : 'text-secondary hover:bg-surface-container-low' }} rounded-lg mx-2 transition-colors">
                    <span class="material-symbols-outlined">table_restaurant</span>
                    <span class="font-label-md text-label-md">Table Management</span>
                </a>
            </li>
            <li>
                <a href="/admin/orders" class="flex items-center gap-3 px-4 py-3 {{ $current === 'admin/orders' ? 'bg-primary-container text-on-primary-container' : 'text-secondary hover:bg-surface-container-low' }} rounded-lg mx-2 transition-colors">
                    <span class="material-symbols-outlined">receipt_long</span>
                    <span class="font-label-md text-label-md">Financial Reports</span>
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
                <p class="text-[10px] text-secondary uppercase tracking-wider">{{ auth()->user()->isOwner() ? 'Owner' : 'Admin' }}</p>
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
