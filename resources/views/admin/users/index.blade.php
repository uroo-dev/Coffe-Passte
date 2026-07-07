@extends('layouts.admin')

@section('title', 'Kelola Akun - Admin Coffe Paste')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(btn, name) {
        Swal.fire({
            title: 'Hapus akun ' + name + '?',
            text: 'Akun akan dihapus permanen',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) {
                btn.closest('form').submit();
            }
        });
    }
</script>
@endpush

@section('content')
@include('admin._sidebar')

<main class="lg:ml-72 min-h-screen flex flex-col">
    <header class="sticky top-0 z-40 bg-surface flex justify-between items-center px-container-margin h-20 w-full">
        <div>
            <h1 class="font-headline-xl text-headline-xl text-on-surface">Kelola Akun</h1>
            <p class="text-body-sm text-secondary">Daftar akun staff dan admin</p>
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
                    <p class="text-on-surface-variant font-body-md">Daftar akun staff dan admin</p>
                </div>
                <a href="/admin/users/create" class="flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold hover:opacity-90 transition-all" style="background: #a73a00; color: #ffffff;">
                    <span class="material-symbols-outlined text-lg">add</span>
                    Buat Akun
                </a>
            </div>

            @if (session('success'))
                <div class="p-4 rounded-lg mb-6 flex items-center gap-3 bg-green-50 text-green-700">
                    <span class="material-symbols-outlined">check_circle</span>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 rounded-lg mb-6 flex items-center gap-3 bg-red-50 text-red-700">
                    <span class="material-symbols-outlined">error</span>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-surface-container-lowest rounded-2xl shadow-[0_4px_12px_rgba(0,0,0,0.05)] border border-outline-variant overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr class="bg-surface-container text-left">
                            <th class="px-5 py-3 font-label-md text-label-md text-on-surface-variant">Nama</th>
                            <th class="px-5 py-3 font-label-md text-label-md text-on-surface-variant">Email</th>
                            <th class="px-5 py-3 font-label-md text-label-md text-on-surface-variant">Role</th>
                            <th class="px-5 py-3 font-label-md text-label-md text-on-surface-variant">Dibuat</th>
                            <th class="px-5 py-3 font-label-md text-label-md text-on-surface-variant text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/20">
                        @foreach($users as $user)
                        <tr class="hover:bg-surface-container-low/50 transition-colors">
                            <td class="px-5 py-4 font-medium text-on-surface">{{ $user->name }}</td>
                            <td class="px-5 py-4 text-on-surface-variant">{{ $user->email }}</td>
                            <td class="px-5 py-4">
                                @php
                                    $roleLabels = ['owner' => 'Owner', 'admin' => 'Admin', 'staff_cashier' => 'Kasir', 'staff_kitchen' => 'Dapur'];
                                    $roleColors = ['owner' => 'bg-purple-100 text-purple-800', 'admin' => 'bg-blue-100 text-blue-800', 'staff_cashier' => 'bg-green-100 text-green-800', 'staff_kitchen' => 'bg-yellow-100 text-yellow-800'];
                                @endphp
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $roleLabels[$user->role] ?? $user->role }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-on-surface-variant text-sm">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="px-5 py-4 text-right">
                                @if(!$user->isOwner())
                                <form method="POST" action="/admin/users/{{ $user->id }}" class="delete-user-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete(this, '{{ $user->name }}')" class="text-sm text-red-600 hover:text-red-800 flex items-center gap-1 ml-auto">
                                        <span class="material-symbols-outlined text-lg">delete</span>
                                        Hapus
                                    </button>
                                </form>
                                @else
                                <span class="text-xs text-on-surface-variant">Owner</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection
