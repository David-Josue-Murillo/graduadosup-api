<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
    }

    #[Test] public function a_new_user_is_registering(): void
    {
        $this->withoutExceptionHandling();

        $registerData = [
            'name' => 'Admin Admin',
            'email' => 'admin@admin.com',
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


    #[Test] public function name_field_must_be_required(): void
    {
        $registerData = [
            'email' => 'dmin@admin.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->post('/register', $registerData);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El nombre es obligatorio.'
        ]);
    }

    #[Test] public function name_must_be_valid(): void
    {
        $registerData = [
            'name' => 'Admin123',
            'email' => 'admin@admin.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->post('/register', $registerData);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'El nombre solo puede contener letras y espacios.'
        ]);
    }

    #[Test] public function email_field_must_be_required(): void
    {
        $registerData = [
            'name' => 'Admin Admin',
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

    #[Test] public function email_is_not_valid(): void
    {
        $registerData = [
            'name' => 'Admin Admin',
            'email' => 'test@test',
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

    #[Test] public function email_must_not_be_duplicated(): void
    {
        $registerData = [
            'name' => 'Admin Admin',
            'email' => 'test@test.com',
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

    #[Test] public function password_field_must_be_required(): void
    {
        $registerData = [
            'name' => 'Test Test',
            'email' => 'newtest@test.com',
        ];

        $response = $this->post('/register', $registerData);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message','errors']);
        $response->assertJsonFragment([
            'errors' => 'La contraseña es obligatoria.'
        ]);
    }

    #[Test] public function password_must_be_at_least_6_characters(): void
    {
        $registerData = [
            'name' => 'Test Test',
            'email' => 'newtest@test.com',
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

    #[Test] public function password_must_match(): void
     {
         $registerData = [
             'name' => 'Test Test',
             'email' => 'newtest@test.com',
             'password' => 'password',  
             'password_confirmation' => 'badpassword',
         ];
 
         $response = $this->postJson('/register', $registerData);
 
         $response->assertStatus(422);
         $response->assertJsonStructure(['message','errors']);
         $response->assertJsonFragment([
             'errors' => 'La confirmación de contraseña no coincide.'
         ]);
     }
}
