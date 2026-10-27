@extends('layouts.app')

@section('title', 'Data Aset')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800">Data Aset</h1>
            <p class="text-sm text-slate-500">Kelola data aset perusahaan</p>
        </div>
        <a href="{{ route('asets.create') }}"
            class="inline-flex items-center gap-2 bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Aset
        </a>
        <a href="{{ route('ruangan.index') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-teal-200 bg-teal-50 px-4 py-2 text-sm font-medium text-teal-700 hover:bg-teal-100">
            QR Ruangan
        </a>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 mb-6">
        <form method="GET" action="{{ route('asets.index') }}" class="grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-5">
            <div>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama/kode/kategori/lokasi..."
                    list="aset-nama-options"
                    class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none">
                @include('suggestion-list', ['listId' => 'aset-nama-options', 'options' => $namaOptions])
            </div>
            <div>
                <select name="kondisi"
                    class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none">
                    <option value="">Semua Kondisi</option>
                    <option value="baik" {{ request('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="rusak_ringan" {{ request('kondisi') == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan
                    </option>
                    <option value="rusak_berat" {{ request('kondisi') == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat
                    </option>
                </select>
            </div>
            <div>
                <select name="kategori"
                    class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriOptions as $kategori)
                        <option value="{{ $kategori }}" {{ request('kategori') === $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <input type="text" name="lokasi" value="{{ request('lokasi') }}"
                    placeholder="Semua lokasi" list="aset-lokasi-options"
                    class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none">
                @include('suggestion-list', ['listId' => 'aset-lokasi-options', 'options' => $lokasiOptions])
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="flex-1 bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg text-sm font-medium">Filter</button>
                <a href="{{ route('asets.index') }}"
                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg text-sm">Reset</a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($asets as $aset)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <img src="{{ $aset->image_url }}" alt="{{ $aset->nama }}"
                                    class="h-12 w-12 rounded-lg object-cover border border-slate-200">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $aset->kode_aset }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <a href="{{ route('asets.show', $aset) }}"
                                    class="hover:text-slate-700 hover:underline">{{ $aset->nama }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 capitalize">
                                {{ $aset->kategori ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $aset->lokasi ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 py-1 text-xs rounded-full {{ $aset->kondisi == 'baik' ? 'bg-green-100 text-green-700' : ($aset->kondisi == 'rusak_ringan' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                    {{ str_replace('_', ' ', ucfirst($aset->kondisi)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <a href="{{ route('asets.show', $aset) }}"
                                    class="text-slate-600 hover:text-slate-800 mr-2">Detail</a>
                                <a href="{{ route('asets.edit', $aset) }}"
                                    class="text-blue-600 hover:text-blue-800 mr-2">Edit</a>
                                <form action="{{ route('asets.destroy', $aset) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Yakin ingin menghapus aset ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-10 text-center text-gray-500">Belum ada data aset.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4">
            {{ $asets->links() }}
        </div>
    </div>
@endsection