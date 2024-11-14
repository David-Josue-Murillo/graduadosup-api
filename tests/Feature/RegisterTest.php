<?php

namespace Tests\Feature;

use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /** @test */
    public function test_example(): void
    {
        $this->withoutExceptionHandling();

        $registerData = [
            'name' => 'Josue Serrano',
            'email' => 'dm514823@gmail.com',
            'password' => Hash::make('password')  
        ];

        $response = $this->post('/register', $registerData);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'user',
                'token'
            ]
        ]);
    }
}
