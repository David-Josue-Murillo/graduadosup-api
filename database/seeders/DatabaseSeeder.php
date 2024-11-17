<?php

namespace Database\Seeders;

use App\Models\Campu;
use App\Models\Career;
use App\Models\Faculty;
use App\Models\NumGraduate;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'David Murillo',
            'email' => 'dm514821@gmail.com',
            'password'=> Hash::make('password'),
            'role' => 'admin'
        ]);
        
        Campu::factory(3)->create();
        Faculty::factory(6)->create();
        Career::factory(18)->create();
        NumGraduate::factory(36)->create();
        User::factory(2)->create();
    }
}
