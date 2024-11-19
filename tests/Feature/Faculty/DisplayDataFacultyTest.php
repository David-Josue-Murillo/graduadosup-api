<?php

namespace Tests\Feature\Faculty;

use App\Models\User;
use Database\Seeders\NumGraduateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DisplayDataFacultyTest extends TestCase
{
    use RefreshDatabase;
    private const URL = '/faculties';

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([NumGraduateSeeder::class]);
    }

    /** @test */
    public function it_must_returned_all_faculties(): void
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
                'name' => 'Facultad de Informática, Electrónica y Comunicación'
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
        $response = $this->apiAs(User::find(1), 'get', self::URL.'/1/career');

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'data', 'status', 'errors'])
            ->assertJsonFragment([
                'id' => 1,
                'name' => 'Ingeniería en Informática',
            ]);
    }
}
