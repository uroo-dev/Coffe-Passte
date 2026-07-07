@extends('layouts.cashier')

@section('title', 'Dashboard - Staff Coffe Paste')

@push('styles')
<style>
    .floor-plan-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 1.5rem; }
    .table-card { transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
    .table-card:active { transform: scale(0.95); }
</style>
@endpush

@section('content')
@include('staff.partials._sidebar_cashier')

<main class="ml-72 min-h-screen flex flex-col">
    <header class="sticky top-0 z-40 bg-surface flex justify-between items-center px-container-margin h-20 w-full">
        <div>
            <h1 class="font-headline-xl text-headline-xl text-on-surface">Floor Plan</h1>
            <p class="text-body-sm text-secondary" id="tableCount">{{ $tables->count() }} Meja</p>
        </div>
        <div class="flex items-center gap-4">
            <span class="font-label-md text-label-md text-on-surface-variant">{{ auth()->user()->name }}</span>
            <div class="h-10 w-10 rounded-full bg-primary-fixed-dim flex items-center justify-center text-primary font-bold text-sm">
                {{ substr(auth()->user()->name, 0, 2) }}
            </div>
        </div>
    </header>

    <div class="p-8">
        <div class="max-w-5xl mx-auto">
            <div class="flex flex-wrap gap-4 mb-8 bg-surface-container-low p-4 rounded-2xl border border-outline-variant/30">
                <div class="flex items-center gap-2"><span class="w-4 h-4 rounded-full bg-green-500"></span><span class="text-label-md text-on-surface-variant">Kosong</span></div>
                <div class="flex items-center gap-2"><span class="w-4 h-4 rounded-full bg-yellow-400"></span><span class="text-label-md text-on-surface-variant">Terisi</span></div>
                <div class="flex items-center gap-2"><span class="w-4 h-4 rounded-full bg-red-500"></span><span class="text-label-md text-on-surface-variant">Minta Bayar</span></div>
            </div>

            <div class="floor-plan-grid" id="tablesGrid">
                @foreach($tables as $table)
                <div class="table-card bg-surface-container-lowest border-2 rounded-2xl p-4 flex flex-col items-center justify-center gap-2 shadow-sm cursor-pointer hover:shadow-md
                    @if($table->status === 'empty') border-green-500/20 hover:border-green-500/50
                    @elseif($table->status === 'occupied') border-yellow-400/20 hover:border-yellow-400/50
                    @else border-red-500/20 hover:border-red-500/50 @endif">
                    <span class="font-headline-lg text-headline-lg
                        @if($table->status === 'empty') text-green-600
                        @elseif($table->status === 'occupied') text-yellow-600
                        @else text-red-600 @endif">
                        {{ $table->table_number }}
                    </span>
                    <span class="px-2 py-0.5 text-[10px] font-bold rounded uppercase
                        @if($table->status === 'empty') bg-green-100 text-green-800
                        @elseif($table->status === 'occupied') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800 @endif">
                        {{ str_replace('_', ' ', $table->status) }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</main>

@push('scripts')
<script>
    const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 5000, timerProgressBar: true });
    let prevTables = {};

    function pollTables() {
        fetch('{{ route("staff.table-statuses") }}')
            .then(r => r.json())
            .then(tables => {
                document.getElementById('tableCount').textContent = tables.length + ' Meja';

                const grid = document.getElementById('tablesGrid');
                let html = '';
                let hasNewPayment = false;

                tables.forEach(t => {
                    const prev = prevTables[t.id];
                    const isNew = prev && prev.status !== 'waiting_payment' && t.status === 'waiting_payment';

                    if (isNew) {
                        hasNewPayment = true;
                        try {
                            const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACAf39/f4B/f3+AgH9/f3+AgH9/f3+Af39/f3+Af39/f3+AgH9/f3+Af39/f4B/f3+Af39/gH9/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f4B/f39/gH9/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f4B/f39/gH9/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f4B/f39/gH9/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f4B/f39/gH9/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f4B/f39/gH9/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f4B/f39/gH9/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f4B/f39/gH9/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f4B/f39/gH9/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f4B/f39/gH9/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f4B/f39/gH9/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f4B/f39/gH9/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f4B/f39/gH9/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f4B/f39/gH9/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f4B/f39/gH9/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f4B/f39/gH9/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f4B/f39/gH9/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f4B/f39/gH9/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f4B/f39/gH9/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f4B/f39/gH9/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f4B/f39/gH9/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f4B/f39/gH9/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f4B/f39/gH9/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f4B/f39/gH9/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f4B/f39/gH9/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f4B/f39/gH9/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f4B/f39/gH9/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f4B/f39/gH9/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f4B/f39/gH9/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f4B/f39/gH9/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f4B/f39/gH9/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f4B/f39/gH9/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f4B/f39/gH9/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f4B/f39/gH9/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f4B/f39/gH9/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f4B/f39/gH9/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f4B/f39/gH9/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f4B/f39/gH9/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f3+Af39/f4B/f39/gH9/f3+Af39/f3+A');
                            audio.volume = 0.3;
                            audio.play();
                        } catch(e) {}

                        Toast.fire({
                            icon: 'warning',
                            title: 'Meja ' + t.table_number + ' minta bayar!',
                            timer: 8000
                        });

                        if (Notification.permission === 'granted') {
                            new Notification('Coffe Paste - Minta Bayar', {
                                body: 'Meja ' + t.table_number + ' memanggil kasir',
                                icon: '/favicon.ico'
                            });
                        }
                    }

                    prevTables[t.id] = t;

                    const statusClass = t.status === 'empty' ? 'border-green-500/20 hover:border-green-500/50' :
                                        t.status === 'occupied' ? 'border-yellow-400/20 hover:border-yellow-400/50' :
                                        'border-red-500/20 hover:border-red-500/50';
                    const textClass = t.status === 'empty' ? 'text-green-600' :
                                      t.status === 'occupied' ? 'text-yellow-600' : 'text-red-600';
                    const badgeClass = t.status === 'empty' ? 'bg-green-100 text-green-800' :
                                       t.status === 'occupied' ? 'bg-yellow-100 text-yellow-800' :
                                       'bg-red-100 text-red-800';
                    const label = t.status.replace('_', ' ');

                    html += `<div class="table-card bg-surface-container-lowest border-2 rounded-2xl p-4 flex flex-col items-center justify-center gap-2 shadow-sm cursor-pointer hover:shadow-md ${statusClass}">
                        <span class="font-headline-lg text-headline-lg ${textClass}">${t.table_number}</span>
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded uppercase ${badgeClass}">${label}</span>
                    </div>`;
                });

                grid.innerHTML = html;

                if (hasNewPayment) {
                    document.title = '🔔 Notifikasi - Coffe Paste';
                    setTimeout(() => document.title = 'Dashboard - Coffe Paste', 5000);
                }
            })
            .catch(() => {});
    }

    if (Notification && Notification.permission === 'default') {
        Notification.requestPermission();
    }

    setInterval(pollTables, 8000);
    pollTables();
</script>
@endpush
@endsection
