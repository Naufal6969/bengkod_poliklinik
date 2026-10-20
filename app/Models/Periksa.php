<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Periksa extends Model
{
    protected $table = 'periksa';

    protected $fillable = [
        'daftar_poli_id',
        'tanggal_periksa',
        'catatan',
        'biaya_periksa',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_periksa' => 'datetime',
            'biaya_periksa' => 'decimal:2',
        ];
    }

    public function daftarPoli(): BelongsTo
    {
        return $this->belongsTo(DaftarPoli::class, 'daftar_poli_id');
    }

    public function detailPeriksas(): HasMany
    {
        return $this->hasMany(DetailPeriksa::class, 'periksa_id');
    }
}
