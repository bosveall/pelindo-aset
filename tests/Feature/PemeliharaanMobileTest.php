<?php

namespace Tests\Feature;

use App\Models\Aset;
use App\Models\Pemeliharaan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PemeliharaanMobileTest extends TestCase
{
    use RefreshDatabase;

    public function test_scan_page_renders_for_authenticated_user(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('asets.scan'))
            ->assertOk()
            ->assertSee(route('asets.scan.lookup'), false)
            ->assertSee('Masukkan kode manual');
    }

    public function test_scan_lookup_opens_the_matching_asset(): void
    {
        $aset = Aset::create([
            'kode_aset' => 'AST-SCAN-001',
            'nama' => 'Laptop Petugas',
            'lokasi' => 'Ruang TI',
            'kondisi' => 'baik',
        ]);

        $this->actingAs(User::factory()->create())
            ->get(route('asets.scan.lookup', ['kode' => $aset->kode_aset]))
            ->assertRedirect(route('asets.show', $aset));
    }

    public function test_scan_lookup_shows_a_message_for_unknown_asset_code(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('asets.scan.lookup', ['kode' => 'AST-TIDAK-ADA']))
            ->assertRedirect(route('asets.scan'))
            ->assertSessionHas('scan_error');
    }

    public function test_schedule_form_preselects_the_asset_opened_from_a_scan(): void
    {
        $aset = Aset::create([
            'kode_aset' => 'AST-JADWAL-001',
            'nama' => 'AC Ruang Server',
            'lokasi' => 'Ruang Server',
            'kondisi' => 'baik',
        ]);

        $this->actingAs(User::factory()->create())
            ->get(route('pemeliharaans.create', ['aset_id' => $aset->id]))
            ->assertOk()
            ->assertSee($aset->kode_aset)
            ->assertSee('value="' . $aset->id . '" selected', false);
    }

    public function test_mobile_user_can_create_and_update_maintenance_schedule_and_status(): void
    {
        $user = User::factory()->create();
        $aset = Aset::create([
            'kode_aset' => 'AST-JADWAL-002',
            'nama' => 'Printer Ruang Keuangan',
            'lokasi' => 'Ruang Keuangan',
            'kondisi' => 'baik',
        ]);

        $this->actingAs($user)
            ->post(route('pemeliharaans.store'), [
                'aset_id' => $aset->id,
                'jadwal_pemeliharaan' => '2026-10-15',
                'jenis' => 'Pengecekan rutin',
                'status' => 'menunggu',
            ])
            ->assertRedirect(route('pemeliharaans.index'));

        $pemeliharaan = Pemeliharaan::where('aset_id', $aset->id)->firstOrFail();

        $this->put(route('pemeliharaans.update', $pemeliharaan), [
            'aset_id' => $aset->id,
            'jadwal_pemeliharaan' => '2026-10-20 00:00:00',
            'jenis' => 'Pengecekan rutin',
            'status' => 'proses',
        ])->assertRedirect(route('pemeliharaans.index'));

        $this->assertDatabaseHas('pemeliharaans', [
            'id' => $pemeliharaan->id,
            'jadwal_pemeliharaan' => '2026-10-20 00:00:00',
            'status' => 'proses',
        ]);
    }
}