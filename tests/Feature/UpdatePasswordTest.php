<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
    }

    /** @test */
    public function an_authenticated_user_can_update_their_password(): void
    {
        $data = [
            'current_password' => 'password',
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ];

        $response = $this->apiAs(User::find(1), 'patch', '/users/update-password', $data);

        $response->assertOk();
        User::find(1)->refresh();
        $this->assertTrue(
            Hash::check('new_password', User::find(1)->password),
            'La nueva contraseña no se guardó correctamente'
        );    
    }

    /** @test */
    public function user_cannot_update_password_with_invalid_current_password(): void
    {
        $data = [
            'current_password' => 'WrongPassword',
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ];

        $response = $this->apiAs(User::find(1), 'patch', '/users/update-password', $data);
        $response->assertStatus(422);
        User::find(1)->refresh();
        $this->assertFalse(
            Hash::check('new_password', User::find(1)->password),
            'La contraseña no debería haber cambiado'
        );
    }

    /** @test */
    public function user_cannot_update_password_without_current_password(): void
    {
        $data = [
            'password' => 'other_password',
            'password_confirmation' => 'other_password',
        ];

        $response = $this->apiAs(User::find(1), 'patch', '/users/update-password', $data);
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors']);
        $response->assertJsonFragment([
            'errors' => 'El campo contraseña actual es requerida'
        ]);
    }


    /** @test */
    public function password_confirmation_must_be_at_least_8_characters(): void
    {
        $data = [
            'current_password' => 'password',
            'password' => 'test',
            'password_confirmation' => 'test',
        ];
        
        $response = $this->apiAs(User::find(1), 'patch', '/users/update-password', $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors']);
        $response->assertJsonFragment([
            'errors' => 'El campo contraseña debe tener al menos 8 caracteres'
        ]);
    }

    /** @test */
    public function password_confirmation_must_match(): void
    {
        $data = [
            'current_password' => 'password',
            'password' => 'new_password',
            'password_confirmation' => 'different_password',
        ];

        $response = $this->apiAs(User::find(1), 'patch', '/users/update-password', $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors']);
        $response->assertJsonFragment([
            'errors' => 'La contraseñas no coinciden'
        ]);
    }
}