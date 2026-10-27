<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pemeliharaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'aset_id',
        'tanggal',
        'jadwal_pemeliharaan',
        'jenis',
        'keterangan',
        'petugas_id',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'jadwal_pemeliharaan' => 'date',
    ];

    public function aset(): BelongsTo
    {
        return $this->belongsTo(Aset::class);
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
