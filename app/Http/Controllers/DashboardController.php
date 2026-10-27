<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Pemeliharaan;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAset = Aset::count();
        $asetBaik = Aset::where('kondisi', 'baik')->count();
        $asetRusak = Aset::whereIn('kondisi', ['rusak_ringan', 'rusak_berat'])->count();
        $totalPemeliharaan = Pemeliharaan::count();
        $pemeliharaanProses = Pemeliharaan::where('status', 'proses')->count();
        $totalPetugas = User::where('role', 'petugas')->count();

        $asetPerKategori = Aset::selectRaw('kategori, count(*) as total')
            ->whereNotNull('kategori')
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        $pemeliharaanTerbaru = Pemeliharaan::with(['aset', 'petugas'])
            ->latest()
            ->limit(5)
            ->get();

        $asetUnggulan = Aset::whereIn('nama', [
            'Proyektor',
            'Printer Multifungsi',
            'Meja Kerja',
            'Laptop Kantor',
            'Komputer Server',
            'Kamera CCTV',
            'AC Split 2 PK',
        ])
            ->orderBy('nama')
            ->get()
            ->each(function (Aset $aset) {
                $aset->dashboard_image = $aset->image_url;
            });

        return view('dashboard', compact(
            'totalAset',
            'asetBaik',
            'asetRusak',
            'totalPemeliharaan',
            'pemeliharaanProses',
            'totalPetugas',
            'asetPerKategori',
            'pemeliharaanTerbaru',
            'asetUnggulan'
        ));
    }
}
