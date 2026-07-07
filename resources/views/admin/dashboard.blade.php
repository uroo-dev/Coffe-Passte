@extends('layouts.admin')

@section('title', 'Dashboard - Admin Coffe Paste')

@section('content')
<aside class="fixed left-0 top-0 h-full border-r border-outline-variant bg-surface-container-lowest w-72 flex flex-col z-50">
    <div class="p-6">
        <h1 class="font-headline-lg text-headline-lg font-bold text-primary">Coffe Paste</h1>
        <p class="text-body-sm text-secondary">Restaurant Master Portal</p>
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

<main class="ml-72 min-h-screen flex flex-col">
    <header class="sticky top-0 z-40 bg-surface flex justify-between items-center px-container-margin h-20 w-full">
        <div class="flex items-center gap-6">
            <div class="relative group">
                <span class="absolute inset-y-0 left-3 flex items-center text-secondary group-focus-within:text-primary transition-colors">
                    <span class="material-symbols-outlined">search</span>
                </span>
                <input class="pl-10 pr-4 py-2 bg-surface-container-low border-none rounded-full focus:ring-2 focus:ring-primary-container text-body-sm w-64 transition-all" placeholder="Search analytics..." type="text">
            </div>
        </div>
        <div class="flex items-center gap-4">
            <button class="w-10 h-10 flex items-center justify-center rounded-full text-secondary hover:bg-surface-container-high transition-colors relative">
                <span class="material-symbols-outlined">notifications</span>
                <span class="absolute top-2 right-2 w-2 h-2 bg-primary-container rounded-full"></span>
            </button>
            <div class="h-8 w-px bg-outline-variant mx-2"></div>
            <span class="font-label-md text-label-md text-on-surface-variant">{{ auth()->user()->name }}</span>
        </div>
    </header>

    <div class="p-8 space-y-8">
        <div class="flex justify-between items-end">
            <div>
                <h2 class="font-headline-xl text-headline-xl text-on-surface">Executive Overview</h2>
                <p class="text-body-md text-secondary">Real-time performance tracking</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-surface-container-lowest p-6 rounded-2xl shadow-[0_4px_12px_rgba(0,0,0,0.05)] border border-outline-variant cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 bg-primary-fixed rounded-xl flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">payments</span>
                    </div>
                    <span class="text-xs font-bold text-on-surface-variant bg-surface-container-high px-2 py-1 rounded-md">Hari Ini</span>
                </div>
                <p class="text-body-sm text-secondary font-medium">Today's Revenue</p>
                <h3 class="font-price-display text-[28px] text-on-surface mt-1" id="todayRevenue">Memuat...</h3>
            </div>
            <div class="bg-surface-container-lowest p-6 rounded-2xl shadow-[0_4px_12px_rgba(0,0,0,0.05)] border border-outline-variant cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 bg-secondary-container rounded-xl flex items-center justify-center text-secondary">
                        <span class="material-symbols-outlined">shopping_bag</span>
                    </div>
                    <span class="text-xs font-bold text-on-surface-variant bg-surface-container-high px-2 py-1 rounded-md">Hari Ini</span>
                </div>
                <p class="text-body-sm text-secondary font-medium">Total Orders</p>
                <h3 class="font-headline-xl text-headline-xl text-on-surface mt-1" id="totalOrders">Memuat...</h3>
            </div>
            <div class="bg-surface-container-lowest p-6 rounded-2xl shadow-[0_4px_12px_rgba(0,0,0,0.05)] border border-outline-variant cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 bg-tertiary-fixed rounded-xl flex items-center justify-center text-tertiary">
                        <span class="material-symbols-outlined">table_bar</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="w-2 h-2 bg-primary rounded-full animate-pulse"></span>
                        <span class="text-[10px] font-bold text-on-surface">LIVE</span>
                    </div>
                </div>
                <p class="text-body-sm text-secondary font-medium">Active Tables</p>
                <h3 class="font-headline-xl text-headline-xl text-on-surface mt-1"><span id="occupiedTables">Memuat...</span></h3>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12 lg:col-span-8 bg-surface-container-lowest p-8 rounded-3xl shadow-[0_4px_12px_rgba(0,0,0,0.05)] border border-outline-variant">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h4 class="font-headline-lg text-headline-lg text-on-surface">Peak Hours Analysis</h4>
                        <p class="text-body-sm text-secondary">Hourly order distribution</p>
                    </div>
                </div>
                <div class="chart-container flex items-end justify-between gap-2 pt-4" style="position:relative;height:300px;width:100%;">
                    <canvas id="hourlyChart"></canvas>
                </div>
            </div>
            <div class="col-span-12 lg:col-span-4 bg-surface-container-lowest p-6 rounded-3xl shadow-[0_4px_12px_rgba(0,0,0,0.05)] border border-outline-variant flex flex-col">
                <div class="flex items-center gap-2 mb-6">
                    <span class="material-symbols-outlined text-primary">trending_up</span>
                    <h4 class="font-headline-lg text-headline-lg text-on-surface">Top Menu</h4>
                </div>
                <div class="space-y-4 flex-grow overflow-y-auto custom-scrollbar pr-2" id="topMenusList">
                    <p class="text-body-sm text-secondary">Memuat data...</p>
                </div>
            </div>
        </div>

        <section class="space-y-6">
            <div class="flex justify-between items-center">
                <h4 class="font-headline-lg text-headline-lg text-on-surface">Menu Terlaris</h4>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" id="topSellingGrid">
                <div class="col-span-full text-center text-secondary py-8">Memuat data...</div>
            </div>
        </section>
    </div>

    <footer class="mt-auto p-6 bg-surface-container flex justify-between items-center border-t border-outline-variant">
        <p class="text-body-sm text-secondary">&copy; {{ date('Y') }} Coffe Paste. All rights reserved.</p>
    </footer>
