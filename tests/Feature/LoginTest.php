<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    #[Test]
    public function an_existing_user_can_login(): void
    {
        // Dato a probar
        $credentianls = [
            'email' => 'rutilio@gmail.com',
            'password' => '123456'
        ];

        // Realizando la prueba
        $response = $this->get('/api/login', $credentianls);

        // Respuesta esperada
        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => ['token']]);
    }

    public function an_non_existing_user_cannot_login(): void
    {
        // Dato a probar
        $credentianls = [
            'email' => 'admi@admi.com',
            'password' => 'dsdsssdds'
        ];

        // Realizando la prueba
        $response = $this->get('/api/login', $credentianls);

        // Respuesta esperada
        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => ['token']]);
    }

    public function email_must_be_required(): void
    {
        // Dato a probar
        $credentianls = [
            'password' => 'dsdsssdds'
        ];

        // Realizando la prueba
        $response = $this->get('/api/login', $credentianls);

        // Respuesta esperada
        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => ['token']]);
    }

    public function password_must_be_required(): void
    {
        // Dato a probar
        $credentianls = [
            'email' => 'rutilio@gmail.com',
        ];

        // Realizando la prueba
        $response = $this->get('/api/login', $credentianls);

        // Respuesta esperada
        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => ['token']]);
    }
}
