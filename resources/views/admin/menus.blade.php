@extends('layouts.admin')

@section('title', 'Menu - Admin Coffe Paste')

@section('content')
@include('admin._sidebar')

<main class="lg:ml-72 min-h-screen flex flex-col">
    <header class="sticky top-0 z-40 bg-surface flex justify-between items-center px-container-margin h-20 w-full">
        <div>
            <h1 class="font-headline-xl text-headline-xl text-on-surface">Menu Catalog</h1>
            <p class="text-body-sm text-secondary">Kelola daftar menu</p>
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
                    <p class="text-on-surface-variant font-body-md">Daftar menu yang tersedia</p>
                </div>
                <button onclick="showCreateForm()" class="flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold hover:opacity-90 transition-all" style="background: #a73a00; color: #ffffff;">
                    <span class="material-symbols-outlined text-lg">add</span>
                    Tambah Menu
                </button>
            </div>

            <div class="bg-surface-container-lowest rounded-2xl shadow-[0_4px_12px_rgba(0,0,0,0.05)] border border-outline-variant overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr class="bg-surface-container text-left">
                            <th class="px-5 py-3 font-label-md text-label-md text-on-surface-variant">Nama</th>
                            <th class="px-5 py-3 font-label-md text-label-md text-on-surface-variant">Kategori</th>
                            <th class="px-5 py-3 font-label-md text-label-md text-on-surface-variant">Harga</th>
                            <th class="px-5 py-3 font-label-md text-label-md text-on-surface-variant">Tersedia</th>
                            <th class="px-5 py-3 font-label-md text-label-md text-on-surface-variant text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="menusTable" class="divide-y divide-outline-variant/20">
                        <tr><td colspan="5" class="px-5 py-8 text-center text-on-surface-variant">Memuat data...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    let categories = [];
    const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true });

    function loadMenus() {
        fetch('/admin/api/menus')
            .then(r => r.json())
            .then(data => {
                const tbody = document.getElementById('menusTable');
                if (!data.length) {
                    tbody.innerHTML = '<tr><td colspan="5" class="px-5 py-8 text-center text-on-surface-variant">Belum ada menu</td></tr>';
                    return;
                }
                tbody.innerHTML = data.map(m => `
                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                        <td class="px-5 py-4 font-medium text-on-surface">${m.name}</td>
                        <td class="px-5 py-4 text-on-surface-variant">${m.category?.name || '-'}</td>
                        <td class="px-5 py-4 font-medium text-on-surface">Rp ${(m.price || 0).toLocaleString('id-ID')}</td>
                        <td class="px-5 py-4">
                            <span class="px-2.5 py-1 text-xs font-bold rounded-full ${m.is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                ${m.is_available ? 'Tersedia' : 'Habis'}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <button onclick="editMenu(${m.id})" class="text-sm text-primary hover:text-primary-container flex items-center gap-1 ml-auto">
                                <span class="material-symbols-outlined text-lg">edit</span> Edit
                            </button>
                        </td>
                    </tr>
                `).join('');
            });
    }

    function loadCategoryOptions() {
        fetch('/admin/api/categories')
            .then(r => r.json())
            .then(data => { categories = data; });
    }

    function showCreateForm() {
        const catOptions = categories.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
        Swal.fire({
            title: 'Tambah Menu',
            html: `
                <div class="text-left space-y-3">
                    <div><label class="text-sm font-medium">Nama Menu</label><input id="s-name" class="swal2-input w-full" required></div>
                    <div><label class="text-sm font-medium">Kategori</label><select id="s-category" class="swal2-input w-full">${catOptions}</select></div>
                    <div><label class="text-sm font-medium">Harga (Rp)</label><input id="s-price" type="number" min="0" class="swal2-input w-full" required></div>
                    <div><label class="text-sm font-medium">Deskripsi</label><textarea id="s-desc" class="swal2-input w-full" rows="2"></textarea></div>
                    <div><label class="text-sm font-medium">Gambar (URL)</label><input id="s-image" class="swal2-input w-full"></div>
                    <div><label class="flex items-center gap-2"><input type="checkbox" id="s-avail" checked> Tersedia</label></div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#a73a00',
            width: 500,
            preConfirm: () => {
                const name = document.getElementById('s-name').value;
                if (!name) { Swal.showValidationMessage('Nama harus diisi'); return false; }
                const data = {
                    category_id: document.getElementById('s-category').value,
                    name, price: document.getElementById('s-price').value,
                    description: document.getElementById('s-desc').value,
                    image: document.getElementById('s-image').value,
                    is_available: document.getElementById('s-avail').checked,
                };
                return fetch('/admin/api/menus', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify(data)
                }).then(r => r.ok ? r.json() : Promise.reject('Gagal menyimpan'));
            }
        }).then(result => {
            if (result.isConfirmed) { loadMenus(); Toast.fire({ icon: 'success', title: 'Menu berhasil disimpan' }); }
        });
    }

    function editMenu(id) {
        fetch('/admin/api/menus')
            .then(r => r.json())
            .then(data => {
                const m = data.find(x => x.id === id);
                if (!m) return;
                const catOptions = categories.map(c => `<option value="${c.id}" ${c.id === m.category_id ? 'selected' : ''}>${c.name}</option>`).join('');
                Swal.fire({
                    title: 'Edit Menu',
                    html: `
                        <div class="text-left space-y-3">
                            <div><label class="text-sm font-medium">Nama Menu</label><input id="s-name" class="swal2-input w-full" value="${m.name}" required></div>
                            <div><label class="text-sm font-medium">Kategori</label><select id="s-category" class="swal2-input w-full">${catOptions}</select></div>
                            <div><label class="text-sm font-medium">Harga (Rp)</label><input id="s-price" type="number" min="0" class="swal2-input w-full" value="${m.price}" required></div>
                            <div><label class="text-sm font-medium">Deskripsi</label><textarea id="s-desc" class="swal2-input w-full" rows="2">${m.description || ''}</textarea></div>
                            <div><label class="text-sm font-medium">Gambar (URL)</label><input id="s-image" class="swal2-input w-full" value="${m.image || ''}"></div>
                            <div><label class="flex items-center gap-2"><input type="checkbox" id="s-avail" ${m.is_available ? 'checked' : ''}> Tersedia</label></div>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Simpan',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#a73a00',
                    width: 500,
                    preConfirm: () => {
                        const name = document.getElementById('s-name').value;
                        if (!name) { Swal.showValidationMessage('Nama harus diisi'); return false; }
                        const payload = {
                            category_id: document.getElementById('s-category').value,
                            name, price: document.getElementById('s-price').value,
                            description: document.getElementById('s-desc').value,
                            image: document.getElementById('s-image').value,
                            is_available: document.getElementById('s-avail').checked,
                        };
                        return fetch(`/admin/api/menus/${id}`, {
                            method: 'PUT',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify(payload)
                        }).then(r => r.ok ? r.json() : Promise.reject('Gagal menyimpan'));
                    }
                }).then(result => {
                    if (result.isConfirmed) { loadMenus(); Toast.fire({ icon: 'success', title: 'Menu berhasil disimpan' }); }
                });
            });
    }

    loadMenus();
    loadCategoryOptions();
</script>
@endpush