<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /** @test */
    public function a_user_requests_to_see_the_entire_number_of_graduates(): void
    {
        $response = $this->apiAs(User::find(1), 'GET', '/graduates');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'data' => [
                '*' => [
                    'id',
                    'quantity',
                    'year',
                    'campus' => [
                        'id',
                        'name'
                    ],
                    'career' => [
                        'id',
                        'name',
                        'faculty' => [
                            'id',
                            'name'
                        ]
                    ]
                ],
            ],
            'status'
        ]);
    }
}
