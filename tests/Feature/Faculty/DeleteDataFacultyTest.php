<?php

namespace Tests\Feature\Faculty;

use App\Models\User;
use Database\Seeders\NumGraduateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DeleteDataFacultyTest extends TestCase
{
    use RefreshDatabase;
    private const URL = '/faculties';

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([NumGraduateSeeder::class]);
    }

    #[Test] public function it_deletes_a_existing_record(): void
    {
        $response = $this->apiAs(User::find(1), 'delete', self::URL.'/1');

        $response->assertStatus(204);
        $this->assertDatabaseMissing('careers', ['id' => 1]);
    }

    #[Test] public function it_fails_to_delete_a_non_existent_record(): void
    {
        $response = $this->apiAs(User::find(1), 'delete', self::URL.'/100');

        $response->assertStatus(422)
            ->assertJsonStructure(['message','errors']);
    }
}
