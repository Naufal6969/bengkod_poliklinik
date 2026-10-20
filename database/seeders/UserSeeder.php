<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => 'admin',
                'role' => 'admin',
            ],
            [
                'name' => 'Dokter',
                'email' => 'dokter@gmail.com',
                'password' => 'dokter',
                'role' => 'dokter',
            ],
            [
                'name' => 'Pasien',
                'email' => 'pasien@gmail.com',
                'password' => 'pasien',
                'role' => 'pasien',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user,
            );
        }
    }
}
