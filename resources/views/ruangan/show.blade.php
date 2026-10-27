<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $lokasi }} - Pemeliharaan Aset</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="room-portal min-h-screen bg-slate-100 text-slate-800">
    <main class="mx-auto max-w-2xl px-4 py-6 sm:py-10">
        <div class="room-portal-banner mb-6 rounded-3xl p-6 text-white shadow-xl">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-teal-300">Pemeliharaan Aset</p>
            <h1 class="mt-2 text-2xl font-bold">{{ $lokasi }}</h1>
            <p class="mt-2 text-sm text-slate-300">Scan QR ini untuk melaporkan aset ruangan yang membutuhkan
                pemeliharaan.</p>
        </div>
        @include('suggestion-list', ['listId' => 'jenis-pemeliharaan-options', 'options' => $jenisOptions])

        @if(session('success'))
            <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @if($asets->isEmpty())
            <div class="rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-800">
                Belum ada aset yang terdaftar di ruangan ini.
            </div>
        @endif

        <div class="space-y-4">
            @foreach($asets as $aset)
                <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">{{ $aset->kode_aset }}
                            </p>
                            <h2 class="mt-1 text-lg font-semibold text-slate-800">{{ $aset->nama }}</h2>
                            <p class="mt-1 text-sm text-slate-500">{{ $aset->kategori ?: 'Tanpa kategori' }} · Kondisi
                                {{ str_replace('_', ' ', $aset->kondisi) }}
                            </p>
                        </div>
                        @auth
                            <div class="flex shrink-0 flex-col gap-2">
                                <a href="{{ route('asets.edit', $aset) }}"
                                    class="rounded-lg bg-slate-100 px-3 py-2 text-center text-xs font-semibold text-slate-700 hover:bg-slate-200">
                                    Edit aset
                                </a>
                                <a href="{{ route('pemeliharaans.create', ['aset_id' => $aset->id]) }}"
                                    class="rounded-lg bg-teal-600 px-3 py-2 text-center text-xs font-semibold text-white hover:bg-teal-700">
                                    Atur jadwal
                                </a>
                            </div>
                        @endauth
                    </div>
                    <details class="mt-4 border-t border-slate-100 pt-4">
                        <summary class="cursor-pointer text-sm font-semibold text-teal-700">Laporkan aset perlu pemeliharaan
                        </summary>
                        <form method="POST" action="{{ route('ruangan.pemeliharaan.store', ['lokasi' => $lokasi]) }}"
                            class="mt-4 space-y-3">
                            @csrf
                            <input type="hidden" name="aset_id" value="{{ $aset->id }}">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Jenis pemeliharaan</label>
                                <input name="jenis" list="jenis-pemeliharaan-options" required placeholder="Contoh: Pengecekan rutin"
                                    class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Keterangan</label>
                                <textarea name="keterangan" required rows="3"
                                    placeholder="Jelaskan masalah atau pekerjaan yang diperlukan"
                                    class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100"></textarea>
                            </div>
                            <button
                                class="w-full rounded-xl bg-teal-600 px-4 py-3 text-sm font-semibold text-white hover:bg-teal-700">Kirim
                                permintaan</button>
                        </form>
                    </details>
                </section>
            @endforeach
        </div>
    </main>
</body>

</html>