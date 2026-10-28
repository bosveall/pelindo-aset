<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ruangan extends Model
{
    use HasFactory;

    protected $table = 'ruangans';

    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $ruangan): void {
            $ruangan->slug = Str::slug($ruangan->slug ?: $ruangan->nama ?? '');
        });
    }
}
