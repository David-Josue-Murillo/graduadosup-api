<?php

namespace Database\Seeders;

use App\Models\Faculty;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Faculty::factory()->create([
            'id' => 1,
            'name' => 'Facultad de Informática, Electrónica y Comunicación',
        ]);

        Faculty::factory()->create([
            'id' => 2,
            'name' => 'Facultad de Administración de Empresas',
        ]);

        Faculty::factory()->create([
            'id' => 3,
            'name' => 'Facultad de Ciencias Naturales, Exactas y Tecnología',
        ]);
    }
}
