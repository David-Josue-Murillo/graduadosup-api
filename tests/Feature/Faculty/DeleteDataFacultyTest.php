<?php

namespace Tests\Feature\Faculty;

use App\Models\User;
use Database\Seeders\NumGraduateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

    #[Test] public function must_delete_a_existing_record(): void
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
                'id' => 1,
                'name' => 'Facultad de Informática, Electrónica y Comunicación'
            ]);
    }

    #[Test] public function it_cannot_delete_a_not_exit_record(): void
    {
        $response = $this->apiAs(User::find(1), 'delete', self::URL.'/100');

        $response->assertStatus(422)
            ->assertJsonStructure(['message','errors']);
    }
}