</main>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    let hourlyChart = null;

    fetch('/admin/api/dashboard')
        .then(r => r.json())
        .then(data => {
            document.getElementById('todayRevenue').textContent = 'Rp ' + (data.today_revenue || 0).toLocaleString('id-ID');
            document.getElementById('totalOrders').textContent = (data.total_orders_today || 0) + ' Orders';
            document.getElementById('occupiedTables').textContent = (data.occupied_tables || 0) + ' Meja';

            const topMenusList = document.getElementById('topMenusList');
            topMenusList.innerHTML = '';
            if (data.top_menus && data.top_menus.length) {
                data.top_menus.forEach(item => {
                    topMenusList.innerHTML += `
                        <div class="flex items-center justify-between p-3 rounded-xl bg-surface-container-low border border-outline-variant/50">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-surface flex items-center justify-center text-on-surface-variant">
                                    <span class="material-symbols-outlined">restaurant</span>
                                </div>
                                <div>
                                    <p class="font-label-md text-label-md text-on-surface">${item.menu?.name || 'Unknown'}</p>
                                    <p class="text-[12px] text-on-surface-variant">${item.total_qty} terjual</p>
                                </div>
                            </div>
                        </div>
                    `;
                });
            } else {
                topMenusList.innerHTML = '<p class="text-body-sm text-secondary">Belum ada data</p>';
            }

            const topSellingGrid = document.getElementById('topSellingGrid');
            topSellingGrid.innerHTML = '';
            if (data.top_menus && data.top_menus.length) {
                data.top_menus.forEach(item => {
                    topSellingGrid.innerHTML += `
                        <div class="bg-surface-container-lowest rounded-2xl overflow-hidden shadow-sm border border-outline-variant group hover:shadow-md transition-shadow">
                            <div class="p-6 flex items-center justify-center h-40 bg-surface-container-low">
                                <span class="material-symbols-outlined text-6xl text-primary">restaurant_menu</span>
                            </div>
                            <div class="p-4">
                                <h5 class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface truncate">${item.menu?.name || 'Unknown'}</h5>
                                <div class="flex justify-between items-center mt-2">
                                    <p class="text-body-sm text-secondary">${item.total_qty} Sales</p>
                                    <p class="font-price-display text-price-display text-primary">Rp ${(item.menu?.price || 0).toLocaleString('id-ID')}</p>
                                </div>
                            </div>
                        </div>
                    `;
                });
            } else {
                topSellingGrid.innerHTML = '<div class="col-span-full text-center text-secondary py-8">Belum ada data</div>';
            }

            const ctx = document.getElementById('hourlyChart').getContext('2d');
            const hours = Array.from({length: 24}, (_, i) => i + ':00');
            const values = new Array(24).fill(0);
            if (data.hourly_orders) {
                data.hourly_orders.forEach(h => { if (h.hour >= 0 && h.hour < 24) values[h.hour] = h.total; });
            }

            hourlyChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: hours,
                    datasets: [{
                        label: 'Orders',
                        data: values,
                        borderColor: '#ff5c00',
                        backgroundColor: 'rgba(255, 92, 0, 0.08)',
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#ff5c00',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        borderWidth: 3,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: '#8f7065', font: { size: 11 } }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: '#e7eefe' },
                            ticks: { color: '#8f7065', font: { size: 11 }, stepSize: 1 }
                        }
                    }
                }
            });
        })
        .catch(() => {
            document.getElementById('todayRevenue').textContent = 'Rp 0';
            document.getElementById('totalOrders').textContent = '0 Orders';
            document.getElementById('occupiedTables').textContent = '0 Meja';
        });

    const searchInput = document.querySelector('input[type="text"]');
    if (searchInput) {
        searchInput.addEventListener('focus', () => searchInput.classList.add('w-80'));
        searchInput.addEventListener('blur', () => searchInput.classList.remove('w-80'));
    }
</script>
@endpush
