@extends('layouts.app')

@section('title', 'Riwayat Pemeliharaan')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800">Riwayat Pemeliharaan</h1>
            <p class="text-sm text-slate-500">Kelola riwayat pemeliharaan aset</p>
        </div>
        <a href="{{ route('pemeliharaans.create') }}"
            class="inline-flex items-center gap-2 bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Pemeliharaan
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 mb-6">
        <form method="GET" action="{{ route('pemeliharaans.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/kode aset..."
                    class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none">
            </div>
            <div>
                <select name="aset_id"
                    class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none">
                    <option value="">Semua Aset</option>
                    @foreach($asets as $a)
                        <option value="{{ $a->id }}" {{ request('aset_id') == $a->id ? 'selected' : '' }}>{{ $a->kode_aset }} -
                            {{ $a->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="flex-1 bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg text-sm font-medium">Filter</button>
                <a href="{{ route('pemeliharaans.index') }}"
                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg text-sm">Reset</a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="hidden overflow-x-auto md:block">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aset</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Petugas
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pemeliharaans as $pm)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('pemeliharaans.show', $pm) }}"
                                    class="font-medium text-gray-900 hover:text-slate-700">{{ $pm->aset?->nama }}</a>
                                <p class="text-xs text-gray-500">{{ $pm->aset?->kode_aset }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $pm->tanggal?->format('d/m/Y H:i') ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $pm->jadwal_pemeliharaan?->format('d/m/Y') ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $pm->jenis ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $pm->petugas?->name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <a href="{{ route('pemeliharaans.show', $pm) }}"
                                    class="text-slate-600 hover:text-slate-800 mr-2">Detail</a>
                                <a href="{{ route('pemeliharaans.edit', $pm) }}"
                                    class="text-blue-600 hover:text-blue-800 mr-2">Edit</a>
                                <form action="{{ route('pemeliharaans.destroy', $pm) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Yakin ingin menghapus riwayat ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500">Belum ada data pemeliharaan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="divide-y divide-slate-200 md:hidden">
            @forelse($pemeliharaans as $pm)
                <article class="space-y-4 p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <a href="{{ route('pemeliharaans.show', $pm) }}" class="block truncate text-sm font-semibold text-slate-900">
                                {{ $pm->aset?->nama ?? 'Aset dihapus' }}
                            </a>
                            <p class="mt-1 text-xs text-slate-500">{{ $pm->aset?->kode_aset ?? '-' }} · {{ $pm->jenis ?? 'Pemeliharaan' }}</p>
                        </div>
                        <span class="shrink-0 rounded-full px-2.5 py-1 text-xs font-semibold {{ $pm->status === 'selesai' ? 'bg-emerald-50 text-emerald-700' : ($pm->status === 'proses' ? 'bg-sky-50 text-sky-700' : 'bg-amber-50 text-amber-800') }}">
                            {{ ucfirst($pm->status ?? 'menunggu') }}
                        </span>
                    </div>
                    <dl class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <dt class="text-xs text-slate-500">Jadwal</dt>
                            <dd class="mt-1 font-medium text-slate-800">{{ $pm->jadwal_pemeliharaan?->format('d/m/Y') ?? 'Belum diatur' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-500">Petugas</dt>
                            <dd class="mt-1 truncate font-medium text-slate-800">{{ $pm->petugas?->name ?? 'Belum ditentukan' }}</dd>
                        </div>
                    </dl>
                    <div class="flex gap-2 border-t border-slate-100 pt-3">
                        <a href="{{ route('pemeliharaans.edit', $pm) }}" class="flex-1 rounded-lg bg-teal-700 px-3 py-2.5 text-center text-sm font-semibold text-white hover:bg-teal-800">Ubah jadwal</a>
                        <a href="{{ route('pemeliharaans.show', $pm) }}" aria-label="Detail pemeliharaan" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                    </div>
                </article>
            @empty
                <p class="px-4 py-8 text-center text-sm text-slate-500">Belum ada data pemeliharaan.</p>
            @endforelse
        </div>
        <div class="px-6 py-4">
            {{ $pemeliharaans->links() }}
        </div>
    </div>
@endsection