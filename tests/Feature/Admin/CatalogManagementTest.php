<?php

namespace Tests\Feature\Admin;

use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Poli;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CatalogManagementTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_only_admins_can_access_catalog_management(): void
    {
        $this->get(route('admin.poli.index'))->assertRedirectToRoute('login');

        $this->actingAs(User::factory()->create(['role' => 'dokter']))
            ->get(route('admin.poli.index'))
            ->assertForbidden();
    }

    public function test_admin_can_create_update_and_delete_poli(): void
    {
        $this->signInAsAdmin();

        $this->get(route('admin.poli.index'))->assertOk()->assertSee('Data Poli');
        $this->get(route('admin.poli.create'))->assertOk()->assertSee('Nama Poli');
        $this->post(route('admin.poli.store'), [
            'nama_poli' => 'Poli Anak',
            'keterangan' => 'Pelayanan kesehatan anak.',
        ])->assertRedirectToRoute('admin.poli.index');

        $poli = Poli::query()->where('nama_poli', 'Poli Anak')->firstOrFail();
        $this->assertModelExists($poli);

        $this->get(route('admin.poli.edit', $poli))->assertOk()->assertSee('Poli Anak');
        $this->put(route('admin.poli.update', $poli), [
            'nama_poli' => 'Poli Anak dan Remaja',
            'keterangan' => 'Pelayanan anak dan remaja.',
        ])->assertRedirectToRoute('admin.poli.index');
        $this->assertDatabaseHas('poli', [
            'id' => $poli->id,
            'nama_poli' => 'Poli Anak dan Remaja',
            'keterangan' => 'Pelayanan anak dan remaja.',
        ]);

        $this->delete(route('admin.poli.destroy', $poli))->assertRedirectToRoute('admin.poli.index');
        $this->assertDatabaseMissing('poli', ['id' => $poli->id]);
    }

    public function test_poli_cannot_be_deleted_while_it_has_doctors(): void
    {
        $this->signInAsAdmin();
        $poli = Poli::create(['nama_poli' => 'Poli Gigi']);
        $user = User::factory()->create(['role' => 'dokter', 'id_poli' => $poli->id]);
        $user->dokter()->create([
            'poli_id' => $poli->id,
            'nama_dokter' => 'Dokter Gigi',
        ]);

        $this->from(route('admin.poli.index'))
            ->delete(route('admin.poli.destroy', $poli))
            ->assertRedirect(route('admin.poli.index'))
            ->assertSessionHas('error', 'Poli tidak dapat dihapus karena masih memiliki dokter.');

        $this->assertModelExists($poli);
    }

    public function test_admin_can_create_update_and_delete_doctor_with_login_account(): void
    {
        $this->signInAsAdmin();
        $poli = Poli::create(['nama_poli' => 'Poli Umum']);

        $this->get(route('admin.dokter.index'))->assertOk()->assertSee('Manajemen Dokter');
        $this->get(route('admin.dokter.create'))->assertOk()->assertSee('Email Login Dokter');
        $this->post(route('admin.dokter.store'), [
            'poli_id' => $poli->id,
            'nama_dokter' => 'Dr. Sari',
            'alamat' => 'Jl. Sehat',
            'no_hp' => '081234567890',
            'email' => 'sari.dokter@example.com',
            'password' => 'password-lama',
            'password_confirmation' => 'password-lama',
        ])->assertRedirectToRoute('admin.dokter.index');

        $dokter = Dokter::query()->where('nama_dokter', 'Dr. Sari')->firstOrFail();
        $user = User::query()->where('email', 'sari.dokter@example.com')->firstOrFail();
        $this->assertSame('dokter', $user->role);
        $this->assertSame($poli->id, $user->id_poli);
        $this->assertSame($user->id, $dokter->user_id);
        $this->assertTrue(Hash::check('password-lama', $user->password));

        $this->get(route('admin.dokter.edit', $dokter))->assertOk()->assertSee('sari.dokter@example.com');
        $this->put(route('admin.dokter.update', $dokter), [
            'poli_id' => $poli->id,
            'nama_dokter' => 'Dr. Sari Putri',
            'alamat' => 'Jl. Sehat No. 2',
            'no_hp' => '081234567891',
            'email' => 'sari.putri@example.com',
        ])->assertRedirectToRoute('admin.dokter.index');

        $this->assertDatabaseHas('dokter', [
            'id' => $dokter->id,
            'nama_dokter' => 'Dr. Sari Putri',
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'sari.putri@example.com',
            'name' => 'Dr. Sari Putri',
            'id_poli' => $poli->id,
        ]);
        $this->assertTrue(Hash::check('password-lama', $user->fresh()->password));

        $this->put(route('admin.dokter.update', $dokter), [
            'poli_id' => $poli->id,
            'nama_dokter' => 'Dr. Sari Putri',
            'alamat' => 'Jl. Sehat No. 2',
            'no_hp' => '081234567891',
            'email' => 'sari.putri@example.com',
            'password' => 'password-baru',
            'password_confirmation' => 'password-baru',
        ])->assertRedirectToRoute('admin.dokter.index');

        $this->assertTrue(Hash::check('password-baru', $user->fresh()->password));

        $this->delete(route('admin.dokter.destroy', $dokter))->assertRedirectToRoute('admin.dokter.index');
        $this->assertDatabaseMissing('dokter', ['id' => $dokter->id]);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_doctor_cannot_be_deleted_while_it_has_a_schedule(): void
    {
        $this->signInAsAdmin();
        $poli = Poli::create(['nama_poli' => 'Poli Saraf']);
        $user = User::factory()->create(['role' => 'dokter']);
        $dokter = $user->dokter()->create([
            'poli_id' => $poli->id,
            'nama_dokter' => 'Dokter Saraf',
        ]);
        $dokter->jadwalPeriksas()->create([
            'hari' => 'Senin',
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00',
            'tarif_periksa' => 50000,
        ]);

        $this->from(route('admin.dokter.index'))
            ->delete(route('admin.dokter.destroy', $dokter))
            ->assertRedirect(route('admin.dokter.index'))
            ->assertSessionHas('error', 'Dokter tidak dapat dihapus karena masih memiliki jadwal periksa.');

        $this->assertModelExists($dokter);
        $this->assertModelExists($user);
    }

    public function test_admin_can_create_update_and_delete_patient_master_data(): void
    {
        $this->signInAsAdmin();

        $this->get(route('admin.pasien.index'))->assertOk()->assertSee('Manajemen Pasien');
        $this->get(route('admin.pasien.create'))->assertOk()->assertSee('Nomor Rekam Medis');
        $this->post(route('admin.pasien.store'), [
            'no_rm' => '202610-123',
            'nama_pasien' => 'Budi Santoso',
            'alamat' => 'Jl. Mawar No. 3',
            'no_hp' => '081234567890',
            'tanggal_lahir' => '1990-05-12',
            'jenis_kelamin' => 'L',
        ])->assertRedirectToRoute('admin.pasien.index');

        $pasien = Pasien::query()->where('no_rm', '202610-123')->firstOrFail();
        $this->assertModelExists($pasien);

        $this->get(route('admin.pasien.edit', $pasien))->assertOk()->assertSee('Budi Santoso');
        $this->put(route('admin.pasien.update', $pasien), [
            'no_rm' => '202610-123',
            'nama_pasien' => 'Budi Santoso Baru',
            'alamat' => 'Jl. Mawar No. 4',
            'no_hp' => '081234567891',
            'tanggal_lahir' => '1990-05-12',
            'jenis_kelamin' => 'L',
        ])->assertRedirectToRoute('admin.pasien.index');

        $this->assertDatabaseHas('pasien', [
            'id' => $pasien->id,
            'nama_pasien' => 'Budi Santoso Baru',
            'alamat' => 'Jl. Mawar No. 4',
        ]);

        $this->delete(route('admin.pasien.destroy', $pasien))->assertRedirectToRoute('admin.pasien.index');
        $this->assertDatabaseMissing('pasien', ['id' => $pasien->id]);
    }

    public function test_patient_cannot_be_deleted_while_it_has_a_registration(): void
    {
        $this->signInAsAdmin();
        $poli = Poli::create(['nama_poli' => 'Poli Mata']);
        $dokter = Dokter::create([
            'poli_id' => $poli->id,
            'nama_dokter' => 'Dokter Mata',
        ]);
        $jadwal = $dokter->jadwalPeriksas()->create([
            'hari' => 'Selasa',
            'jam_mulai' => '09:00',
            'jam_selesai' => '12:00',
            'tarif_periksa' => 50000,
        ]);
        $pasien = Pasien::create([
            'no_rm' => '202610-456',
            'nama_pasien' => 'Siti Aminah',
        ]);
        $pasien->daftarPolis()->create([
            'jadwal_periksa_id' => $jadwal->id,
            'tanggal_daftar' => '2026-10-14',
            'no_antrian' => 1,
            'keluhan' => 'Mata terasa lelah.',
        ]);

        $this->from(route('admin.pasien.index'))
            ->delete(route('admin.pasien.destroy', $pasien))
            ->assertRedirect(route('admin.pasien.index'))
            ->assertSessionHas('error', 'Pasien tidak dapat dihapus karena masih memiliki pendaftaran poli.');

        $this->assertModelExists($pasien);
        $this->assertDatabaseCount('daftar_poli', 1);
    }

    public function test_catalog_forms_reject_invalid_relationships_and_duplicate_medical_record_numbers(): void
    {
        $this->signInAsAdmin();

        $this->from(route('admin.dokter.create'))->post(route('admin.dokter.store'), [
            'poli_id' => 999,
            'nama_dokter' => 'Dokter Baru',
            'email' => 'dokter.baru@example.com',
            'password' => 'password-aman',
            'password_confirmation' => 'password-aman',
        ])->assertRedirect(route('admin.dokter.create'))
            ->assertSessionHasErrors('poli_id');
        $this->assertDatabaseCount('dokter', 0);

        Pasien::create([
            'no_rm' => '202610-789',
            'nama_pasien' => 'Pasien Pertama',
        ]);

        $this->from(route('admin.pasien.create'))->post(route('admin.pasien.store'), [
            'no_rm' => '202610-789',
            'nama_pasien' => 'Pasien Duplikat',
            'tanggal_lahir' => '2099-01-01',
            'jenis_kelamin' => 'X',
        ])->assertRedirect(route('admin.pasien.create'))
            ->assertSessionHasErrors(['no_rm', 'tanggal_lahir', 'jenis_kelamin']);
        $this->assertDatabaseCount('pasien', 1);
    }

    private function signInAsAdmin(): User
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        return $admin;
    }
}
