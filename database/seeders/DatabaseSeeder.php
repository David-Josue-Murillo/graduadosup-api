<?php

namespace Database\Seeders;

use App\Models\Campu;
use App\Models\Career;
use App\Models\Faculty;
use App\Models\NumGraduate;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Campu::factory(10)->create();
        Faculty::factory(19)->create();
        Career::factory(355)->create();
        NumGraduate::factory(10000)->create();
        // User::factory(10)->create();

        /*User::factory()->create([
            'name' => 'David Murillo',
            'email' => 'dm514821@gmail.com',
        ]);*/
    }
}
