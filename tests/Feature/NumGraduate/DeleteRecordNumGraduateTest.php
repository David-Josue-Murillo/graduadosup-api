<?php

namespace Tests\Feature\NumGraduate;

use App\Models\User;
use Database\Seeders\NumGraduateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteRecordNumGraduateTest extends TestCase
{
    use RefreshDatabase;
    private const URL = '/graduates';

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            NumGraduateSeeder::class
        ]);
    }

    /** @test */
    public function must_delete_a_record(): void 
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
                'quantity' => 100,
                'year' => 2021,
                'campus_id' => 1,
                'career_id' => 1
            ]);
    }

    /** @test */
    public function must_not_delete_record(): void 
    {
        $response = $this->apiAs(User::find(1), 'delete', self::URL.'/100');

        $response->assertStatus(422)
            ->assertJsonStructure(['message','errors']);
    }
}
