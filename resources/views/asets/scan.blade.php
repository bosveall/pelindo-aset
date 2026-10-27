@extends('layouts.app')

@section('title', 'Scan Barcode')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-teal-600">Pencarian cepat</p>
                <h1 class="mt-1 text-2xl font-bold text-slate-900">Scan barcode aset</h1>
                <p class="mt-2 text-sm text-slate-500">Arahkan kamera ke kode aset untuk membuka data yang sesuai.</p>
            </div>
            <a href="{{ route('asets.index') }}" class="text-sm font-semibold text-teal-700 hover:text-teal-900">Lihat semua
                aset</a>
        </div>

        <div class="grid gap-6 lg:grid-cols-[minmax(0,1.1fr)_minmax(280px,0.9fr)]">
            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-slate-950 shadow-sm">
                <div class="relative aspect-square overflow-hidden">
                    <video id="barcode-video" data-lookup-url="{{ route('asets.scan.lookup') }}"
                        class="h-full w-full object-cover" playsinline muted></video>
                    <div id="scanner-placeholder"
                        class="absolute inset-0 flex flex-col items-center justify-center gap-3 px-6 text-center text-white">
                        <div
                            class="flex h-16 w-16 items-center justify-center rounded-2xl border border-teal-300/30 bg-teal-400/10 text-3xl text-teal-200">
                            ⌁</div>
                        <div>
                            <p class="font-semibold">Kamera belum aktif</p>
                            <p class="mt-1 text-sm text-slate-400">Gunakan kamera perangkat untuk membaca barcode.</p>
                        </div>
                    </div>
                    <div id="scanner-frame"
                        class="pointer-events-none absolute left-1/2 top-1/2 aspect-square w-[68%] -translate-x-1/2 -translate-y-1/2 rounded-xl border-2 border-teal-300 opacity-0 shadow-[0_0_0_999px_rgba(2,6,23,0.45)] transition-opacity">
                    </div>
                </div>
                <div
                    class="flex flex-col gap-3 border-t border-white/10 p-4 sm:flex-row sm:items-center sm:justify-between">
                    <p id="scanner-status" aria-live="polite" class="text-sm text-slate-300">Tekan tombol mulai untuk
                        memindai.</p>
                    <button id="start-scanner" type="button"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-teal-500 px-4 py-2.5 text-sm font-semibold text-slate-950 hover:bg-teal-400">
                        Mulai scan
                    </button>
                </div>
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-teal-50 text-teal-700">#</div>
                    <div>
                        <h2 class="font-semibold text-slate-900">Masukkan kode manual</h2>
                        <p class="text-sm text-slate-500">Alternatif jika kamera tidak tersedia.</p>
                    </div>
                </div>
                <form action="{{ route('asets.index') }}" method="GET" class="mt-6 space-y-3">
                    <label for="asset-code" class="block text-sm font-medium text-slate-700">Kode aset</label>
                    <input id="asset-code" name="search" type="text" autocomplete="off" placeholder="Contoh: AST-001"
                        class="w-full rounded-xl border border-slate-300 px-3 py-3 text-sm uppercase outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                    <button type="submit"
                        class="w-full rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-800">Cari
                        aset</button>
                </form>
                @if(session('scan_error'))
                    <p role="alert" class="mt-4 rounded-xl bg-amber-50 px-3 py-2 text-sm text-amber-800">
                        {{ session('scan_error') }}</p>
                @endif
                <p id="scanner-help" class="mt-5 border-t border-slate-100 pt-4 text-xs leading-5 text-slate-500">Izinkan
                    akses kamera. Kamera HP memerlukan koneksi HTTPS atau localhost.</p>
            </section>
        </div>
    </div>
@endsection