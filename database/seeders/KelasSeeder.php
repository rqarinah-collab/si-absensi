<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = [
            ['nama' => 'Kelas 10'],
            ['nama' => 'Kelas 11'],
            ['nama' => 'Kelas 12'],
        ];

        foreach ($classes as $class) {
            Kelas::firstOrCreate($class);
        }
    }
}
