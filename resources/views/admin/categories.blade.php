@extends('layouts.admin')

@section('title', 'Kategori - Admin Coffe Paste')

@section('content')
@include('admin._sidebar')

<main class="lg:ml-72 min-h-screen flex flex-col">
    <header class="sticky top-0 z-40 bg-surface flex justify-between items-center px-container-margin h-20 w-full">
        <div>
            <h1 class="font-headline-xl text-headline-xl text-on-surface">Kategori</h1>
            <p class="text-body-sm text-secondary">Kelola kategori menu</p>
        </div>
        <div class="flex items-center gap-4">
            <span class="font-label-md text-label-md text-on-surface-variant">{{ auth()->user()->name }}</span>
            <div class="h-10 w-10 rounded-full bg-primary-fixed-dim flex items-center justify-center text-primary font-bold text-sm">
                {{ substr(auth()->user()->name, 0, 2) }}
            </div>
        </div>
    </header>

    <div class="p-8">
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <p class="text-on-surface-variant font-body-md">Daftar kategori yang tersedia</p>
                </div>
                <button onclick="showCreateForm()" class="flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold hover:opacity-90 transition-all" style="background: #a73a00; color: #ffffff;">
                    <span class="material-symbols-outlined text-lg">add</span>
                    Tambah Kategori
                </button>
            </div>

            <div class="bg-surface-container-lowest rounded-2xl shadow-[0_4px_12px_rgba(0,0,0,0.05)] border border-outline-variant overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr class="bg-surface-container text-left">
                            <th class="px-5 py-3 font-label-md text-label-md text-on-surface-variant">Nama</th>
                            <th class="px-5 py-3 font-label-md text-label-md text-on-surface-variant">Slug</th>
                            <th class="px-5 py-3 font-label-md text-label-md text-on-surface-variant">Jumlah Menu</th>
                            <th class="px-5 py-3 font-label-md text-label-md text-on-surface-variant text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="categoriesTable" class="divide-y divide-outline-variant/20">
                        <tr><td colspan="4" class="px-5 py-8 text-center text-on-surface-variant">Memuat data...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true });

    function loadCategories() {
        fetch('/admin/api/categories')
            .then(r => r.json())
            .then(data => {
                const tbody = document.getElementById('categoriesTable');
                if (!data.length) {
                    tbody.innerHTML = '<tr><td colspan="4" class="px-5 py-8 text-center text-on-surface-variant">Belum ada kategori</td></tr>';
                    return;
                }
                tbody.innerHTML = data.map(c => `
                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                        <td class="px-5 py-4 font-medium text-on-surface">${c.name}</td>
                        <td class="px-5 py-4 text-on-surface-variant">${c.slug}</td>
                        <td class="px-5 py-4 text-on-surface-variant">${c.menus_count || 0}</td>
                        <td class="px-5 py-4 text-right">
                            <button onclick="editCategory(${c.id}, '${c.name}')" class="text-sm text-primary hover:text-primary-container flex items-center gap-1 ml-auto">
                                <span class="material-symbols-outlined text-lg">edit</span> Edit
                            </button>
                        </td>
                    </tr>
                `).join('');
            });
    }

    function showCreateForm() {
        Swal.fire({
            title: 'Tambah Kategori',
            html: '<input id="swal-name" class="swal2-input" placeholder="Nama Kategori" autofocus>',
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#a73a00',
            preConfirm: () => {
                const name = document.getElementById('swal-name').value;
                if (!name) { Swal.showValidationMessage('Nama kategori harus diisi'); return false; }
                return fetch('/admin/api/categories', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ name })
                }).then(r => r.ok ? r.json() : Promise.reject('Gagal menyimpan'));
            }
        }).then(result => {
            if (result.isConfirmed) {
                loadCategories();
                Toast.fire({ icon: 'success', title: 'Kategori berhasil disimpan' });
            }
        });
    }

    function editCategory(id, name) {
        Swal.fire({
            title: 'Edit Kategori',
            html: `<input id="swal-name" class="swal2-input" value="${name}" autofocus>`,
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#a73a00',
            preConfirm: () => {
                const newName = document.getElementById('swal-name').value;
                if (!newName) { Swal.showValidationMessage('Nama kategori harus diisi'); return false; }
                return fetch(`/admin/api/categories/${id}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ name: newName })
                }).then(r => r.ok ? r.json() : Promise.reject('Gagal menyimpan'));
            }
        }).then(result => {
            if (result.isConfirmed) {
                loadCategories();
                Toast.fire({ icon: 'success', title: 'Kategori berhasil disimpan' });
            }
        });
    }

    loadCategories();
</script>
@endpush