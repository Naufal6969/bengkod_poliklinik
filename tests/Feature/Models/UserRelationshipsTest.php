<?php

namespace Tests\Feature\Models;

use App\Models\Dokter;
use App\Models\JadwalPeriksa;
use App\Models\Poli;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class UserRelationshipsTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_doctor_account_retrieves_its_poli_and_schedules(): void
    {
        $poli = Poli::create([
            'nama_poli' => 'Poli Umum',
            'keterangan' => 'Pelayanan umum',
        ]);
        $user = User::factory()->create([
            'role' => 'dokter',
            'id_poli' => $poli->id,
        ]);
        $dokter = Dokter::create([
            'user_id' => $user->id,
            'poli_id' => $poli->id,
            'nama_dokter' => 'Dokter Contoh',
        ]);
        $jadwal = JadwalPeriksa::create([
            'dokter_id' => $dokter->id,
            'hari' => 'Senin',
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00',
            'tarif_periksa' => 100000,
        ]);

        $user = $user->fresh();

        $this->assertSame($poli->id, $user->poli->id);
        $this->assertSame([$jadwal->id], $user->jadwalPeriksas->modelKeys());
    }
}
