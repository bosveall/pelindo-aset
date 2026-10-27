@extends('layouts.app')

@section('title', 'Tambah Pemeliharaan')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Tambah Pemeliharaan</h1>
        <p class="text-gray-500">Catat riwayat pemeliharaan aset</p>
    </div>

    <div class="bg-white rounded-xl shadow p-6 max-w-3xl">
        <form method="POST" action="{{ route('pemeliharaans.store') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label for="aset_id" class="block text-sm font-medium text-gray-700 mb-1">Aset <span
                            class="text-red-500">*</span></label>
                    <select id="aset_id" name="aset_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 outline-none">
                        <option value="">-- Pilih Aset --</option>
                        @foreach($asets as $aset)
                            <option value="{{ $aset->id }}" {{ old('aset_id', $asetId) == $aset->id ? 'selected' : '' }}>
                                {{ $aset->kode_aset }} - {{ $aset->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                    <input type="datetime-local" id="tanggal" name="tanggal" value="{{ old('tanggal') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 outline-none">
                </div>
                <div>
                    <label for="jadwal_pemeliharaan" class="block text-sm font-medium text-gray-700 mb-1">Jadwal
                        Pemeliharaan</label>
                    <input type="date" id="jadwal_pemeliharaan" name="jadwal_pemeliharaan"
                        value="{{ old('jadwal_pemeliharaan') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 outline-none">
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="status" name="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 outline-none">
                        <option value="menunggu" {{ old('status', 'menunggu') === 'menunggu' ? 'selected' : '' }}>Menunggu
                        </option>
                        <option value="proses" {{ old('status') === 'proses' ? 'selected' : '' }}>Proses</option>
                        <option value="selesai" {{ old('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div>
                    <label for="jenis" class="block text-sm font-medium text-gray-700 mb-1">Jenis Pemeliharaan</label>
                    <input type="text" id="jenis" name="jenis" value="{{ old('jenis') }}" list="jenis-pemeliharaan-options"
                        placeholder="cth: Pengecekan rutin, Perbaikan"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 outline-none">
                    @include('suggestion-list', ['listId' => 'jenis-pemeliharaan-options', 'options' => $jenisOptions])
                </div>
                <div class="md:col-span-2">
                    <label for="petugas_id" class="block text-sm font-medium text-gray-700 mb-1">Petugas</label>
                    <select id="petugas_id" name="petugas_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 outline-none">
                        <option value="">-- Pilih Petugas --</option>
                        @foreach($petugas as $p)
                            <option value="{{ $p->id }}" {{ old('petugas_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 outline-none">{{ old('keterangan') }}</textarea>
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="bg-slate-800 hover:bg-slate-700 text-white px-6 py-2 rounded-lg text-sm font-medium">Simpan</button>
                <a href="{{ route('pemeliharaans.index') }}"
                    class="px-6 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm">Batal</a>
            </div>
        </form>
    </div>
@endsection