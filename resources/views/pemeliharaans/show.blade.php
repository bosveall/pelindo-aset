@extends('layouts.app')

@section('title', 'Detail Pemeliharaan')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Pemeliharaan</h1>
            <p class="text-gray-500">Riwayat pemeliharaan aset</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('pemeliharaans.edit', $pemeliharaan) }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">Edit</a>
            <a href="{{ route('pemeliharaans.index') }}"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm">Kembali</a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-6 max-w-3xl">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-semibold">{{ $pemeliharaan->aset?->nama }}</h2>
                <p class="text-sm text-gray-500">{{ $pemeliharaan->aset?->kode_aset }}</p>
            </div>
        </div>

        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div class="bg-gray-50 rounded-lg p-4">
                <dt class="text-gray-500">Tanggal</dt>
                <dd class="font-medium mt-1">{{ $pemeliharaan->tanggal?->format('d/m/Y H:i') ?? '-' }}</dd>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <dt class="text-gray-500">Jadwal Pemeliharaan</dt>
                <dd class="font-medium mt-1">{{ $pemeliharaan->jadwal_pemeliharaan?->format('d/m/Y') ?? '-' }}</dd>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <dt class="text-gray-500">Jenis</dt>
                <dd class="font-medium mt-1">{{ $pemeliharaan->jenis ?? '-' }}</dd>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <dt class="text-gray-500">Petugas</dt>
                <dd class="font-medium mt-1">{{ $pemeliharaan->petugas?->name ?? '-' }}</dd>
            </div>
        </dl>

        @if($pemeliharaan->keterangan)
            <div class="mt-4 bg-gray-50 rounded-lg p-4">
                <p class="text-gray-500 text-sm mb-1">Keterangan</p>
                <p class="text-sm">{{ $pemeliharaan->keterangan }}</p>
            </div>
        @endif
    </div>
@endsection