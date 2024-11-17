<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /** @test */
    public function an_existing_user_can_login(): void
    {
        $this->withoutExceptionHandling();
        // Dato a probar
        $credentianls = [
            'login' => 'test@test.com',
            'password' => 'password'
        ];

        // Realizando la prueba
        $response = $this->post('/login', $credentianls);

        // Respuesta esperada
        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => ['token']]);
    }

    /** @test */
    public function an_non_existing_user_cannot_login(): void
    {
        // Dato a probar
        $credentianls = [
            'login' => 'admi@admi.com',
            'password' => 'password'
        ];

        // Realizando la prueba
        $response = $this->post('/login', $credentianls);

        // Respuesta esperada
        $response->assertStatus(401);
        $response->assertJsonFragment([
            'errors' => 'Unauthorized',
            'status' => 401
        ]);
    }

    /** @test */
    public function email_must_be_required(): void
    {
        // Dato a probar
        $credentianls = [
            'password' => 'password'
        ];

        // Realizando la prueba
        $response = $this->postJson('/login', $credentianls);

        // Respuesta esperada
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors']);
        $response->assertJsonFragment([
            'errors' => 'El correo electrónico o nombre de usuario es obligatorio.',
        ]);
    }
    
    /** @test */
    public function password_must_be_required(): void
    {
        // Dato a probar
        $credentianls = [
            'login' => 'test@test.com',
        ];

        // Realizando la prueba
        $response = $this->post('/login', $credentianls);

        // Respuesta esperada
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors']);
        $response->assertJsonFragment([
            'errors' => 'La contraseña es obligatoria.'
        ]);
    }
}
