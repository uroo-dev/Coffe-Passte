@extends('layouts.admin')

@section('title', 'Buat Akun - Admin Coffe Paste')

@section('content')
@include('admin._sidebar')

<main class="lg:ml-72 min-h-screen flex flex-col">
    <header class="sticky top-0 z-40 bg-surface flex justify-between items-center px-container-margin h-20 w-full">
        <div>
            <h1 class="font-headline-xl text-headline-xl text-on-surface">Buat Akun Baru</h1>
            <p class="text-body-sm text-secondary">Buatkan akun untuk staff atau admin</p>
        </div>
        <div class="flex items-center gap-4">
            <span class="font-label-md text-label-md text-on-surface-variant">{{ auth()->user()->name }}</span>
            <div class="h-10 w-10 rounded-full bg-primary-fixed-dim flex items-center justify-center text-primary font-bold text-sm">
                {{ substr(auth()->user()->name, 0, 2) }}
            </div>
        </div>
    </header>

    <div class="p-8">
        <div class="max-w-2xl mx-auto">
            <a href="/admin/users" class="flex items-center gap-1 text-on-surface-variant hover:text-on-surface mb-6 text-sm">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
                Kembali
            </a>

            @if ($errors->any())
                <div class="p-4 rounded-lg mb-6 bg-red-50 text-red-700">
                    @foreach ($errors->all() as $error)
                        <p class="text-sm flex items-center gap-2"><span class="material-symbols-outlined text-lg">error</span>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="bg-surface-container-lowest rounded-2xl shadow-[0_4px_12px_rgba(0,0,0,0.05)] border border-outline-variant p-6">
                <form method="POST" action="/admin/users" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium mb-1.5 text-on-surface">Nama Lengkap</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-lg text-outline">person</span>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-outline text-sm outline-none focus:border-primary transition-all bg-surface-container-low">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1.5 text-on-surface">Email</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-lg text-outline">mail</span>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-outline text-sm outline-none focus:border-primary transition-all bg-surface-container-low">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1.5 text-on-surface">Password</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-lg text-outline">lock</span>
                            <input type="password" name="password" required
                                class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-outline text-sm outline-none focus:border-primary transition-all bg-surface-container-low">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1.5 text-on-surface">Role</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-lg text-outline">badge</span>
                            <select name="role" required
                                class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-outline text-sm outline-none focus:border-primary transition-all bg-surface-container-low appearance-none">
                                <option value="">Pilih Role</option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="staff_cashier" {{ old('role') === 'staff_cashier' ? 'selected' : '' }}>Staff Kasir</option>
                                <option value="staff_kitchen" {{ old('role') === 'staff_kitchen' ? 'selected' : '' }}>Staff Dapur</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-lg text-sm font-semibold hover:opacity-90 transition-all" style="background: #a73a00; color: #ffffff;">
                        <span class="material-symbols-outlined text-lg mr-1">person_add</span>
                        Buat Akun
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
