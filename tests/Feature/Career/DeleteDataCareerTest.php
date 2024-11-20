<?php

namespace Tests\Feature\Career;

use App\Models\Career;
use App\Models\User;
use Database\Seeders\NumGraduateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DeleteDataCareerTest extends TestCase
{
    use RefreshDatabase;
    private const URL = '/careers';

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([NumGraduateSeeder::class]);
    }

    protected function validGraduateData(array $overrides = []): array
    {
        return array_merge([
            'id' => 1,
            'name' => 'Técnico en Informática',
            'faculty_id' => 1,
        ], $overrides);
    }

    #[Test] public function it_deletes_an_existing_record(): void
    {
        $response = $this->apiAs(User::find(1), 'delete', self::URL.'/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data',
                'status',
                'errors'
            ])
            ->assertJsonFragment([
                'name' => 'Ingeniería en Informática'
            ]);
        $this->assertDatabaseMissing('careers', ['id' => 1]);
    }

    #[Test] public function it_fails_to_delete_a_non_existent_record(): void
    {
        $response = $this->apiAs(User::find(1), 'delete', self::URL.'/999');

        $response->assertStatus(422)
            ->assertJsonStructure(['message','errors']);
    }
}
