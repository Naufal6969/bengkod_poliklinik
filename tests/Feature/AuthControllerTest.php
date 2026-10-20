<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_patient_registration_creates_account_with_next_monthly_medical_record_number(): void
    {
        $this->travelTo(now()->setDate(2026, 10, 14)->setTime(9, 0));
        User::factory()->create([
            'name' => 'Pasien Lama',
            'email' => 'lama@example.com',
            'role' => 'pasien',
            'no_rm' => '202610-001',
        ]);

        $response = $this->post(route('register.store'), [
            'nama' => 'Pasien Baru',
            'alamat' => 'Jl. Sehat No. 10',
            'no_ktp' => '3201010101010002',
            'no_hp' => '081234567890',
            'email' => 'baru@example.com',
            'password' => 'password-aman',
            'password_confirmation' => 'password-aman',
        ]);

        $response->assertRedirectToRoute('login');
        $response->assertSessionHas('success', 'Registrasi berhasil! Silakan login.');
        $this->assertDatabaseHas('users', [
            'name' => 'Pasien Baru',
            'alamat' => 'Jl. Sehat No. 10',
            'no_ktp' => '3201010101010002',
            'no_hp' => '081234567890',
            'no_rm' => '202610-002',
            'role' => 'pasien',
            'email' => 'baru@example.com',
        ]);
        $this->assertTrue(Hash::check('password-aman', User::where('email', 'baru@example.com')->value('password')));
    }

    public function test_registration_rejects_a_duplicate_identity_number(): void
    {
        User::factory()->create([
            'no_ktp' => '3201010101010001',
        ]);

        $response = $this->from(route('register'))->post(route('register.store'), [
            'nama' => 'Pasien Baru',
            'alamat' => 'Jl. Sehat No. 10',
            'no_ktp' => '3201010101010001',
            'no_hp' => '081234567890',
            'email' => 'baru@example.com',
            'password' => 'password-aman',
            'password_confirmation' => 'password-aman',
        ]);

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrors('no_ktp');
        $this->assertDatabaseCount('users', 1);
    }

    public function test_medical_record_numbers_continue_numerically_after_one_thousand(): void
    {
        $this->travelTo(now()->setDate(2026, 10, 14)->setTime(9, 0));
        User::factory()->create([
            'email' => 'pasien-999@example.com',
            'role' => 'pasien',
            'no_rm' => '202610-999',
        ]);
        User::factory()->create([
            'email' => 'pasien-1000@example.com',
            'role' => 'pasien',
            'no_rm' => '202610-1000',
        ]);

        $this->post(route('register.store'), [
            'nama' => 'Pasien Berikutnya',
            'alamat' => 'Jl. Sehat No. 10',
            'no_ktp' => '3201010101010003',
            'no_hp' => '081234567890',
            'email' => 'berikutnya@example.com',
            'password' => 'password-aman',
            'password_confirmation' => 'password-aman',
        ])->assertRedirectToRoute('login');

        $this->assertDatabaseHas('users', [
            'email' => 'berikutnya@example.com',
            'no_rm' => '202610-1001',
        ]);
    }

    public function test_registration_requires_all_patient_fields(): void
    {
        $response = $this->from(route('register'))->post(route('register.store'), []);

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrors(['nama', 'alamat', 'no_ktp', 'no_hp', 'email', 'password']);
        $this->assertDatabaseCount('users', 0);
    }

    public function test_admin_login_redirects_to_the_admin_dashboard(): void
    {
        User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'correct-password',
            'role' => 'admin',
        ]);

        $response = $this->post(route('login.store'), [
            'email' => 'admin@example.com',
            'password' => 'correct-password',
        ]);

        $response->assertRedirectToRoute('admin.dashboard');
        $this->assertAuthenticated();
        $this->get(route('admin.dashboard'))->assertOk()->assertSee('Halo Selamat Datang Admin');
    }

    public function test_doctor_login_redirects_to_the_doctor_dashboard(): void
    {
        User::factory()->create([
            'email' => 'dokter@example.com',
            'password' => 'correct-password',
            'role' => 'dokter',
        ]);

        $response = $this->post(route('login.store'), [
            'email' => 'dokter@example.com',
            'password' => 'correct-password',
        ]);

        $response->assertRedirectToRoute('dokter.dashboard');
        $this->get(route('dokter.dashboard'))->assertOk()->assertSee('Halo Selamat Datang Dokter');
    }

    public function test_patient_login_redirects_to_the_patient_dashboard(): void
    {
        User::factory()->create([
            'email' => 'pasien@example.com',
            'password' => 'correct-password',
            'role' => 'pasien',
        ]);

        $response = $this->post(route('login.store'), [
            'email' => 'pasien@example.com',
            'password' => 'correct-password',
        ]);

        $response->assertRedirectToRoute('pasien.dashboard');
        $this->get(route('pasien.dashboard'))->assertOk()->assertSee('Halo Selamat Datang Pasien');
    }

    public function test_login_rejects_invalid_credentials(): void
    {
        $response = $this->from(route('login'))->post(route('login.store'), [
            'email' => 'missing@example.com',
            'password' => 'incorrect-password',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_authenticated_user_cannot_open_another_role_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('dokter.dashboard'))
            ->assertForbidden();
    }

    public function test_guest_is_redirected_to_login_from_dashboard(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirectToRoute('login');
    }

    public function test_logout_ends_the_authenticated_session(): void
    {
        $user = User::factory()->create(['role' => 'pasien']);

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirectToRoute('login');

        $this->assertGuest();
    }
}
