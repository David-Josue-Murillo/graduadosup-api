<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
    }

    #[Test] public function an_existing_user_can_login(): void
    {
        $this->withoutExceptionHandling();
        // Dato a probar
        $credentials = [
            'login' => 'test@test.com',
            'password' => 'password'
        ];

        // Realizando la prueba
        $response = $this->post('/login', $credentials);

        // Respuesta esperada
        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => ['token']]);
    }

    #[Test] public function an_non_existing_user_cannot_login(): void
    {
        // Dato a probar
        $credentials = [
            'login' => 'admi@admi.com',
            'password' => 'password'
        ];

        // Realizando la prueba
        $response = $this->post('/login', $credentials);

        // Respuesta esperada
        $response->assertStatus(401);
        $response->assertJsonFragment([
            'errors' => 'Unauthorized',
            'status' => 401
        ]);
    }

    #[Test] public function it_requires_the_email_field(): void
    {
        // Dato a probar
        $credentials = [
            'password' => 'password'
        ];

        // Realizando la prueba
        $response = $this->postJson('/login', $credentials);

        // Respuesta esperada
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors']);
        $response->assertJsonFragment([
            'errors' => 'El correo electrónico o nombre de usuario es obligatorio.',
        ]);
    }

    #[Test] public function it_requires_the_password_field(): void
    {
        // Dato a probar
        $credentials = [
            'login' => 'test@test.com',
        ];

        // Realizando la prueba
        $response = $this->post('/login', $credentials);

        // Respuesta esperada
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors']);
        $response->assertJsonFragment([
            'errors' => 'La contraseña es obligatoria.'
        ]);
    }
}
