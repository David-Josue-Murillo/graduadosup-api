<?php

namespace Tests\Feature\NumGraduateTest;

use Database\Seeders\NumGraduateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
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
            NumGraduateSeeder::class
        ]);
    }

    /** @test */
    public function it_must_returned_all_graduates(): void
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
    public function it_must_returned_all_graduates_that_matching_with_campus_id(): void
    {
        $response = $this->apiAs(User::find(1), 'get', self::URL.'?campus_id=1');

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
    public function it_must_returned_all_graduates_that_matching_with_career_id(): void
    {
        $response = $this->apiAs(User::find(1), 'get', self::URL.'?career=1');

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
    public function it_must_returned_all_graduates_that_matching_with_the_year(): void
    {
        $response = $this->apiAs(User::find(1), 'get', self::URL.'?year=2023');

        $response->assertStatus(200)
            ->assertJsonStructure(self::JSON_RESPONSE)
            ->assertJsonFragment([
                'year' => 2023
            ]);
    }
    
    /** @test */
    public function must_return_a_specific_record(): void
    {
        $response = $this->apiAs(User::find(1), 'get', self::URL.'/1');

        dd($response->json());
        $response->assertStatus(200)
            ->assertJsonStructure(self::JSON_RESPONSE)
            ->assertJsonFragment([
                'quantity' => '100',
                'year' => '2021',
                'campus_id' => 1,
                'career_id' => 1
            ]);
    }
}
