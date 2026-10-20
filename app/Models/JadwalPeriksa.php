<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JadwalPeriksa extends Model
{
    protected $table = 'jadwal_periksa';

    protected $fillable = [
        'dokter_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'tarif_periksa',
    ];

    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class, 'dokter_id');
    }

    public function daftarPolis(): HasMany
    {
        return $this->hasMany(DaftarPoli::class, 'jadwal_periksa_id');
    }
}
