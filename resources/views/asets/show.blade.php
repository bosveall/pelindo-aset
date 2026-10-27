@extends('layouts.app')

@section('title', 'Detail Aset')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Aset</h1>
            <p class="text-gray-500">{{ $aset->kode_aset }} - {{ $aset->nama }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('asets.edit', $aset) }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">Edit</a>
            <a href="{{ route('asets.index') }}"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm">Kembali</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Gambar Aset</h3>
                <img src="{{ $aset->image_url }}" alt="{{ $aset->nama }}"
                    class="h-64 w-full rounded-xl object-cover border border-slate-200 shadow-sm">
            </div>
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Informasi Aset</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Kode</dt>
                        <dd class="font-medium">{{ $aset->kode_aset }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Kategori</dt>
                        <dd class="font-medium capitalize">{{ $aset->kategori ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Merk</dt>
                        <dd class="font-medium">{{ $aset->merk ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Lokasi</dt>
                        <dd class="font-medium">{{ $aset->lokasi ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Tgl Pengadaan</dt>
                        <dd class="font-medium">{{ $aset->tanggal_pengadaan?->format('d/m/Y') ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Kondisi</dt>
                        <dd>
                            <span
                                class="px-2 py-1 text-xs rounded-full {{ $aset->kondisi == 'baik' ? 'bg-green-100 text-green-700' : ($aset->kondisi == 'rusak_ringan' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                {{ str_replace('_', ' ', ucfirst($aset->kondisi)) }}
                            </span>
                        </dd>
                    </div>
                </dl>
                @if($aset->deskripsi)
                    <div class="mt-4 pt-4 border-t">
                        <p class="text-gray-500 text-sm mb-1">Deskripsi</p>
                        <p class="text-sm">{{ $aset->deskripsi }}</p>
                    </div>
                @endif
            </div>
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="font-semibold text-gray-800 mb-2">Ringkasan Pemeliharaan</h3>
                <p class="text-3xl font-bold text-slate-800">{{ $aset->pemeliharaans->count() }}</p>
                <p class="text-sm text-gray-500">kali pemeliharaan</p>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="p-5 border-b flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800">Riwayat Pemeliharaan</h3>
                    <a href="{{ route('pemeliharaans.create', ['aset_id' => $aset->id]) }}"
                        class="text-sm font-medium text-teal-700 hover:underline">Atur jadwal</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jadwal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Petugas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($aset->pemeliharaans as $pm)
                                <tr>
                                    <td class="px-6 py-3 text-sm">{{ $pm->tanggal?->format('d/m/Y') ?? '-' }}</td>
                                    <td class="px-6 py-3 text-sm">{{ $pm->jenis ?? '-' }}</td>
                                    <td class="px-6 py-3 text-sm">{{ $pm->jadwal_pemeliharaan?->format('d/m/Y') ?? '-' }}</td>
                                    <td class="px-6 py-3 text-sm">{{ $pm->petugas?->name ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada riwayat pemeliharaan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection