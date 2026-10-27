<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Aset extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_aset',
        'nama',
        'kategori',
        'merk',
        'lokasi',
        'kondisi',
        'tanggal_pengadaan',
        'deskripsi',
        'gambar',
    ];

    protected $casts = [
        'tanggal_pengadaan' => 'date',
    ];

    public function getImageUrlAttribute(): string
    {
        if ($this->gambar && Storage::disk('public')->exists($this->gambar)) {
            return asset('storage/' . $this->gambar);
        }

        $imageMap = [
            'proyektor' => 'proyektor.webp',
            'printer' => 'printer epson l360.webp',
            'meja' => 'meja kerja.webp',
            'laptop' => 'laptop kantor kerja.jpg',
            'komputer' => 'gambar pc.jpg',
            'cctv' => 'cctv.jpg',
            'ac' => 'ac (4).webp',
        ];

        $name = mb_strtolower($this->nama ?? '');
        $image = 'placeholder.svg';

        foreach ($imageMap as $keyword => $filename) {
            if (str_contains($name, $keyword)) {
                $image = $filename;
                break;
            }
        }

        return asset('assets/images/' . $image);
    }

    public function pemeliharaans(): HasMany
    {
        return $this->hasMany(Pemeliharaan::class);
    }
}
