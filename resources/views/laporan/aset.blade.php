@extends('layouts.app')

@section('title', 'Laporan Aset')

@section('content')
    @php
        $jumlahBaik = $asets->where('kondisi', 'baik')->count();
        $jumlahRusakRingan = $asets->where('kondisi', 'rusak_ringan')->count();
        $jumlahRusakBerat = $asets->where('kondisi', 'rusak_berat')->count();
    @endphp

    <div class="space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div class="flex items-center gap-2">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center">
                    <img src="{{ asset('assets/icons/pelindo-logo.png') }}" alt="Logo Pelindo" class="h-full w-full object-contain">
                </span>
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-teal-700">Pelindo · Regional 1 Dumai</p>
                    <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">Laporan Aset</h1>
                    <p class="mt-1 text-sm text-slate-500">Ringkasan dan daftar aset berdasarkan filter yang dipilih.</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-2 no-print">
                <a href="{{ route('laporan.aset.export.pdf', request()->query()) }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm font-semibold text-rose-700 transition hover:bg-rose-100">
                    <span aria-hidden="true">PDF</span>
                    Unduh PDF
                </a>
                <a href="{{ route('laporan.aset.export.excel', request()->query()) }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-100">
                    <span aria-hidden="true">XLS</span>
                    Unduh Excel
                </a>
                <button type="button" onclick="window.print()"
                    class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">
                    Cetak
                </button>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
            <article class="rounded-2xl border border-teal-100 bg-gradient-to-br from-teal-600 to-emerald-700 p-4 text-white shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-teal-100">Total hasil</p>
                <p class="mt-2 text-3xl font-bold">{{ $asets->count() }}</p>
                <p class="mt-1 text-xs text-teal-100">aset ditemukan</p>
            </article>
            <article class="rounded-2xl border border-emerald-100 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Kondisi baik</p>
                <p class="mt-2 text-3xl font-bold text-emerald-700">{{ $jumlahBaik }}</p>
                <p class="mt-1 text-xs text-slate-500">aset siap digunakan</p>
            </article>
            <article class="rounded-2xl border border-amber-100 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Rusak ringan</p>
                <p class="mt-2 text-3xl font-bold text-amber-600">{{ $jumlahRusakRingan }}</p>
                <p class="mt-1 text-xs text-slate-500">perlu pemantauan</p>
            </article>
            <article class="rounded-2xl border border-rose-100 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Rusak berat</p>
                <p class="mt-2 text-3xl font-bold text-rose-600">{{ $jumlahRusakBerat }}</p>
                <p class="mt-1 text-xs text-slate-500">perlu tindak lanjut</p>
            </article>
        </div>

        <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm no-print">
            <div class="mb-4">
                <h2 class="font-semibold text-slate-900">Filter laporan</h2>
                <p class="mt-1 text-xs text-slate-500">Pilih kondisi, kategori, atau lokasi aset.</p>
            </div>
            <form method="GET" action="{{ route('laporan.aset') }}"
                class="grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-4">
                <div>
                    <label for="filter-kondisi" class="mb-1.5 block text-xs font-semibold text-slate-600">Kondisi</label>
                    <select id="filter-kondisi" name="kondisi"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-teal-100">
                        <option value="">Semua Kondisi</option>
                        <option value="baik" {{ request('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="rusak_ringan" {{ request('kondisi') == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="rusak_berat" {{ request('kondisi') == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                    </select>
                </div>
                <div>
                    <label for="filter-kategori" class="mb-1.5 block text-xs font-semibold text-slate-600">Kategori</label>
                    <select id="filter-kategori" name="kategori"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-teal-100">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori }}" {{ request('kategori') == $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="filter-lokasi" class="mb-1.5 block text-xs font-semibold text-slate-600">Lokasi</label>
                    <select id="filter-lokasi" name="lokasi"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-teal-100">
                        <option value="">Semua Lokasi</option>
                        @foreach($lokasis as $lokasi)
                            <option value="{{ $lokasi }}" {{ request('lokasi') === $lokasi ? 'selected' : '' }}>{{ $lokasi }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="flex-1 rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-teal-800">Terapkan</button>
                    <a href="{{ route('laporan.aset') }}"
                        class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-100">Reset</a>
                </div>
            </form>
        </section>

        <section class="print-area overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-1 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="font-semibold text-slate-900">Daftar Aset</h2>
                    <p class="mt-1 text-xs text-slate-500">Data sesuai filter laporan · {{ now()->format('d/m/Y H:i') }}</p>
                </div>
                <span class="w-fit rounded-full bg-teal-50 px-3 py-1 text-xs font-semibold text-teal-700">
                    {{ $asets->count() }} data
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">No</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Kode Aset</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Nama Aset</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Kategori</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Lokasi</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Kondisi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($asets as $index => $aset)
                            <tr class="transition hover:bg-teal-50/40">
                                <td class="whitespace-nowrap px-5 py-3.5 text-sm text-slate-400">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                                <td class="whitespace-nowrap px-5 py-3.5 text-sm font-semibold text-slate-700">{{ $aset->kode_aset }}</td>
                                <td class="px-5 py-3.5 text-sm font-medium text-slate-900">{{ $aset->nama }}</td>
                                <td class="px-5 py-3.5 text-sm capitalize text-slate-600">{{ $aset->kategori ?? '-' }}</td>
                                <td class="px-5 py-3.5 text-sm text-slate-600">{{ $aset->lokasi ?? '-' }}</td>
                                <td class="whitespace-nowrap px-5 py-3.5 text-sm">
                                    @php
                                        $kondisiClass = match ($aset->kondisi) {
                                            'baik' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                            'rusak_ringan' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                            'rusak_berat' => 'bg-rose-50 text-rose-700 ring-rose-600/20',
                                            default => 'bg-slate-100 text-slate-600 ring-slate-500/20',
                                        };
                                    @endphp
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold capitalize ring-1 ring-inset {{ $kondisiClass }}">
                                        {{ str_replace('_', ' ', $aset->kondisi) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-16 text-center">
                                    <p class="font-semibold text-slate-700">Belum ada aset yang cocok</p>
                                    <p class="mt-1 text-sm text-slate-500">Coba ubah atau reset filter laporan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <style>
        @media print {
            aside, nav, button, form, .no-print { display: none !important; }
            .ml-64 { margin-left: 0 !important; }
            .print-area { border: 1px solid #cbd5e1 !important; box-shadow: none !important; }
            body { background: #fff !important; }
        }
    </style>
@endsection
