<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'alamat', 'no_ktp', 'no_hp', 'no_rm', 'id_poli'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function poli(): BelongsTo
    {
        return $this->belongsTo(Poli::class, 'id_poli');
    }

    public function dokter(): HasOne
    {
        return $this->hasOne(Dokter::class, 'user_id');
    }

    public function jadwalPeriksas(): HasManyThrough
    {
        return $this->hasManyThrough(
            JadwalPeriksa::class,
            Dokter::class,
            'user_id',
            'dokter_id',
            'id',
            'id',
        );
    }
}
