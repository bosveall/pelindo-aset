<?php

namespace Tests\Feature;

use App\Models\Aset;
use App\Models\Pemeliharaan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutomaticSuggestionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_asset_form_suggests_saved_values_and_accepts_new_values(): void
    {
        $user = User::factory()->create();

        Aset::create([
            'kode_aset' => 'AST-SUG-001',
            'nama' => 'Laptop Lama',
            'kategori' => 'Elektronik',
            'merk' => 'Lenovo',
            'lokasi' => 'Ruang TI',
            'kondisi' => 'baik',
        ]);

        $this->actingAs($user)
            ->get(route('asets.create'))
            ->assertOk()
            ->assertSee('list="aset-kategori-options"', false)
            ->assertSee('<option value="Elektronik">', false)
            ->assertSee('<option value="Ruang TI">', false)
            ->assertSee('<option value="Lenovo">', false);

        $this->post(route('asets.store'), [
            'kode_aset' => 'AST-SUG-002',
            'nama' => 'Perangkat Baru',
            'kategori' => 'Kategori Baru',
            'merk' => 'Merek Baru',
            'lokasi' => 'Lokasi Baru',
            'kondisi' => 'baik',
        ])->assertRedirect(route('asets.index'));

        $this->assertDatabaseHas('asets', [
            'kode_aset' => 'AST-SUG-002',
            'kategori' => 'Kategori Baru',
            'merk' => 'Merek Baru',
            'lokasi' => 'Lokasi Baru',
        ]);
    }

    public function test_asset_index_can_filter_using_saved_category_and_location(): void
    {
        $user = User::factory()->create();
        Aset::create([
            'kode_aset' => 'AST-FILTER-001',
            'nama' => 'Laptop Cabang',
            'kategori' => 'Elektronik',
            'lokasi' => 'Ruang TI',
            'kondisi' => 'baik',
        ]);
        Aset::create([
            'kode_aset' => 'AST-FILTER-002',
            'nama' => 'Meja Cabang',
            'kategori' => 'Furnitur',
            'lokasi' => 'Ruang Rapat',
            'kondisi' => 'baik',
        ]);

        $this->actingAs($user)
            ->get(route('asets.index', ['kategori' => 'Elektronik', 'lokasi' => 'Ruang TI']))
            ->assertOk()
            ->assertSee('Laptop Cabang')
            ->assertDontSee('AST-FILTER-002');
    }

    public function test_maintenance_forms_suggest_saved_types_and_accept_new_types(): void
    {
        $user = User::factory()->create();
        $aset = Aset::create([
            'kode_aset' => 'AST-MAINT-001',
            'nama' => 'Pompa Air',
            'lokasi' => 'Ruang Teknik',
            'kondisi' => 'baik',
        ]);
        Pemeliharaan::create([
            'aset_id' => $aset->id,
            'jenis' => 'Pengecekan rutin',
            'status' => 'menunggu',
        ]);

        $this->actingAs($user)
            ->get(route('pemeliharaans.create'))
            ->assertOk()
            ->assertSee('list="jenis-pemeliharaan-options"', false)
            ->assertSee('<option value="Pengecekan rutin">', false);

        $this->post(route('pemeliharaans.store'), [
            'aset_id' => $aset->id,
            'jenis' => 'Kalibrasi baru',
            'status' => 'menunggu',
        ])->assertRedirect(route('pemeliharaans.index'));

        $this->assertDatabaseHas('pemeliharaans', [
            'aset_id' => $aset->id,
            'jenis' => 'Kalibrasi baru',
        ]);
    }
}
