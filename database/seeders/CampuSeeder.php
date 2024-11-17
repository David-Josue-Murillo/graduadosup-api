<?php

namespace Database\Seeders;

use App\Models\Campu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CampuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Campu::factory()->create([
            'id' => 1,
            'name' => 'Centro regional universitario de Veraguas',
        ]);

        Campu::factory()->create([
            'id' => 2,
            'name' => 'Centro regional universitario de Panamá',
        ]);
    }
}
