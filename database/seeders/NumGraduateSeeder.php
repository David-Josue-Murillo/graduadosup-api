<?php

namespace Database\Seeders;

use App\Models\Campu;
use App\Models\Career;
use App\Models\Faculty;
use App\Models\NumGraduate;
use App\Models\User;
use Illuminate\Database\Seeder;

class NumGraduateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CampuSeeder::class,
            FacultySeeder::class,
            CareerSeeder::class,
        ]);

        NumGraduate::factory()->create([
            'id' => 1,
            'quantity' => '100',
            'year' => '2021',
            'campus_id' => 1,
            'career_id' => 1
        ]);

        NumGraduate::factory()->create([
            'id' => 2,
            'quantity' => '25',
            'year' => '2022',
            'campus_id' => 2,
            'career_id' => 2
        ]);

        NumGraduate::factory()->create([
            'id' => 3,
            'quantity' => '70',
            'year' => '2023',
            'campus_id' => 2,
            'career_id' => 3
        ]);

        NumGraduate::factory()->create([
            'id' => 4,
            'quantity' => '15',
            'year' => '2018',
            'campus_id' => 1,
            'career_id' => 4
        ]);

        NumGraduate::factory()->create([
            'id' => 5,
            'quantity' => '140',
            'year' => '2019',
            'campus_id' => 1,
            'career_id' => 5
        ]);
    }
}
