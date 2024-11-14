<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Notification;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    
    protected function setUp(): void
    {
        parent::setUp();
    }
    
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        Notification::fake();
        $data = ['email' => 'dm514831@gmail.com'];

        $response = $this->postJson('/forgot-password', $data);

        $response->assertStatus(200);
        $response->assertJsonFragment(['messages' => 'Correo enviado exitosamente']); // Check if the response has the message
    
        
    }
}
