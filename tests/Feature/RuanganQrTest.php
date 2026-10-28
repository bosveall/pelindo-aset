<?php

namespace Tests\Feature;

use App\Models\Aset;
use App\Models\Pemeliharaan;
use App\Models\Ruangan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
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

    public function test_authenticated_user_can_quick_edit_asset_details_from_room_qr_page(): void
    {
        $aset = Aset::create([
            'kode_aset' => 'AST-RUANG-QUICK-EDIT',
            'nama' => 'Monitor Ruang TI',
            'kategori' => 'Elektronik',
            'merk' => 'Dell',
            'lokasi' => 'Ruang TI',
            'kondisi' => 'baik',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('ruangan.show', ['lokasi' => 'Ruang TI']));

        $response->assertOk();
        $response->assertSee('Kode aset');
        $response->assertSee('Nama aset');
        $response->assertSee('Kondisi');
        $response->assertSee('Simpan');
        $response->assertSee('Form lengkap');
        $response->assertSee('name="kode_aset"', false);
        $response->assertSee('name="nama"', false);
        $response->assertSee('name="merk"', false);
        $response->assertSee('name="deskripsi"', false);
    }

    public function test_admin_can_create_and_rename_room_and_update_related_assets(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('ruangan.store'), [
                '_token' => 'test-token',
                'nama' => 'Ruang Sistem',
                'deskripsi' => 'Ruang sistem baru',
            ])
            ->assertRedirect(route('ruangan.index'))
            ->assertSessionHas('success');

        $ruangan = Ruangan::where('nama', 'Ruang Sistem')->firstOrFail();

        $aset = Aset::create([
            'kode_aset' => 'AST-RUANG-RENAME',
            'nama' => 'Server Utama',
            'lokasi' => 'Ruang Sistem',
            'kondisi' => 'baik',
            'status' => 'aktif',
        ]);

        $this->actingAs($admin)
            ->post(route('ruangan.update', ['ruangan' => $ruangan->id]), [
                '_token' => 'test-token',
                'nama' => 'Ruang Infrastruktur',
                'deskripsi' => 'Ruang infrastrukturnya',
            ])
            ->assertSessionHas('success');

        $this->assertDatabaseHas('ruangans', ['nama' => 'Ruang Infrastruktur', 'slug' => 'ruang-infrastruktur']);
        $this->assertDatabaseHas('asets', ['id' => $aset->id, 'lokasi' => 'Ruang Infrastruktur']);
        $this->get(route('ruangan.show', ['lokasi' => 'Ruang Infrastruktur']))->assertOk();
    }

    public function test_room_page_bootstraps_missing_ruangan_table(): void
    {
        Schema::dropIfExists('ruangans');

        $response = $this->get(route('ruangan.show', ['lokasi' => 'Ruang Server']));

        $response->assertOk();
        $this->assertTrue(Schema::hasTable('ruangans'));
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