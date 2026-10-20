<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Obat extends Model
{
    protected $table = 'obat';

    protected $fillable = [
        'nama_obat',
        'kemasan',
        'stok',
        'harga',
    ];

    protected function casts(): array
    {
        return [
            'stok' => 'integer',
            'harga' => 'decimal:2',
        ];
    }

    public function detailPeriksas(): HasMany
    {
        return $this->hasMany(DetailPeriksa::class, 'obat_id');
    }
}
