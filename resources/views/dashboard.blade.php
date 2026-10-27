@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    @php
        $statusText = $asetRusak > 0
            ? 'Terdapat ' . $asetRusak . ' aset yang memerlukan perhatian segera.'
            : 'Semua aset dalam kondisi baik dan terpantau dengan baik.';
        $performancePercent = $totalAset > 0 ? round((($asetBaik / $totalAset) * 100), 0) : 0;
    @endphp

    <div class="dashboard-canvas space-y-6">
        <section class="dashboard-hero space-y-6" aria-label="Ringkasan kondisi aset">
            <div class="relative z-10 flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="dashboard-kicker text-xs font-semibold uppercase tracking-[0.24em]">Pelindo · Regional 1 Dumai</p>
                    <h2 class="mt-3 text-2xl font-semibold tracking-tight text-white sm:text-3xl">
                        Selamat datang, {{ Auth::user()->name }}
                    </h2>
                    <p class="dashboard-copy mt-2 max-w-xl text-sm leading-6">
                        Pusat kendali aset dan pemeliharaan — pantau operasional dalam satu ringkasan.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('asets.scan') }}"
                        class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-teal-300 to-cyan-200 px-4 py-2.5 text-sm font-bold text-slate-900 shadow-lg shadow-black/10 transition hover:brightness-105">
                        Scan barcode
                    </a>
                    <a href="{{ route('asets.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-white/25 bg-white/10 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-white/20">
                        Cari aset
                    </a>
                </div>
            </div>

            <div class="dashboard-condition relative z-10" aria-label="Kondisi sistem">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div class="flex items-end gap-2">
                        <span class="text-3xl font-semibold tracking-tight text-white">{{ $performancePercent }}%</span>
                        <span class="pb-1 text-sm text-slate-300">aset dalam kondisi baik</span>
                    </div>
                    <p class="max-w-xl text-sm leading-6 text-slate-300">{{ $statusText }}</p>
                </div>
                <div class="dashboard-progress mt-4 h-2.5 overflow-hidden rounded-full">
                    <div class="h-full rounded-full transition-all duration-700" style="width: {{ $performancePercent }}%"></div>
                </div>
            </div>
        </section>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            <div
                class="group rounded-2xl border border-sky-200 bg-gradient-to-br from-sky-100 to-sky-200/80 p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-lg hover:shadow-sky-900/10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-sky-800">Total aset</p>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ $totalAset }}</p>
                        <p class="mt-1 text-xs text-slate-500">terdaftar dalam inventaris</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-600 text-white shadow-lg shadow-sky-600/20 transition group-hover:scale-105">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="group rounded-2xl border border-emerald-200 bg-gradient-to-br from-emerald-100 to-emerald-200/80 p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-lg hover:shadow-emerald-900/10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-emerald-800">Aset baik</p>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ $asetBaik }}</p>
                        <p class="mt-1 text-xs text-slate-500">siap digunakan</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-600 text-white shadow-lg shadow-emerald-600/20 transition group-hover:scale-105">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="group rounded-2xl border border-rose-200 bg-gradient-to-br from-rose-100 to-rose-200/80 p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-lg hover:shadow-rose-900/10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-rose-800">Aset rusak</p>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ $asetRusak }}</p>
                        <p class="mt-1 text-xs text-slate-500">perlu pemeriksaan</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-600 text-white shadow-lg shadow-rose-600/20 transition group-hover:scale-105">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="group rounded-2xl border border-violet-200 bg-gradient-to-br from-violet-100 to-violet-200/80 p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-lg hover:shadow-violet-900/10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-violet-800">Total pemeliharaan</p>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ $totalPemeliharaan }}</p>
                        <p class="mt-1 text-xs text-slate-500">riwayat tercatat</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-violet-600 text-white shadow-lg shadow-violet-600/20 transition group-hover:scale-105">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="group rounded-2xl border border-amber-200 bg-gradient-to-br from-amber-100 to-amber-200/80 p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-lg hover:shadow-amber-900/10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-amber-800">Pemeliharaan berlangsung</p>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ $pemeliharaanProses }}</p>
                        <p class="mt-1 text-xs text-slate-500">sedang ditangani</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-500 text-white shadow-lg shadow-amber-500/20 transition group-hover:scale-105">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="group rounded-2xl border border-cyan-200 bg-gradient-to-br from-cyan-100 to-cyan-200/80 p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-lg hover:shadow-cyan-900/10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-cyan-800">Total petugas</p>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ $totalPetugas }}</p>
                        <p class="mt-1 text-xs text-slate-500">siap mendukung operasional</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-cyan-600 text-white shadow-lg shadow-cyan-600/20 transition group-hover:scale-105">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
        </div>
        </div>

        <section class="overflow-hidden rounded-2xl border border-teal-200 bg-gradient-to-br from-teal-50 to-cyan-100 shadow-sm">
            <div
                class="flex flex-col gap-3 border-b border-teal-100 bg-gradient-to-r from-teal-50 via-white to-cyan-50 px-5 py-5 sm:flex-row sm:items-end sm:justify-between sm:px-6">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.28em] text-teal-700">Visual aset</p>
                    <h3 class="mt-1 text-xl font-bold text-slate-900">Aset yang terpantau</h3>
                    <p class="mt-1 text-sm text-slate-500">Lihat inventaris utama dengan foto aktual dari koleksi aset.</p>
                </div>
                <a href="{{ route('asets.index') }}" class="text-sm font-medium text-teal-700 hover:text-teal-900">Buka
                    semua aset &rarr;</a>
            </div>
            <div class="grid gap-4 p-5 sm:grid-cols-2 sm:p-6 lg:grid-cols-3 xl:grid-cols-4">
                @forelse($asetUnggulan as $aset)
                    <a href="{{ route('asets.show', $aset) }}"
                        class="group overflow-hidden rounded-2xl border border-teal-200 bg-gradient-to-br from-teal-100 to-cyan-100 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-teal-400 hover:shadow-lg">
                        <div class="relative aspect-[4/3] overflow-hidden bg-gradient-to-br from-sky-100 to-teal-50">
                            <img src="{{ $aset->dashboard_image }}" alt="{{ $aset->nama }}"
                                class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                            <span
                                class="absolute left-3 top-3 rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider shadow-sm {{ $aset->kondisi === 'baik' ? 'bg-emerald-100 text-emerald-800' : ($aset->kondisi === 'rusak_ringan' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">{{ str_replace('_', ' ', $aset->kondisi) }}</span>
                        </div>
                        <div class="border-t border-slate-100 p-4">
                            <p class="truncate font-semibold text-slate-800">{{ $aset->nama }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ $aset->kategori }} &middot; {{ $aset->lokasi }}</p>
                        </div>
                    </a>
                @empty
                    <p class="text-sm text-slate-500">Belum ada aset bergambar untuk ditampilkan.</p>
                @endforelse
            </div>
        </section>

        <div class="grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
            <div class="overflow-hidden rounded-2xl border border-indigo-200 bg-gradient-to-br from-indigo-50 to-sky-100 shadow-sm">
                <div class="flex items-center justify-between border-b border-indigo-100 bg-gradient-to-r from-indigo-50 to-white px-6 py-4">
                    <div>
                        <h3 class="font-bold text-slate-900">Distribusi Aset per Kategori</h3>
                        <p class="text-sm text-slate-500">Komposisi aset berdasarkan kategori</p>
                    </div>
                    <div class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-bold text-indigo-700">
                        {{ $totalAset }} total
                    </div>
                </div>
                <div class="p-6">
                    @if($asetPerKategori->isEmpty())
                        <p class="text-sm text-slate-500">Belum ada data aset.</p>
                    @else
                        @php
                            $total = $asetPerKategori->sum('total');
                        @endphp
                        <div class="space-y-4">
                            @foreach($asetPerKategori as $kategori)
                                <div>
                                    <div class="mb-2 flex items-center justify-between text-sm">
                                        <span class="font-medium capitalize text-slate-700">{{ $kategori->kategori }}</span>
                                        <span class="text-slate-500">{{ $kategori->total }} aset</span>
                                    </div>
                                    <div class="h-2.5 w-full rounded-full bg-slate-100">
                                        <div class="h-2.5 rounded-full bg-gradient-to-r from-indigo-500 via-teal-500 to-cyan-400"
                                            style="width: {{ $total > 0 ? ($kategori->total / $total) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="rounded-2xl border border-amber-200 bg-gradient-to-br from-amber-100 to-orange-100 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-slate-900">Prioritas Hari Ini</h3>
                        <p class="text-sm text-slate-500">Hal yang perlu diperhatikan</p>
                    </div>
                    <div class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-800">
                        {{ $asetRusak }} butuh tindak lanjut
                    </div>
                </div>

                <div class="mt-5 space-y-3">
                    <div class="rounded-xl border border-amber-300 bg-amber-50/80 p-4">
                        <p class="text-sm font-bold text-amber-900">Pemeliharaan aktif</p>
                        <p class="mt-1 text-sm text-slate-500">Saat ini ada {{ $pemeliharaanProses }} pekerjaan yang sedang
                            berlangsung.</p>
                    </div>
                    <div class="rounded-xl border border-sky-300 bg-sky-50/80 p-4">
                        <p class="text-sm font-bold text-sky-900">Tim siap membantu</p>
                        <p class="mt-1 text-sm text-slate-500">Tersedia {{ $totalPetugas }} petugas untuk mendukung
                            penanganan.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-cyan-200 bg-gradient-to-br from-cyan-50 to-sky-100 shadow-sm">
            <div class="flex items-center justify-between border-b border-sky-100 bg-gradient-to-r from-sky-50 to-white px-6 py-4">
                <div>
                    <h3 class="font-bold text-slate-900">Pemeliharaan Terbaru</h3>
                    <p class="text-sm text-slate-500">Riwayat kegiatan pemeliharaan terbaru</p>
                </div>
                <a href="{{ route('pemeliharaans.index') }}"
                    class="text-sm font-medium text-teal-600 hover:text-teal-700">Lihat semua</a>
            </div>
            <div class="p-6">
                @if($pemeliharaanTerbaru->isEmpty())
                    <p class="text-sm text-slate-500">Belum ada riwayat pemeliharaan.</p>
                @else
                    <ul class="space-y-3">
                        @foreach($pemeliharaanTerbaru as $pm)
                            <li
                                class="flex flex-col gap-2 border-b border-slate-100 py-4 last:border-b-0 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex items-start gap-3">
                                    <div class="mt-1 h-3 w-3 rounded-full bg-gradient-to-br from-teal-400 to-cyan-600 ring-4 ring-teal-50"></div>
                                    <div>
                                        <p class="font-medium text-slate-800">{{ $pm->aset?->nama }}</p>
                                        <p class="text-sm text-slate-500">{{ $pm->jenis }} · Jadwal:
                                            {{ $pm->jadwal_pemeliharaan?->format('d/m/Y') ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
@endsection