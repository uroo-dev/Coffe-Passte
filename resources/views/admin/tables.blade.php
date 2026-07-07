@extends('layouts.admin')

@section('title', 'Meja - Admin Coffe Paste')

@section('content')
@include('admin._sidebar')

<main class="lg:ml-72 min-h-screen flex flex-col">
    <header class="sticky top-0 z-40 bg-surface flex justify-between items-center px-container-margin h-20 w-full">
        <div>
            <h1 class="font-headline-xl text-headline-xl text-on-surface">Table Management</h1>
            <p class="text-body-sm text-secondary">Kelola meja dan QR Code</p>
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
            <div class="flex justify-between items-center mb-8">
                <div>
                    <p class="text-on-surface-variant font-body-md">Semua meja restoran</p>
                </div>
                <button onclick="showCreateForm()" class="flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold hover:opacity-90 transition-all" style="background: #a73a00; color: #ffffff;">
                    <span class="material-symbols-outlined text-lg">add</span>
                    Tambah Meja
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="tablesGrid">
                <div class="col-span-full text-center text-on-surface-variant py-8">Memuat data...</div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true });

    function loadTables() {
        fetch('/admin/api/tables')
            .then(r => r.json())
            .then(data => {
                const grid = document.getElementById('tablesGrid');
                if (!data.length) {
                    grid.innerHTML = '<div class="col-span-full text-center text-on-surface-variant py-8">Belum ada meja</div>';
                    return;
                }
                grid.innerHTML = data.map(t => `
                    <div class="bg-surface-container-lowest rounded-2xl shadow-[0_4px_12px_rgba(0,0,0,0.05)] border border-outline-variant p-5">
                        <div class="flex items-center justify-between mb-3">
                            <span class="font-headline-lg text-headline-lg text-on-surface">${t.table_number}</span>
                            <span class="px-2.5 py-1 text-xs font-bold rounded-full ${t.status === 'empty' ? 'bg-green-100 text-green-800' : t.status === 'occupied' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'}">
                                ${t.status.replace('_', ' ')}
                            </span>
                        </div>
                        <p class="text-xs text-on-surface-variant mb-3 truncate">QR: ${t.qr_token ? t.qr_token.substring(0, 20) + '...' : '-'}</p>
                        <div class="flex gap-2">
                            <button onclick="showQR('${t.table_number}', '${t.qr_token || ''}', ${t.id})" class="flex-1 px-3 py-2 text-xs font-medium rounded-lg bg-surface-container-low text-on-surface-variant hover:bg-surface-container-highest transition-all flex items-center justify-center gap-1">
                                <span class="material-symbols-outlined text-sm">qr_code</span> QR
                            </button>
                            <button onclick="regenerateQR(${t.id})" class="flex-1 px-3 py-2 text-xs font-medium rounded-lg bg-surface-container-low text-on-surface-variant hover:bg-surface-container-highest transition-all flex items-center justify-center gap-1">
                                <span class="material-symbols-outlined text-sm">refresh</span> Regenerate
                            </button>
                            <button onclick="deleteTable(${t.id}, '${t.table_number}')" class="px-3 py-2 text-xs font-medium rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-all flex items-center justify-center">
                                <span class="material-symbols-outlined text-sm">delete</span>
                            </button>
                        </div>
                    </div>
                `).join('');
            });
    }

    function showCreateForm() {
        Swal.fire({
            title: 'Tambah Meja',
            html: '<input id="swal-number" class="swal2-input" placeholder="Nomor Meja" autofocus>',
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#a73a00',
            preConfirm: () => {
                const number = document.getElementById('swal-number').value;
                if (!number) { Swal.showValidationMessage('Nomor meja harus diisi'); return false; }
                return fetch('/admin/api/tables', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ table_number: number })
                }).then(r => r.ok ? r.json() : Promise.reject('Nomor meja sudah ada'));
            }
        }).then(result => {
            if (result.isConfirmed) { loadTables(); Toast.fire({ icon: 'success', title: 'Meja berhasil ditambahkan' }); }
        });
    }

    function showQR(tableNumber, qrToken, tableId) {
        if (!qrToken) { regenerateQR(tableId); return; }
        const url = '{{ url('/customer/menu') }}/' + qrToken;
        Swal.fire({
            title: 'QR Code Meja ' + tableNumber,
            html: `
                <div id="swal-qrcode" class="flex justify-center my-2"></div>
                <p class="text-xs text-gray-500 break-all mt-2">${url}</p>
            `,
            showConfirmButton: true,
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#a73a00',
            didOpen: () => {
                new QRCode(document.getElementById('swal-qrcode'), { text: url, width: 180, height: 180 });
            }
        });
    }

    function regenerateQR(id) {
        fetch('/admin/api/tables/' + id + '/generate-qr', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
            .then(r => r.json())
            .then(() => { loadTables(); Toast.fire({ icon: 'success', title: 'QR Code berhasil diperbarui' }); })
            .catch(() => Toast.fire({ icon: 'error', title: 'Gagal memperbarui QR' }));
    }

    function deleteTable(id, number) {
        Swal.fire({
            title: 'Hapus Meja ' + number + '?',
            text: 'Data meja akan dihapus permanen',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) {
                fetch('/admin/api/tables/' + id, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
                    .then(r => r.ok ? r.json() : Promise.reject('Gagal menghapus'))
                    .then(() => { loadTables(); Toast.fire({ icon: 'success', title: 'Meja berhasil dihapus' }); })
                    .catch(e => Toast.fire({ icon: 'error', title: e }));
            }
        });
    }

    loadTables();
</script>
@endpush