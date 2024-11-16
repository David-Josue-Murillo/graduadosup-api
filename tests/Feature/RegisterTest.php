<?php

namespace Tests\Feature;

use Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /** @test */
    public function a_new_user_is_registering(): void
    {
        $this->withoutExceptionHandling();

        $registerData = [
            'name' => 'Josue Serrano',
            'email' => 'dm51ss4823@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
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


    /** @test */
    public function name_field_must_be_required(): void
    {
        $registerData = [
            'email' => 'dm514823@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->post('/register', $registerData);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El nombre es obligatorio. (and 1 more error)'
        ]);
    }

    /** @test */
    public function name_must_be_valid(): void
    {
        $registerData = [
            'name' => 'David507',
            'email' => 'dm514823@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->post('/register', $registerData);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El nombre solo puede contener letras y espacios. (and 1 more error)'
        ]);
    }

    /** @test */
    public function email_field_must_be_required(): void
    {
        $registerData = [
            'name' => 'Josue Serrano',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->post('/register', $registerData);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El correo electrónico es obligatorio.'
        ]);
    }

    /** @test */
    public function email_is_not_valid(): void
    {
        $registerData = [
            'name' => 'Josue Serrano',
            'email' => 'prueba@prueba',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->post('/register', $registerData);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El correo electrónico debe ser válido.'
        ]);
    }

    /** @test */
    public function email_must_not_be_duplicated(): void
    {
        $registerData = [
            'name' => 'Josue Murillo',
            'email' => 'dm514823@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->post('/register', $registerData);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'Este correo electrónico ya está registrado.'
        ]);
    }

    /** @test */
    public function password_field_must_be_required(): void
    {
        $registerData = [
            'name' => 'Josue Serrano',
            'email' => 'dm514831@gmail.com',
        ];

        $response = $this->post('/register', $registerData);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'La contraseña es obligatoria.'
        ]);
    }

    /** @test */
    public function password_must_be_at_least_6_characters(): void
    {
        $registerData = [
            'name' => 'Josue Serrano',
            'email' => 'dm5148ss311@gmail.com',
            'password' => 'test',  
            'password_confirmation' => 'test',
        ];

        $response = $this->postJson('/register', $registerData);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'La contraseña debe tener al menos 6 caracteres.'
        ]);
    }
}
