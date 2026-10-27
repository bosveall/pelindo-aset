@extends('layouts.app')

@section('title', 'Hak Akses')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Kelola Hak Akses</h1>
        <p class="text-gray-500">Atur role dan hak akses pengguna</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Admin --}}
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="p-5 border-b bg-slate-800">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-white">Admin</h3>
                    <span class="px-2 py-1 text-xs rounded-full bg-white/20 text-white">{{ $admins->count() }}
                        pengguna</span>
                </div>
                <p class="text-slate-300 text-sm mt-1">Akses penuh ke seluruh menu</p>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($admins as $user)
                    <div class="p-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-9 h-9 rounded-full bg-slate-600 text-white flex items-center justify-center font-bold text-xs uppercase">
                                {{ substr($user->name, 0, 1) }}</div>
                            <div>
                                <p class="font-medium text-sm">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('hakakses.update', $user) }}" class="flex items-center gap-2">
                            @csrf
                            @method('PUT')
                            <select name="role" onchange="this.form.submit()"
                                class="px-2 py-1 border border-gray-300 rounded-lg text-sm" {{ $user->id === Auth::id() ? 'disabled' : '' }}>
                                <option value="admin" selected>Admin</option>
                                <option value="petugas">Petugas</option>
                            </select>
                        </form>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500 text-sm">Tidak ada admin.</div>
                @endforelse
            </div>
        </div>

        {{-- Petugas --}}
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="p-5 border-b bg-blue-700">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-white">Petugas</h3>
                    <span class="px-2 py-1 text-xs rounded-full bg-white/20 text-white">{{ $petugas->count() }}
                        pengguna</span>
                </div>
                <p class="text-blue-100 text-sm mt-1">Akses data aset, pemeliharaan & laporan</p>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($petugas as $user)
                    <div class="p-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-xs uppercase">
                                {{ substr($user->name, 0, 1) }}</div>
                            <div>
                                <p class="font-medium text-sm">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('hakakses.update', $user) }}" class="flex items-center gap-2">
                            @csrf
                            @method('PUT')
                            <select name="role" onchange="this.form.submit()"
                                class="px-2 py-1 border border-gray-300 rounded-lg text-sm">
                                <option value="petugas" selected>Petugas</option>
                                <option value="admin">Admin</option>
                            </select>
                        </form>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500 text-sm">Tidak ada petugas.</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection