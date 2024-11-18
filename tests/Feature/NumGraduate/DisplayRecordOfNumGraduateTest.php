<?php

namespace Tests\Feature\NumGraduateTest;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Database\Seeders\CampuSeeder;
use Database\Seeders\CareerSeeder;
use Database\Seeders\FacultySeeder;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

class DisplayRecordOfNumGraduateTest extends TestCase
{
    use RefreshDatabase;
    private const URL = '/graduates';
    private const JSON_RESPONSE = [
        'message',
        'data' => [[
            'id',
            'quantity',
            'year',
            'campus' => [
                'id',
                'name'
            ],
            'career' => [
                'id',
                'name'
            ]
        ]
        ],
        'status',
        'errors'
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            UserSeeder::class,
            FacultySeeder::class,
            CampuSeeder::class,
            CareerSeeder::class
        ]);
    }

    protected function validGraduateData(array $overrides = []): array
    {
        return array_merge([
            'quantity' => 100,
            'year' => now()->year,
            'campus_id' => 1,
            'career_id' => 1,
        ], $overrides);
    }

    /** @test */
    public function it_should_returned_all_graduates(): void
    {
        $response = $this->apiAs(User::find(1), 'get', self::URL);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data',
                'status',
                'errors'
            ]);
    }

    /** @test */
    public function register_a_new_number_of_graduates(): void
    {
        $data = $this->validGraduateData();

        $response = $this->apiAs(User::find(1), 'post', self::URL, $data);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'data',
            'status',
            'errors'
        ]);
    }

    /** @test */
    public function it_should_returned_all_graduates_that_matching_with_campus_id(): void
    {
        $this->register_a_new_number_of_graduates();
        $response = $this->apiAs(User::find(1), 'get', '/api'.self::URL.'?campus_id=1');

        $response->assertStatus(200)
            ->assertJsonStructure(self::JSON_RESPONSE)
            ->assertJsonFragment([
                'campus' => [
                    'id' => 1,
                    'name' => 'Centro regional universitario de Veraguas',
                ],
            ]);
    }

    /** @test */
    public function it_should_returned_all_graduates_that_matching_with_career_id(): void
    {
        $this->register_a_new_number_of_graduates();
        $response = $this->apiAs(User::find(1), 'get', '/api'.self::URL.'?career=1');

        $response->assertStatus(200)
            ->assertJsonStructure(self::JSON_RESPONSE)
            ->assertJsonFragment([
                'career' => [
                    'id' => 1,
                    'name' => 'Ingeniería en Informática',
                    "faculty" => [
                        'id' => 1,
                        'name' => "Facultad de Informática, Electrónica y Comunicación"
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_should_returned_all_graduates_that_matching_with_the_year(): void
    {
        $this->register_a_new_number_of_graduates();
        $response = $this->apiAs(User::find(1), 'get', '/api'.self::URL.'?year=2024');

        $response->assertStatus(200)
            ->assertJsonStructure(self::JSON_RESPONSE)
            ->assertJsonFragment([
                'year' => 2024
            ]);
    }    
}
