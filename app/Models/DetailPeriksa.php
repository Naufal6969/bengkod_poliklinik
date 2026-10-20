<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPeriksa extends Model
{
    protected $table = 'detail_periksa';

    protected $fillable = [
        'periksa_id',
        'obat_id',
        'dosis',
        'jumlah',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'integer',
            'subtotal' => 'decimal:2',
        ];
    }

    public function periksa(): BelongsTo
    {
        return $this->belongsTo(Periksa::class, 'periksa_id');
    }

    public function obat(): BelongsTo
    {
        return $this->belongsTo(Obat::class, 'obat_id');
    }
}
