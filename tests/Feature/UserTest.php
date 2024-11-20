<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
    }

    #[Test] public function a_user_request_to_see_the_entire_number_of_graduates(): void
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
