<?php

namespace Database\Seeders;

use App\Models\Career;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CareerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Career::factory()->create([
            'id' => 1,
            'name' => 'Ingeniería en Informática',
            'faculty_id' => 1,
        ]);

        Career::factory()->create([
            'id' => 2,
            'name' => 'Licenciatura en Informática para la Gestión Educativa y Empresarial',
            'faculty_id' => 1,
        ]);

        Career::factory()->create([
            'id' => 3,
            'name' => 'Licenciatura en Bancas y Finanzas',
            'faculty_id' => 2,
        ]);

        Career::factory()->create([
            'id' => 4,
            'name' => 'Licenciatura en Contabilidad',
            'faculty_id' => 2,
        ]);

        Career::factory()->create([
            'id' => 5,
            'name' => 'Licenciatura en Biología',
            'faculty_id' => 3,
        ]);
    }
}
