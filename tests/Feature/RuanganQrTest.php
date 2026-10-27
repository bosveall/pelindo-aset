<?php

namespace Tests\Feature;

use App\Models\Aset;
use App\Models\Pemeliharaan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RuanganQrTest extends TestCase
{
    use RefreshDatabase;

    public function test_room_qr_page_lists_only_configured_barcode_rooms(): void
    {
        Aset::create([
            'kode_aset' => 'AST-RUANG-000',
            'nama' => 'Meja Umum',
            'lokasi' => 'Lokasi Lain',
            'kondisi' => 'baik',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('ruangan.index'));

        $response->assertOk();
        $response->assertSee('Ruang Rapat');
        $response->assertSee('Ruang Pak GM');
        $response->assertSee('data-qr-code=', false);
        $response->assertDontSee('api.qrserver.com');
        $response->assertDontSee('Lokasi Lain');
    }

    public function test_configured_room_qr_can_be_opened_without_assets(): void
    {
        $response = $this->get(route('ruangan.show', ['lokasi' => 'Ruang TI']));

        $response->assertOk();
        $response->assertSee('Ruang TI');
    }

    public function test_room_qr_page_is_public_and_lists_room_assets(): void
    {
        Aset::create([
            'kode_aset' => 'AST-RUANG-001',
            'nama' => 'AC Ruang Server',
            'lokasi' => 'Ruang Server',
            'kondisi' => 'baik',
            'status' => 'aktif',
        ]);

        $response = $this->get(route('ruangan.show', ['lokasi' => 'Ruang Server']));

        $response->assertOk();
        $response->assertSee('AC Ruang Server');
        $response->assertSee('Laporkan aset perlu pemeliharaan');
    }

    public function test_deleting_room_asset_updates_qr_room_count_and_asset_list(): void
    {
        $deletedAset = Aset::create([
            'kode_aset' => 'AST-RUANG-HAPUS',
            'nama' => 'Aset Dihapus',
            'lokasi' => 'Ruang Server',
            'kondisi' => 'baik',
        ]);
        Aset::create([
            'kode_aset' => 'AST-RUANG-TERSISA',
            'nama' => 'Aset Tersisa',
            'lokasi' => 'Ruang Server',
            'kondisi' => 'baik',
        ]);

        $this->actingAs(User::factory()->create())
            ->delete(route('asets.destroy', $deletedAset))
            ->assertRedirect(route('asets.index'));

        $this->actingAs(User::factory()->create())
            ->get(route('ruangan.index'))
            ->assertOk()
            ->assertSeeText('1 aset');

        $this->get(route('ruangan.show', ['lokasi' => 'Ruang Server']))
            ->assertOk()
            ->assertDontSee('Aset Dihapus')
            ->assertSee('Aset Tersisa');
    }

    public function test_authenticated_user_can_edit_asset_from_room_qr_page(): void
    {
        $aset = Aset::create([
            'kode_aset' => 'AST-RUANG-EDIT',
            'nama' => 'Komputer Ruang TI',
            'lokasi' => 'Ruang TI',
            'kondisi' => 'baik',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('ruangan.show', ['lokasi' => 'Ruang TI']));

        $response->assertOk();
        $response->assertSee(route('asets.edit', $aset));
        $response->assertSee('Edit aset');
    }

    public function test_room_qr_can_create_maintenance_request_for_room_asset(): void
    {
        $aset = Aset::create([
            'kode_aset' => 'AST-RUANG-002',
            'nama' => 'Panel Listrik',
            'lokasi' => 'Ruang Teknik',
            'kondisi' => 'baik',
            'status' => 'aktif',
        ]);

        $response = $this->withSession(['_token' => 'test-token'])
            ->post(route('ruangan.pemeliharaan.store', ['lokasi' => 'Ruang Teknik']), [
                '_token' => 'test-token',
                'aset_id' => $aset->id,
                'jenis' => 'Pengecekan rutin',
                'keterangan' => 'Mohon diperiksa.',
            ]);

        $response->assertRedirect(route('ruangan.show', ['lokasi' => 'Ruang Teknik']));
        $this->assertDatabaseHas('pemeliharaans', [
            'aset_id' => $aset->id,
            'status' => 'menunggu',
            'jenis' => 'Pengecekan rutin',
        ]);
    }
}