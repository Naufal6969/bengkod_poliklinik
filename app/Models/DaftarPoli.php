<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DaftarPoli extends Model
{
    protected $table = 'daftar_poli';

    protected $fillable = [
        'pasien_id',
        'jadwal_periksa_id',
        'tanggal_daftar',
        'no_antrian',
        'keluhan',
        'status',
    ];

    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }

    public function jadwalPeriksa(): BelongsTo
    {
        return $this->belongsTo(JadwalPeriksa::class, 'jadwal_periksa_id');
    }

    public function periksa(): HasOne
    {
        return $this->hasOne(Periksa::class, 'daftar_poli_id');
    }
}
