<?php

namespace Tests\Feature\Campus;

use App\Models\User;
use Database\Seeders\NumGraduateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DisplayDataCampusTest extends TestCase
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

    #[Test] public function it_returns_all_campus_records(): void
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

    #[Test] public function it_return_a_specific_record(): void
    {
        $response = $this->apiAs(User::find(1), 'get', self::URL.'/1');

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'data', 'status', 'errors'])
            ->assertJsonFragment([
                'id' => 1,
                'name' => 'Centro regional universitario de Veraguas'
            ]);
    }

    #[Test] public function it_requires_the_faculty_id_to_exist(): void
    {
        $response = $this->apiAs(User::find(1), 'get', self::URL.'/100');

        $response->assertStatus(422)
            ->assertJsonStructure(['message','errors']);
    }
}
