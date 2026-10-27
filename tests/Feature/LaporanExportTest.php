<?php

namespace Tests\Feature;

use App\Models\Aset;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_download_aset_pdf_export(): void
    {
        $user = User::factory()->create([
            'role' => 'petugas',
        ]);

        Aset::create([
            'nama' => 'Laptop',
            'kode_aset' => 'AST-001',
            'kondisi' => 'baik',
            'status' => 'aktif',
            'kategori' => 'Elektronik',
        ]);

        $response = $this->actingAs($user)
            ->get(route('laporan.aset.export.pdf'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_user_can_download_aset_excel_export(): void
    {
        $user = User::factory()->create([
            'role' => 'petugas',
        ]);

        Aset::create([
            'nama' => 'Server',
            'kode_aset' => 'AST-002',
            'kondisi' => 'rusak_ringan',
            'status' => 'nonaktif',
            'kategori' => 'IT',
        ]);

        $response = $this->actingAs($user)
            ->get(route('laporan.aset.export.excel'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_laporan_pages_do_not_show_status_field(): void
    {
        $user = User::factory()->create([
            'role' => 'petugas',
        ]);

        $asetResponse = $this->actingAs($user)->get(route('laporan.aset'));
        $asetResponse->assertOk();
        $asetResponse->assertDontSee('Semua Status');
        $asetResponse->assertDontSee('Status');

        $pemeliharaanResponse = $this->actingAs($user)->get(route('laporan.pemeliharaan'));
        $pemeliharaanResponse->assertOk();
        $pemeliharaanResponse->assertDontSee('Semua Status');
        $pemeliharaanResponse->assertDontSee('Status');
    }

    public function test_main_management_pages_do_not_show_status_field(): void
    {
        $user = User::factory()->create([
            'role' => 'petugas',
        ]);

        $asetIndex = $this->actingAs($user)->get(route('asets.index'));
        $asetIndex->assertOk();
        $asetIndex->assertDontSee('Semua Status');
        $asetIndex->assertDontSee('Status');

        $pemeliharaanIndex = $this->actingAs($user)->get(route('pemeliharaans.index'));
        $pemeliharaanIndex->assertOk();
        $pemeliharaanIndex->assertDontSee('Semua Status');
        $pemeliharaanIndex->assertDontSee('Status');
    }

    public function test_status_is_not_required_when_creating_main_records(): void
    {
        $user = User::factory()->create([
            'role' => 'petugas',
        ]);

        $response = $this->actingAs($user)->post(route('asets.store'), [
            'kode_aset' => 'AST-100',
            'nama' => 'Laptop Baru',
            'kategori' => 'Elektronik',
            'lokasi' => 'Kantor',
            'kondisi' => 'baik',
            'tanggal_pengadaan' => '2025-01-10',
            'deskripsi' => 'Aset baru tanpa status.',
        ]);

        $response->assertRedirect(route('asets.index'));

        $aset = Aset::where('kode_aset', 'AST-100')->first();
        $this->assertNotNull($aset);

        $pemeliharaanResponse = $this->actingAs($user)->post(route('pemeliharaans.store'), [
            'aset_id' => $aset->id,
            'tanggal' => '2025-02-01',
            'jadwal_pemeliharaan' => '2025-02-05',
            'jenis' => 'Pengecekan rutin',
            'keterangan' => 'Tanpa status.',
            'petugas_id' => $user->id,
        ]);

        $pemeliharaanResponse->assertRedirect(route('pemeliharaans.index'));
    }
}
