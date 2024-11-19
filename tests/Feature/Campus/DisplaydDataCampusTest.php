<?php

namespace Tests\Feature\Campus;

use App\Models\User;
use Database\Seeders\NumGraduateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DisplaydDataCampusTest extends TestCase
{
    use RefreshDatabase;
    private const URL = '/campus';

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([NumGraduateSeeder::class]);
    }

    protected function validGraduateData(array $overrides = []): array
    {
        return array_merge([
            'id' => 1,
            'name' => 'Centro regional universitario de Veraguas',
        ], $overrides);
    }

    /** @test */
    public function it_must_returned_all_data(): void
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
    public function must_return_a_specific_record(): void
    {
        $response = $this->apiAs(User::find(1), 'get', self::URL.'/1');

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'data', 'status', 'errors'])
            ->assertJsonFragment([
                'id' => 1,
                'name' => 'Centro regional universitario de Veraguas'
            ]);
    }

    /** @test */
    public function must_return_an_error_if_the_record_does_not_exist(): void
    {
        $response = $this->apiAs(User::find(1), 'get', self::URL.'/100');

        $response->assertStatus(422)
            ->assertJsonStructure(['message','errors']);
    }

    /** @test */
    public function must_return_the_career_that_belongs_to_the_given_record(): void
    {
        $response = $this->apiAs(User::find(1), 'get', self::URL.'/1/faculty');

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'data', 'status', 'errors'])
            ->assertJsonFragment([
                'id' => 1,
                'name' => 'Ingeniería en Informática',
            ]);
    }
}
