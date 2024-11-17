<?php

namespace Tests\Feature;

use App\Models\NumGraduate;
use App\Models\User;
use Database\Seeders\CampuSeeder;
use Database\Seeders\CareerSeeder;
use Database\Seeders\FacultySeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NumGraduateTest extends TestCase
{
    use RefreshDatabase;
    protected $jsonResponseEstructure;

    protected function setUp(): void
    {
        parent::setUp();
        $this->jsonResponseEstructure = [
            'message',
            'data' => [
                'id',
                'quantity',
                'year',
                'campus' => [
                    'id',
                    'name'
                ],
                'career' => [
                    'id',
                    'name',
                    'faculty' => [
                        'id',
                        'name'
                    ]
                ]
            ],
            'status'
        ];
        $this->seed([
            UserSeeder::class,
            FacultySeeder::class,
            CampuSeeder::class,
            CareerSeeder::class
        ]);
    }

    /** @test */
    public function regiter_a_new_number_of_graduates(): void
    {
        $data = [
            'quantity' => 100,
            'year' => 2024,
            'campus_id' => 1,
            'career_id' => 1
        ];

        $response = $this->apiAs(User::find(1), 'post', '/graduates', $data);
        
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'data',
            'status',
            'errors'
        ]);
    }

    /** @test */
    public function it_cannot_be_regiter_a_number_of_graduates_duplicated(): void
    {
        $this->regiter_a_new_number_of_graduates();
        $data = [
            'quantity' => 100,
            'year' => 2024,
            'campus_id' => 1,
            'career_id' => 1
        ];

        $response = $this->apiAs(User::find(1), 'post', '/api/graduates', $data);
        
        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'Registro duplicado'
        ]);
    }
}
