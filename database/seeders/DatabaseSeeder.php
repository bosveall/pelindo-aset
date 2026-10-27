<?php

namespace Database\Seeders;

use App\Models\Aset;
use App\Models\Pemeliharaan;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@pelindo.com'],
            [
                'name' => 'Administrator',
                'password' => 'password',
                'role' => 'admin',
                'no_hp' => '081234567890',
            ]
        );

        // Petugas
        $petugas = User::updateOrCreate(
            ['email' => 'petugas@pelindo.com'],
            [
                'name' => 'Budi Santoso',
                'password' => 'password',
                'role' => 'petugas',
                'no_hp' => '081298765432',
            ]
        );

        // Data aset barcode: setiap ruangan memiliki 8 aset, kecuali Ruang TI.
        $ruanganLain = [
            'Ruang Rapat',
            'Ruang Server',
            'Ruang Keuangan',
            'Ruang Teknik',
            'Ruang SISMAN',
            'Ruang Umum',
            'Ruang Pak GM',
        ];
        $ruanganBarcode = [...$ruanganLain, 'Ruang TI'];
        Aset::whereIn('lokasi', $ruanganBarcode)->delete();

        $namaAsetPerRuangan = [
            'Komputer',
            'Laptop',
            'Printer',
            'Meja Kerja',
            'Kursi Kerja',
            'AC Split',
            'Proyektor',
            'Lemari Arsip',
        ];
        $asetList = [];
        $nomorAset = 1;

        foreach ($ruanganLain as $lokasi) {
            foreach ($namaAsetPerRuangan as $nama) {
                $asetList[] = Aset::create([
                    'kode_aset' => sprintf('AST-%03d', $nomorAset++),
                    'nama' => $nama . ' ' . $lokasi,
                    'kategori' => in_array($nama, ['Meja Kerja', 'Kursi Kerja', 'Lemari Arsip'], true) ? 'Furniture' : 'Elektronik',
                    'merk' => 'Umum',
                    'lokasi' => $lokasi,
                    'kondisi' => 'baik',
                    'status' => 'aktif',
                    'tanggal_pengadaan' => now()->subYear(),
                    'deskripsi' => 'Aset untuk kebutuhan ' . strtolower($lokasi) . '.',
                ]);
            }
        }

        foreach (['Meja', 'Printer', 'Komputer'] as $nama) {
            $asetList[] = Aset::create([
                'kode_aset' => sprintf('AST-%03d', $nomorAset++),
                'nama' => $nama . ' Ruang TI',
                'kategori' => $nama === 'Meja' ? 'Furniture' : ($nama === 'Komputer' ? 'Komputer' : 'Elektronik'),
                'merk' => 'Umum',
                'lokasi' => 'Ruang TI',
                'kondisi' => 'baik',
                'status' => 'aktif',
                'tanggal_pengadaan' => now()->subYear(),
                'deskripsi' => 'Aset untuk kebutuhan Ruang TI.',
            ]);
        }

        // Contoh data pemeliharaan
        $pemeliharaanData = [
            ['aset_id' => $asetList[0]->id, 'tanggal' => now()->subMonths(2), 'jenis' => 'Pengecekan rutin', 'keterangan' => 'Pembersihan dan pengecekan hardware.', 'status' => 'selesai'],
            ['aset_id' => $asetList[1]->id, 'tanggal' => now()->subDays(5), 'jenis' => 'Perbaikan', 'keterangan' => 'Perbaikan layar dan keyboard.', 'status' => 'menunggu'],
            ['aset_id' => $asetList[2]->id, 'tanggal' => now()->subWeeks(3), 'jenis' => 'Service printer', 'keterangan' => 'Pembersihan head printer dan penggantian tinta.', 'status' => 'selesai'],
            ['aset_id' => $asetList[3]->id, 'tanggal' => now()->subMonths(2), 'jenis' => 'Servis AC', 'keterangan' => 'Pembersihan filter dan pengecekan refrigerant.', 'status' => 'selesai'],
            ['aset_id' => $asetList[5]->id, 'tanggal' => now()->subWeeks(5), 'jenis' => 'Kalibrasi proyektor', 'keterangan' => 'Kalibrasi fokus dan brightness.', 'status' => 'menunggu'],
            ['aset_id' => $asetList[6]->id, 'tanggal' => now()->subDays(12), 'jenis' => 'Pemeliharaan CCTV', 'keterangan' => 'Pengecekan hasil rekaman dan kepastian koneksi jaringan.', 'status' => 'selesai'],
        ];

        foreach ($pemeliharaanData as $data) {
            Pemeliharaan::firstOrCreate(
                [
                    'aset_id' => $data['aset_id'],
                    'tanggal' => $data['tanggal'],
                    'jenis' => $data['jenis'],
                    'petugas_id' => $petugas->id,
                ],
                [
                    'keterangan' => $data['keterangan'],
                    'status' => $data['status'],
                ]
            );
        }
    }
}
