<?php

namespace Tests\Feature\Admin;

use App\Models\DaftarPoli;
use App\Models\DetailPeriksa;
use App\Models\Dokter;
use App\Models\JadwalPeriksa;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Periksa;
use App\Models\Poli;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class MedicineAndScheduleManagementTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_only_admins_can_access_medicine_and_schedule_management(): void
    {
        $this->get(route('admin.obat.index'))->assertRedirectToRoute('login');
        $this->get(route('admin.jadwal-periksa.index'))->assertRedirectToRoute('login');

        $this->actingAs(User::factory()->create(['role' => 'dokter']))
            ->get(route('admin.obat.index'))
            ->assertForbidden();
        $this->get(route('admin.jadwal-periksa.index'))->assertForbidden();
    }

    public function test_admin_can_create_update_and_delete_medicine(): void
    {
        $this->signInAsAdmin();

        $this->get(route('admin.obat.index'))->assertOk()->assertSee('Data Obat');
        $this->get(route('admin.obat.create'))->assertOk()->assertSee('Nama Obat');
        $this->post(route('admin.obat.store'), [
            'nama_obat' => 'Paracetamol 500 mg',
            'kemasan' => 'Tablet',
            'stok' => 100,
            'harga' => 2500,
        ])->assertRedirectToRoute('admin.obat.index')
            ->assertSessionHas('success', 'Data obat berhasil ditambahkan.');

        $obat = Obat::query()->where('nama_obat', 'Paracetamol 500 mg')->firstOrFail();
        $this->assertModelExists($obat);

        $this->get(route('admin.obat.edit', $obat))->assertOk()->assertSee('Paracetamol 500 mg');
        $this->put(route('admin.obat.update', $obat), [
            'nama_obat' => 'Paracetamol 500 mg',
            'kemasan' => 'Strip',
            'stok' => 80,
            'harga' => 3000,
        ])->assertRedirectToRoute('admin.obat.index')
            ->assertSessionHas('success', 'Data obat berhasil diperbarui.');
        $this->assertDatabaseHas('obat', [
            'id' => $obat->id,
            'kemasan' => 'Strip',
            'stok' => 80,
            'harga' => 3000,
        ]);

        $this->delete(route('admin.obat.destroy', $obat))
            ->assertRedirectToRoute('admin.obat.index')
            ->assertSessionHas('success', 'Data obat berhasil dihapus.');
        $this->assertDatabaseMissing('obat', ['id' => $obat->id]);
    }

    public function test_medicine_validation_rejects_duplicate_names_and_negative_values(): void
    {
        $this->signInAsAdmin();
        Obat::create([
            'nama_obat' => 'Amoxicillin',
            'kemasan' => 'Kapsul',
            'stok' => 10,
            'harga' => 5000,
        ]);

        $this->from(route('admin.obat.create'))->post(route('admin.obat.store'), [
            'nama_obat' => 'Amoxicillin',
            'kemasan' => 'Kapsul',
            'stok' => -1,
            'harga' => -50,
        ])->assertRedirect(route('admin.obat.create'))
            ->assertSessionHasErrors(['nama_obat', 'stok', 'harga']);

        $this->assertDatabaseCount('obat', 1);
    }

    public function test_medicine_cannot_be_deleted_after_being_used_in_an_examination(): void
    {
        $this->signInAsAdmin();
        $obat = Obat::create([
            'nama_obat' => 'Vitamin C',
            'stok' => 10,
            'harga' => 1000,
        ]);
        $periksa = $this->createExamination();
        DetailPeriksa::create([
            'periksa_id' => $periksa->id,
            'obat_id' => $obat->id,
            'dosis' => '1 tablet',
            'jumlah' => 1,
            'subtotal' => 1000,
        ]);

        $this->from(route('admin.obat.index'))
            ->delete(route('admin.obat.destroy', $obat))
            ->assertRedirect(route('admin.obat.index'))
            ->assertSessionHas('error', 'Obat tidak dapat dihapus karena sudah digunakan dalam detail pemeriksaan.');

        $this->assertModelExists($obat);
    }

    public function test_admin_can_create_update_and_delete_doctor_schedule(): void
    {
        $this->signInAsAdmin();
        $dokter = $this->createDoctor();

        $this->get(route('admin.jadwal-periksa.index'))->assertOk()->assertSee('Jadwal Periksa');
        $this->get(route('admin.jadwal-periksa.create'))->assertOk()->assertSee('Jam Mulai')->assertSee('dr. Nadia Putri');
        $this->post(route('admin.jadwal-periksa.store'), [
            'dokter_id' => $dokter->id,
            'hari' => 'Senin',
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00',
            'tarif_periksa' => 75000,
        ])->assertRedirectToRoute('admin.jadwal-periksa.index')
            ->assertSessionHas('success', 'Jadwal periksa berhasil ditambahkan.');

        $jadwal = JadwalPeriksa::query()->where('dokter_id', $dokter->id)->firstOrFail();
        $this->assertModelExists($jadwal);

        $this->get(route('admin.jadwal-periksa.edit', $jadwal))->assertOk()->assertSee('Senin');
        $this->put(route('admin.jadwal-periksa.update', $jadwal), [
            'dokter_id' => $dokter->id,
            'hari' => 'Selasa',
            'jam_mulai' => '09:00',
            'jam_selesai' => '13:00',
            'tarif_periksa' => 90000,
        ])->assertRedirectToRoute('admin.jadwal-periksa.index')
            ->assertSessionHas('success', 'Jadwal periksa berhasil diperbarui.');
        $this->assertDatabaseHas('jadwal_periksa', [
            'id' => $jadwal->id,
            'hari' => 'Selasa',
            'jam_mulai' => '09:00',
            'jam_selesai' => '13:00',
            'tarif_periksa' => 90000,
        ]);

        $this->delete(route('admin.jadwal-periksa.destroy', $jadwal))
            ->assertRedirectToRoute('admin.jadwal-periksa.index')
            ->assertSessionHas('success', 'Jadwal periksa berhasil dihapus.');
        $this->assertDatabaseMissing('jadwal_periksa', ['id' => $jadwal->id]);
    }

    public function test_schedule_validation_rejects_invalid_doctors_times_and_duplicate_start_times(): void
    {
        $this->signInAsAdmin();
        $dokter = $this->createDoctor();
        JadwalPeriksa::create([
            'dokter_id' => $dokter->id,
            'hari' => 'Rabu',
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00',
            'tarif_periksa' => 75000,
        ]);

        $this->from(route('admin.jadwal-periksa.create'))->post(route('admin.jadwal-periksa.store'), [
            'dokter_id' => 999,
            'hari' => 'Rabu',
            'jam_mulai' => '08:00',
            'jam_selesai' => '07:00',
            'tarif_periksa' => -1,
        ])->assertRedirect(route('admin.jadwal-periksa.create'))
            ->assertSessionHasErrors(['dokter_id', 'jam_selesai', 'tarif_periksa']);

        $this->from(route('admin.jadwal-periksa.create'))->post(route('admin.jadwal-periksa.store'), [
            'dokter_id' => $dokter->id,
            'hari' => 'Rabu',
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00',
            'tarif_periksa' => 75000,
        ])->assertRedirect(route('admin.jadwal-periksa.create'))
            ->assertSessionHasErrors('jam_mulai');

        $this->assertDatabaseCount('jadwal_periksa', 1);
    }

    public function test_schedule_validation_rejects_overlapping_times_for_the_same_doctor(): void
    {
        $this->signInAsAdmin();
        $dokter = $this->createDoctor();
        JadwalPeriksa::create([
            'dokter_id' => $dokter->id,
            'hari' => 'Senin',
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00',
            'tarif_periksa' => 75000,
        ]);

        $this->from(route('admin.jadwal-periksa.create'))->post(route('admin.jadwal-periksa.store'), [
            'dokter_id' => $dokter->id,
            'hari' => 'Senin',
            'jam_mulai' => '11:00',
            'jam_selesai' => '13:00',
            'tarif_periksa' => 75000,
        ])->assertRedirect(route('admin.jadwal-periksa.create'))
            ->assertSessionHasErrors([
                'jam_mulai' => 'Jadwal dokter tidak boleh bertumpang tindih pada hari yang sama.',
            ]);

        $this->assertDatabaseCount('jadwal_periksa', 1);
    }

    public function test_schedule_cannot_be_deleted_after_patient_registration(): void
    {
        $this->signInAsAdmin();
        $dokter = $this->createDoctor();
        $jadwal = JadwalPeriksa::create([
            'dokter_id' => $dokter->id,
            'hari' => 'Kamis',
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00',
            'tarif_periksa' => 75000,
        ]);
        $pasien = Pasien::create([
            'no_rm' => '202610-001',
            'nama_pasien' => 'Pasien Jadwal',
        ]);
        DaftarPoli::create([
            'pasien_id' => $pasien->id,
            'jadwal_periksa_id' => $jadwal->id,
            'tanggal_daftar' => '2026-10-15',
            'no_antrian' => 1,
            'keluhan' => 'Pemeriksaan rutin.',
        ]);

        $this->from(route('admin.jadwal-periksa.index'))
            ->delete(route('admin.jadwal-periksa.destroy', $jadwal))
            ->assertRedirect(route('admin.jadwal-periksa.index'))
            ->assertSessionHas('error', 'Jadwal tidak dapat dihapus karena sudah digunakan dalam pendaftaran pasien.');

        $this->assertModelExists($jadwal);
    }

    private function signInAsAdmin(): User
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        return $admin;
    }

    private function createDoctor(): Dokter
    {
        $poli = Poli::create(['nama_poli' => 'Poli Umum']);

        return Dokter::create([
            'poli_id' => $poli->id,
            'nama_dokter' => 'dr. Nadia Putri',
        ]);
    }

    private function createExamination(): Periksa
    {
        $dokter = $this->createDoctor();
        $jadwal = JadwalPeriksa::create([
            'dokter_id' => $dokter->id,
            'hari' => 'Jumat',
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00',
            'tarif_periksa' => 75000,
        ]);
        $pasien = Pasien::create([
            'no_rm' => '202610-002',
            'nama_pasien' => 'Pasien Pemeriksaan',
        ]);
        $daftarPoli = DaftarPoli::create([
            'pasien_id' => $pasien->id,
            'jadwal_periksa_id' => $jadwal->id,
            'tanggal_daftar' => '2026-10-16',
            'no_antrian' => 1,
            'keluhan' => 'Batuk.',
        ]);

        return Periksa::create([
            'daftar_poli_id' => $daftarPoli->id,
            'biaya_periksa' => 75000,
        ]);
    }
}
