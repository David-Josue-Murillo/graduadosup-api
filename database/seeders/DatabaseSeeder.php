<?php

namespace Database\Seeders;

use App\Models\Campu;
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
        Campu::factory(25)->create();
        // User::factory(10)->create();

        /*User::factory()->create([
            'name' => 'David Murillo',
            'email' => 'dm514821@gmail.com',
        ]);*/
    }
}
