@extends('layouts.app')

@section('title', 'Tambah Aset')

@section('content')
    <div class="mb-6">
        <h1 class="text-xl font-bold text-slate-800">Tambah Aset</h1>
        <p class="text-sm text-slate-500">Lengkapi data aset baru</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 max-w-3xl">
        <form method="POST" action="{{ route('asets.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="kode_aset" class="block text-sm font-medium text-gray-700 mb-1">Kode Aset <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="kode_aset" name="kode_aset" value="{{ old('kode_aset') }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 outline-none">
                </div>
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Aset <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}" list="aset-nama-options" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 outline-none">
                    @include('suggestion-list', ['listId' => 'aset-nama-options', 'options' => $suggestions['nama']])
                </div>
                <div>
                    <label for="kategori" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <input type="text" id="kategori" name="kategori" value="{{ old('kategori') }}" list="aset-kategori-options"
                        placeholder="cth: Komputer, Kendaraan, Mesin"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 outline-none">
                    @include('suggestion-list', ['listId' => 'aset-kategori-options', 'options' => $suggestions['kategori']])
                </div>
                <div>
                    <label for="merk" class="block text-sm font-medium text-gray-700 mb-1">Merk</label>
                    <input type="text" id="merk" name="merk" value="{{ old('merk') }}" list="aset-merk-options"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 outline-none">
                    @include('suggestion-list', ['listId' => 'aset-merk-options', 'options' => $suggestions['merk']])
                </div>
                <div>
                    <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                    <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi') }}" list="aset-lokasi-options"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 outline-none">
                    @include('suggestion-list', ['listId' => 'aset-lokasi-options', 'options' => $suggestions['lokasi']])
                </div>
                <div>
                    <label for="kondisi" class="block text-sm font-medium text-gray-700 mb-1">Kondisi <span
                            class="text-red-500">*</span></label>
                    <select id="kondisi" name="kondisi" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 outline-none">
                        <option value="baik" {{ old('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="rusak_ringan" {{ old('kondisi') == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan
                        </option>
                        <option value="rusak_berat" {{ old('kondisi') == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat
                        </option>
                    </select>
                </div>
                <div>
                    <label for="tanggal_pengadaan" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                        Pengadaan</label>
                    <input type="date" id="tanggal_pengadaan" name="tanggal_pengadaan"
                        value="{{ old('tanggal_pengadaan') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 outline-none">
                </div>
            </div>
            <div>
                <label for="gambar" class="block text-sm font-medium text-gray-700 mb-1">Gambar Aset</label>
                <input type="file" id="gambar" name="gambar" accept="image/*"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 outline-none file:mr-3 file:rounded file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:text-sm file:font-medium">
            </div>
            <div>
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 outline-none">{{ old('deskripsi') }}</textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white px-6 py-2 rounded-lg text-sm font-medium shadow-sm">Simpan</button>
                <a href="{{ route('asets.index') }}"
                    class="px-6 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg text-sm">Batal</a>
            </div>
        </form>
    </div>
@endsection