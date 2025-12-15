<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Panggil KelasSeeder untuk mengisi data kelas (10, 11, 12)
        $this->call(KelasSeeder::class);

        // 2. Buat akun pengguna contoh (Test User)
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
