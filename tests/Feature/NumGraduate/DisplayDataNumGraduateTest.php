<?php

namespace Tests\Feature\NumGraduateTest;

use Database\Seeders\NumGraduateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DisplayDataNumGraduateTest extends TestCase
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

    #[Test] public function it_returns_all_num_graduates_records(): void
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

    #[Test] public function it_must_returned_all_graduates_that_matching_with_campus_id(): void
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

    #[Test] public function it_must_returned_all_graduates_that_matching_with_career_id(): void
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

    #[Test] public function it_must_returned_all_graduates_that_matching_with_the_year(): void
    {
        $response = $this->apiAs(User::find(1), 'get', self::URL.'?year=2023');

        $response->assertStatus(200)
            ->assertJsonStructure(self::JSON_RESPONSE)
            ->assertJsonFragment([
                'year' => 2023
            ]);
    }

    #[Test] public function it_return_a_specific_record(): void
    {
        $response = $this->apiAs(User::find(1), 'get', self::URL.'/1');

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'data', 'status', 'errors'])
            ->assertJsonFragment([
                'id' => 1,
            ]);
    }

    #[Test] public function it_requires_the_num_graduates_id_to_exist(): void
    {
        $response = $this->apiAs(User::find(1), 'get', self::URL.'/100');

        $response->assertStatus(422)
            ->assertJsonStructure(['message','errors']);
    }

    #[Test] public function it_return_the_campus_that_belongs_to_the_given_record(): void
    {
        $response = $this->apiAs(User::find(1), 'get', self::URL.'/1/campus');

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'data', 'status', 'errors'])
            ->assertJsonFragment([
                'id' => 1,
                'name' => 'Centro regional universitario de Veraguas',
            ]);
    }

    #[Test] public function it_return_the_career_that_belongs_to_the_given_record(): void
    {
        $response = $this->apiAs(User::find(1), 'get', self::URL.'/1/career');

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'data', 'status', 'errors'])
            ->assertJsonFragment([
                'id' => 1,
                'name' => 'Ingeniería en Informática',
            ]);
    }

    #[Test] public function it_return_the_faculty_that_belongs_to_the_given_record(): void
    {
        $response = $this->apiAs(User::find(1), 'get', self::URL.'/1/faculty');

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'data', 'status', 'errors'])
            ->assertJsonFragment([
                'id' => 1,
                'name' => 'Facultad de Informática, Electrónica y Comunicación',
            ]);
    }
}
