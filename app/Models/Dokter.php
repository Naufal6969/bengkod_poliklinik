<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dokter extends Model
{
    protected $table = 'dokter';

    protected $fillable = [
        'user_id',
        'poli_id',
        'nama_dokter',
        'alamat',
        'no_hp',
    ];

    public function poli(): BelongsTo
    {
        return $this->belongsTo(Poli::class, 'poli_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jadwalPeriksas(): HasMany
    {
        return $this->hasMany(JadwalPeriksa::class, 'dokter_id');
    }
}
