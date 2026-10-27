@extends('layouts.app')

@section('title', 'Laporan Pemeliharaan')

@section('content')
    @php
        $jumlahAset = $pemeliharaans->pluck('aset_id')->filter()->unique()->count();
        $periodeLaporan = request('dari') && request('sampai')
            ? request('dari') . ' – ' . request('sampai')
            : 'Semua periode';
    @endphp

    <div class="space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div class="flex items-center gap-2">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center">
                    <img src="{{ asset('assets/icons/pelindo-logo.png') }}" alt="Logo Pelindo" class="h-full w-full object-contain">
                </span>
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-teal-700">Pelindo · Regional 1 Dumai</p>
                    <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">Laporan Pemeliharaan</h1>
                    <p class="mt-1 text-sm text-slate-500">Riwayat pemeliharaan aset pada periode yang dipilih.</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-2 no-print">
                <a href="{{ route('laporan.pemeliharaan.export.pdf', request()->query()) }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm font-semibold text-rose-700 transition hover:bg-rose-100">
                    <span aria-hidden="true">PDF</span>
                    Unduh PDF
                </a>
                <a href="{{ route('laporan.pemeliharaan.export.excel', request()->query()) }}"
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

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
            <article class="rounded-2xl border border-teal-100 bg-gradient-to-br from-teal-600 to-emerald-700 p-4 text-white shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-teal-100">Total hasil</p>
                <p class="mt-2 text-3xl font-bold">{{ $pemeliharaans->count() }}</p>
                <p class="mt-1 text-xs text-teal-100">riwayat pemeliharaan</p>
            </article>
            <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Aset terpelihara</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $jumlahAset }}</p>
                <p class="mt-1 text-xs text-slate-500">aset berbeda</p>
            </article>
            <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Periode laporan</p>
                <p class="mt-2 text-lg font-bold text-slate-900">{{ $periodeLaporan }}</p>
                <p class="mt-1 text-xs text-slate-500">rentang tanggal aktif</p>
            </article>
        </div>

        <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm no-print">
            <div class="mb-4">
                <h2 class="font-semibold text-slate-900">Filter periode</h2>
                <p class="mt-1 text-xs text-slate-500">Tentukan rentang tanggal untuk mempersempit hasil laporan.</p>
            </div>
            <form method="GET" action="{{ route('laporan.pemeliharaan') }}"
                class="grid grid-cols-1 gap-3 md:grid-cols-3">
                <div>
                    <label for="filter-dari" class="mb-1.5 block text-xs font-semibold text-slate-600">Dari tanggal</label>
                    <input id="filter-dari" type="date" name="dari" value="{{ request('dari') }}"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-teal-100">
                </div>
                <div>
                    <label for="filter-sampai" class="mb-1.5 block text-xs font-semibold text-slate-600">Sampai tanggal</label>
                    <input id="filter-sampai" type="date" name="sampai" value="{{ request('sampai') }}"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-teal-100">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="flex-1 rounded-xl bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-teal-800">Terapkan</button>
                    <a href="{{ route('laporan.pemeliharaan') }}"
                        class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-100">Reset</a>
                </div>
            </form>
        </section>

        <section class="print-area overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-1 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="font-semibold text-slate-900">Riwayat Pemeliharaan</h2>
                    <p class="mt-1 text-xs text-slate-500">Periode: {{ $periodeLaporan }} · Dicetak {{ now()->format('d/m/Y H:i') }}</p>
                </div>
                <span class="w-fit rounded-full bg-teal-50 px-3 py-1 text-xs font-semibold text-teal-700">
                    {{ $pemeliharaans->count() }} data
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">No</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Aset</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Tanggal</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Jadwal</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Jenis</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Petugas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($pemeliharaans as $index => $pm)
                            <tr class="transition hover:bg-teal-50/40">
                                <td class="whitespace-nowrap px-5 py-3.5 text-sm text-slate-400">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-5 py-3.5 text-sm">
                                    <p class="font-semibold text-slate-900">{{ $pm->aset?->nama ?? 'Aset dihapus' }}</p>
                                    <p class="mt-0.5 text-xs text-slate-500">{{ $pm->aset?->kode_aset ?? '-' }}</p>
                                </td>
                                <td class="whitespace-nowrap px-5 py-3.5 text-sm text-slate-600">{{ $pm->tanggal?->format('d/m/Y') ?? '-' }}</td>
                                <td class="whitespace-nowrap px-5 py-3.5 text-sm text-slate-600">{{ $pm->jadwal_pemeliharaan?->format('d/m/Y') ?? '-' }}</td>
                                <td class="px-5 py-3.5 text-sm">
                                    <span class="inline-flex rounded-full bg-sky-50 px-2.5 py-1 text-xs font-semibold text-sky-700">
                                        {{ $pm->jenis ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-sm text-slate-600">{{ $pm->petugas?->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-16 text-center">
                                    <p class="font-semibold text-slate-700">Belum ada riwayat pemeliharaan</p>
                                    <p class="mt-1 text-sm text-slate-500">Coba pilih periode lain atau reset filter.</p>
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
