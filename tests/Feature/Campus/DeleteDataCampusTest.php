<?php

namespace Tests\Feature\Campus;

use App\Models\User;
use Database\Seeders\NumGraduateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteDataCampusTest extends TestCase
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
    public function must_delete_a_existing_record(): void 
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
                'name' => 'Centro regional universitario de Veraguas'
            ]);
    }

    /** @test */
    public function it_cannot_delete_a_not_exit_record(): void 
    {
        $response = $this->apiAs(User::find(1), 'delete', self::URL.'/100');

        $response->assertStatus(422)
            ->assertJsonStructure(['message','errors']);
    }
}
