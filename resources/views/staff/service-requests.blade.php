@extends('layouts.cashier')

@section('title', 'Panggilan Pelayan - Coffe Paste')

@push('styles')
<style>
    .request-card { transition: all 0.2s ease; }
    .request-card:hover { transform: translateY(-1px); }
    .pulse-dot { animation: pulse-dot 1.5s ease-in-out infinite; }
    @keyframes pulse-dot { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }
</style>
@endpush

@section('content')
@include('staff.partials._sidebar_cashier')

<main class="ml-72 min-h-screen flex flex-col">
    <header class="sticky top-0 z-40 bg-surface flex justify-between items-center px-container-margin h-20 w-full">
        <div>
            <h1 class="font-headline-xl text-headline-xl text-on-surface">Panggilan Pelayan</h1>
            <p class="text-body-sm text-secondary" id="requestCount">Memuat...</p>
        </div>
        <div class="flex items-center gap-4">
            <span class="font-label-md text-label-md text-on-surface-variant">{{ auth()->user()->name }}</span>
            <div class="h-10 w-10 rounded-full bg-primary-fixed-dim flex items-center justify-center text-primary font-bold text-sm">
                {{ substr(auth()->user()->name, 0, 2) }}
            </div>
        </div>
    </header>

    <div class="p-8">
        <div class="max-w-4xl mx-auto space-y-4" id="requestsList">
            <div class="text-center text-on-surface-variant py-8">Memuat data...</div>
        </div>
    </div>
</main>

@push('scripts')
<script>
    const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });

    function loadRequests() {
        fetch('/staff/pending-requests')
            .then(r => r.json())
            .then(data => {
                const list = document.getElementById('requestsList');
                const count = document.getElementById('requestCount');
                count.textContent = data.length + ' panggilan';

                if (!data.length) {
                    list.innerHTML = `
                        <div class="text-center py-16">
                            <span class="material-symbols-outlined text-5xl text-secondary">check_circle</span>
                            <p class="font-headline-lg text-headline-lg text-on-surface mt-4">Tidak ada panggilan</p>
                            <p class="text-body-sm text-secondary mt-1">Semua permintaan sudah ditangani</p>
                        </div>
                    `;
                    return;
                }

                if (data.length > prevRequestCount && prevRequestCount > 0) {
                    new Audio('/notofikasi.mp3').play().catch(() => {});
                    Toast.fire({ icon: 'info', title: 'Panggilan baru masuk!' });
                }
                prevRequestCount = data.length;
                list.innerHTML = data.map(r => `
                    <div class="request-card bg-surface-container-lowest rounded-xl p-5 shadow-sm border border-outline-variant/20 flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-primary-fixed flex items-center justify-center text-primary flex-shrink-0">
                            <span class="material-symbols-outlined">
                                ${r.type === 'Tambah Air Minum' ? 'water_full' :
                                  r.type === 'Minta Alat Makan' ? 'restaurant' :
                                  r.type === 'Bersihkan Meja' ? 'mop' :
                                  r.type === 'Bantuan Menu' ? 'menu_book' : 'person_raised_hand'}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">Meja ${r.table_number}</span>
                                <span class="px-2 py-0.5 text-[10px] font-bold bg-primary-container/10 text-primary-container rounded-full uppercase">${r.type}</span>
                            </div>
                            <p class="text-body-sm text-secondary">${r.created_at}</p>
                        </div>
                        <button onclick="dismissRequest(${r.id})"
                                class="px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:brightness-110 transition-all active:scale-95 flex-shrink-0">
                            Selesai
                        </button>
                    </div>
                `).join('');
            })
            .catch(() => {
                document.getElementById('requestsList').innerHTML = `
                    <div class="text-center py-16">
                        <span class="material-symbols-outlined text-5xl text-secondary">cloud_off</span>
                        <p class="font-headline-lg text-headline-lg text-on-surface mt-4">Gagal memuat</p>
                        <p class="text-body-sm text-secondary mt-1">Coba refresh halaman</p>
                    </div>
                `;
            });
    }

    function dismissRequest(id) {
        fetch('/staff/dismiss-request/' + id, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        }).then(r => r.ok ? r.json() : Promise.reject())
          .then(() => {
              loadRequests();
              Toast.fire({ icon: 'success', title: 'Permintaan ditandai selesai' });
          })
          .catch(() => Toast.fire({ icon: 'error', title: 'Gagal' }));
    }

    loadRequests();
    setInterval(loadRequests, 5000);
</script>
@endpush
@endsection