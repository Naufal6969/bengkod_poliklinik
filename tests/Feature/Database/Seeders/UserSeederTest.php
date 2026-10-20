<?php

namespace Tests\Feature\Database\Seeders;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserSeederTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_creates_the_three_role_accounts_and_can_be_rerun(): void
    {
        $this->seed();
        $this->seed();

        $this->assertDatabaseCount('users', 3);
        $this->assertDatabaseHas('users', [
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
        ]);
        $this->assertDatabaseHas('users', [
            'name' => 'Dokter',
            'email' => 'dokter@gmail.com',
            'role' => 'dokter',
        ]);
        $this->assertDatabaseHas('users', [
            'name' => 'Pasien',
            'email' => 'pasien@gmail.com',
            'role' => 'pasien',
        ]);
        $this->assertTrue(Hash::check('admin', User::where('email', 'admin@gmail.com')->value('password')));
        $this->assertTrue(Hash::check('dokter', User::where('email', 'dokter@gmail.com')->value('password')));
        $this->assertTrue(Hash::check('pasien', User::where('email', 'pasien@gmail.com')->value('password')));
    }
}
