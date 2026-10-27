@extends('layouts.app')

@section('title', 'QR Ruangan')

@section('content')
<div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <h1 class="text-xl font-bold text-slate-800">QR Ruangan</h1>
        <p class="text-sm text-slate-500">Cetak QR untuk ditempel di ruangan dan dibuka dari HP.</p>
    </div>
    <p class="text-xs text-slate-500">Pastikan alamat aplikasi dapat diakses dari jaringan HP.</p>
</div>

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">
    @forelse($ruangan as $item)
    @php($url = route('ruangan.show', ['lokasi' => $item->lokasi]))
    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="mb-4 flex items-start justify-between gap-3">
            <div>
                <h2 class="font-semibold text-slate-800">{{ $item->lokasi }}</h2>
                <p class="text-sm text-slate-500">{{ $item->jumlah_aset }} aset</p>
            </div>
            <span class="rounded-full bg-teal-50 px-2.5 py-1 text-xs font-medium text-teal-700">Scan HP</span>
        </div>
        <div class="flex justify-center rounded-xl bg-white p-3">
            <div data-qr-code="{{ $url }}" role="img" aria-label="QR {{ $item->lokasi }}"
                class="qr-code h-48 w-48"></div>
        </div>
        <p class="mt-3 break-all text-center text-xs text-slate-400">{{ $url }}</p>
        <div class="mt-4 flex gap-2">
            <a href="{{ $url }}" target="_blank"
                class="flex-1 rounded-lg bg-slate-100 px-3 py-2 text-center text-sm font-medium text-slate-700 hover:bg-slate-200">Buka</a>
            <button type="button" onclick="window.print()"
                class="flex-1 rounded-lg bg-teal-600 px-3 py-2 text-sm font-medium text-white hover:bg-teal-700">Cetak</button>
        </div>
    </article>
    @empty
    <div class="col-span-full rounded-2xl border border-dashed border-slate-300 p-10 text-center text-slate-500">
        Belum ada aset dengan lokasi. Isi lokasi aset terlebih dahulu.
    </div>
    @endforelse
</div>
@endsection