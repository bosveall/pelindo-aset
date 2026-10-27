<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Pemeliharaan;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    private const RUANGAN_BERBARCODE = [
        'Ruang Rapat',
        'Ruang Server',
        'Ruang Keuangan',
        'Ruang Teknik',
        'Ruang TI',
        'Ruang SISMAN',
        'Ruang Umum',
        'Ruang Pak GM',
    ];

    public function index()
    {
        $ruangan = Aset::query()
            ->whereIn('lokasi', self::RUANGAN_BERBARCODE)
            ->select('lokasi')
            ->selectRaw('count(*) as jumlah_aset')
            ->groupBy('lokasi')
            ->orderBy('lokasi')
            ->get();

        $jumlahAsetPerRuangan = $ruangan->pluck('jumlah_aset', 'lokasi');
        $ruangan = collect(self::RUANGAN_BERBARCODE)
            ->map(fn(string $lokasi) => (object) [
                'lokasi' => $lokasi,
                'jumlah_aset' => $jumlahAsetPerRuangan->get($lokasi, 0),
            ])
            ->sortBy('lokasi')
            ->values();

        return view('ruangan.index', compact('ruangan'));
    }

    public function show(string $lokasi)
    {
        abort_unless(in_array($lokasi, self::RUANGAN_BERBARCODE, true), 404);

        $asets = Aset::withCount('pemeliharaans')
            ->where('lokasi', $lokasi)
            ->orderBy('nama')
            ->get();
        $jenisOptions = Pemeliharaan::query()
            ->whereNotNull('jenis')
            ->where('jenis', '<>', '')
            ->distinct()
            ->orderBy('jenis')
            ->pluck('jenis');

        return view('ruangan.show', compact('lokasi', 'asets', 'jenisOptions'));
    }

    public function store(Request $request, string $lokasi)
    {
        abort_unless(in_array($lokasi, self::RUANGAN_BERBARCODE, true), 404);

        $data = $request->validate([
            'aset_id' => ['required', 'integer'],
            'jenis' => ['required', 'string', 'max:255'],
            'keterangan' => ['required', 'string'],
        ]);

        $aset = Aset::whereKey($data['aset_id'])
            ->where('lokasi', $lokasi)
            ->firstOrFail();

        Pemeliharaan::create([
            'aset_id' => $aset->id,
            'tanggal' => now(),
            'jenis' => $data['jenis'],
            'keterangan' => $data['keterangan'],
            'status' => 'menunggu',
        ]);

        return redirect()->route('ruangan.show', ['lokasi' => $lokasi])
            ->with('success', 'Permintaan pemeliharaan berhasil dikirim.');
    }
}