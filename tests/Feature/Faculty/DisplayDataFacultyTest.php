<?php

namespace Tests\Feature\Faculty;

use App\Models\User;
use Database\Seeders\NumGraduateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
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

    #[Test] public function it_must_returned_all_faculties(): void
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

    #[Test] public function it_returns_all_faculty_records(): void
    {
        $response = $this->apiAs(User::find(1), 'get', self::URL.'/1');

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'data', 'status', 'errors'])
            ->assertJsonFragment([
                'id' => 1,
                'name' => 'Facultad de Informática, Electrónica y Comunicación'
            ]);
    }

    #[Test] public function it_return_a_specific_record(): void
    {
        $response = $this->apiAs(User::find(1), 'get', self::URL.'/100');

        $response->assertStatus(422)
            ->assertJsonStructure(['message','errors']);
    }

    #[Test] public function it_return_the_career_that_matches_the_faculty(): void
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
