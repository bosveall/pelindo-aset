@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
    <div class="mb-6">
        <h1 class="text-xl font-bold text-slate-800">Laporan</h1>
        <p class="text-sm text-slate-500">Pilih jenis laporan yang ingin dilihat</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <a href="{{ route('laporan.aset') }}"
            class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-md hover:border-teal-200 transition group">
            <div
                class="w-14 h-14 rounded-xl bg-slate-100 flex items-center justify-center mb-4 group-hover:bg-gradient-to-br group-hover:from-teal-500 group-hover:to-emerald-600">
                <svg class="w-7 h-7 text-slate-700 group-hover:text-white" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
            <h2 class="text-lg font-semibold text-slate-800 mb-1">Laporan Aset</h2>
            <p class="text-sm text-slate-500">Lihat laporan seluruh data aset dengan filter kondisi dan kategori.
            </p>
            <span class="inline-block mt-4 text-sm text-teal-600 font-medium group-hover:underline">Buka laporan →</span>
        </a>

        <a href="{{ route('laporan.pemeliharaan') }}"
            class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-md hover:border-teal-200 transition group">
            <div
                class="w-14 h-14 rounded-xl bg-blue-50 flex items-center justify-center mb-4 group-hover:bg-gradient-to-br group-hover:from-teal-500 group-hover:to-emerald-600">
                <svg class="w-7 h-7 text-blue-600 group-hover:text-white" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
            </div>
            <h2 class="text-lg font-semibold text-slate-800 mb-1">Laporan Pemeliharaan</h2>
            <p class="text-sm text-slate-500">Lihat laporan riwayat pemeliharaan dengan periode yang dipilih.</p>
            <span class="inline-block mt-4 text-sm text-teal-600 font-medium group-hover:underline">Buka laporan →</span>
        </a>
    </div>
@endsection