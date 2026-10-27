<?php

namespace Tests\Feature;

use App\Models\Aset;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AsetImageTest extends TestCase
{
    use RefreshDatabase;

    public function test_asset_index_displays_its_image_when_present(): void
    {
        Storage::fake('public');

        $pngData = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAF' .
            'c0x8AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJ0UkG' .
            'AAAAAAgIY6NtAAAAAElFTkSuQmCC');

        $path = 'aset/asset.png';
        Storage::disk('public')->put($path, $pngData);

        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $aset = Aset::create([
            'kode_aset' => 'AST-IMG-001',
            'nama' => 'Laptop Baru',
            'kategori' => 'Elektronik',
            'lokasi' => 'Kantor',
            'kondisi' => 'baik',
            'status' => 'aktif',
            'gambar' => $path,
        ]);

        $response = $this->actingAs($user)->get(route('asets.index'));

        $response->assertOk();
        $response->assertSee('img', false);
        $response->assertSee(asset('storage/' . $path), false);
        $this->assertSame($path, $aset->gambar);
    }

    public function test_uploaded_public_image_can_be_served_from_the_storage_route(): void
    {
        Storage::fake('public');

        $image = 'test image';
        Storage::disk('public')->put('aset/asset.png', $image);

        $response = $this->get('/storage/aset/asset.png');

        $response->assertOk();
        $this->assertSame($image, $response->streamedContent());
    }
}
