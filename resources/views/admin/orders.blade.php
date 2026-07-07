@extends('layouts.admin')

@section('title', 'Pesanan - Admin Coffe Paste')

@section('content')
@include('admin._sidebar')

<main class="lg:ml-72 min-h-screen flex flex-col">
    <header class="sticky top-0 z-40 bg-surface flex justify-between items-center px-container-margin h-20 w-full">
        <div>
            <h1 class="font-headline-xl text-headline-xl text-on-surface">Financial Reports</h1>
            <p class="text-body-sm text-secondary">Semua pesanan pelanggan</p>
        </div>
        <div class="flex items-center gap-4">
            <span class="font-label-md text-label-md text-on-surface-variant">{{ auth()->user()->name }}</span>
            <div class="h-10 w-10 rounded-full bg-primary-fixed-dim flex items-center justify-center text-primary font-bold text-sm">
                {{ substr(auth()->user()->name, 0, 2) }}
            </div>
        </div>
    </header>

    <div class="p-8">
        <div class="max-w-6xl mx-auto">
            <div class="bg-surface-container-lowest rounded-2xl shadow-[0_4px_12px_rgba(0,0,0,0.05)] border border-outline-variant overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr class="bg-surface-container text-left">
                            <th class="px-5 py-3 font-label-md text-label-md text-on-surface-variant">Referensi</th>
                            <th class="px-5 py-3 font-label-md text-label-md text-on-surface-variant">Meja</th>
                            <th class="px-5 py-3 font-label-md text-label-md text-on-surface-variant">Total</th>
                            <th class="px-5 py-3 font-label-md text-label-md text-on-surface-variant">Status</th>
                            <th class="px-5 py-3 font-label-md text-label-md text-on-surface-variant">Pembayaran</th>
                            <th class="px-5 py-3 font-label-md text-label-md text-on-surface-variant">Waktu</th>
                            <th class="px-5 py-3 font-label-md text-label-md text-on-surface-variant text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="ordersTable" class="divide-y divide-outline-variant/20">
                        <tr><td colspan="7" class="px-5 py-8 text-center text-on-surface-variant">Memuat data...</td></tr>
                    </tbody>
                </table>
                <div class="px-5 py-4 bg-surface-container border-t border-outline-variant/30 flex items-center justify-between">
                    <span class="text-sm text-on-surface-variant" id="paginationInfo">Halaman 1</span>
                    <div class="flex gap-2" id="paginationButtons"></div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    let currentPage = 1;
    const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true });

    function loadOrders(page) {
        currentPage = page || 1;
        fetch('/admin/api/orders?page=' + currentPage)
            .then(r => r.json())
            .then(res => {
                const tbody = document.getElementById('ordersTable');
                const data = res.data || [];
                if (!data.length) {
                    tbody.innerHTML = '<tr><td colspan="7" class="px-5 py-8 text-center text-on-surface-variant">Belum ada pesanan</td></tr>';
                    return;
                }
                const statusLabels = { pending: 'Pending', cooking: 'Dimasak', ready: 'Siap', completed: 'Selesai', cancelled: 'Dibatalkan' };
                const statusColors = { pending: 'bg-yellow-100 text-yellow-800', cooking: 'bg-blue-100 text-blue-800', ready: 'bg-green-100 text-green-800', completed: 'bg-green-100 text-green-800', cancelled: 'bg-red-100 text-red-800' };
                const paymentLabels = { unpaid: 'Belum Bayar', paid: 'Lunas' };
                const paymentColors = { unpaid: 'bg-red-100 text-red-800', paid: 'bg-green-100 text-green-800' };

                tbody.innerHTML = data.map(o => `
                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                        <td class="px-5 py-4 font-medium text-primary">${o.order_reference}</td>
                        <td class="px-5 py-4 text-on-surface-variant">${o.table?.table_number || '-'}</td>
                        <td class="px-5 py-4 font-medium text-on-surface">Rp ${(o.total_amount || 0).toLocaleString('id-ID')}</td>
                        <td class="px-5 py-4">
                            <span class="px-2.5 py-1 text-xs font-bold rounded-full ${statusColors[o.order_status] || 'bg-gray-100'}">
                                ${statusLabels[o.order_status] || o.order_status}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="px-2.5 py-1 text-xs font-bold rounded-full ${paymentColors[o.payment_status] || 'bg-gray-100'}">
                                ${paymentLabels[o.payment_status] || o.payment_status}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-sm text-on-surface-variant">${new Date(o.created_at).toLocaleString('id-ID')}</td>
                        <td class="px-5 py-4 text-right">
                            ${o.order_status !== 'completed' && o.order_status !== 'cancelled' ? `
                                <button onclick="cancelOrder(${o.id})" class="text-sm text-red-600 hover:text-red-800 flex items-center gap-1 ml-auto">
                                    <span class="material-symbols-outlined text-lg">cancel</span> Batalkan
                                </button>
                            ` : '<span class="text-xs text-on-surface-variant">-</span>'}
                        </td>
                    </tr>
                `).join('');

                document.getElementById('paginationInfo').textContent = 'Halaman ' + (res.current_page || 1) + ' dari ' + (res.last_page || 1);
                const pag = document.getElementById('paginationButtons');
                pag.innerHTML = '';
                if (res.prev_page_url) pag.innerHTML += `<button onclick="loadOrders(${res.current_page - 1})" class="px-3 py-1.5 text-sm rounded-lg bg-surface-container-low text-on-surface-variant hover:bg-surface-container-highest">Sebelumnya</button>`;
                if (res.next_page_url) pag.innerHTML += `<button onclick="loadOrders(${res.current_page + 1})" class="px-3 py-1.5 text-sm rounded-lg bg-surface-container-low text-on-surface-variant hover:bg-surface-container-highest">Selanjutnya</button>`;
            });
    }

    function cancelOrder(id) {
        Swal.fire({
            title: 'Batalkan Pesanan?',
            text: 'Pesanan akan dibatalkan dan tidak bisa dikembalikan',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, batalkan!',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) {
                fetch('/admin/api/orders/' + id + '/cancel', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
                    .then(r => r.ok ? r.json() : Promise.reject('Gagal membatalkan'))
                    .then(() => { loadOrders(currentPage); Toast.fire({ icon: 'success', title: 'Pesanan dibatalkan' }); })
                    .catch(e => Toast.fire({ icon: 'error', title: e }));
            }
        });
    }

    loadOrders(1);
</script>
@endpush