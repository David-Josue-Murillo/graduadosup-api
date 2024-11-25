<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
use PHPUnit\Framework\Attributes\Test;
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

    #[Test] public function an_authenticated_user_can_update_their_password(): void
    {
        $data = [
            'current_password' => 'password',
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ];

        $response = $this->apiAs(User::find(1), 'patch', '/user/update-password', $data);

        $response->assertOk();
        User::find(1)->refresh();
        $this->assertTrue(
            Hash::check('new_password', User::find(1)->password),
            'La nueva contraseña no se guardó correctamente'
        );    
    }

    #[Test] public function an_user_cannot_update_password_with_invalid_current_password(): void
    {
        $data = [
            'current_password' => 'WrongPassword',
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ];

        $response = $this->apiAs(User::find(1), 'patch', '/user/update-password', $data);
        $response->assertStatus(422);
        User::find(1)->refresh();
        $this->assertFalse(
            Hash::check('new_password', User::find(1)->password),
            'La contraseña no debería haber cambiado'
        );
    }

    #[Test] public function an_user_cannot_update_password_without_current_password(): void
    {
        $data = [
            'password' => 'other_password',
            'password_confirmation' => 'other_password',
        ];

        $response = $this->apiAs(User::find(1), 'patch', '/user/update-password', $data);
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors']);
        $response->assertJsonFragment([
            'errors' => 'El campo contraseña actual es requerida'
        ]);
    }


    #[Test] public function it_require_the_password_confirmation_field_to_be_at_least_8_characters(): void
    {
        $data = [
            'current_password' => 'password',
            'password' => 'test',
            'password_confirmation' => 'test',
        ];
        
        $response = $this->apiAs(User::find(1), 'patch', '/user/update-password', $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors']);
        $response->assertJsonFragment([
            'errors' => 'El campo contraseña debe tener al menos 8 caracteres'
        ]);
    }

    #[Test] public function it_requires_the_password_confirmation_field_to_be_match_with_the_password_field(): void
    {
        $data = [
            'current_password' => 'password',
            'password' => 'new_password',
            'password_confirmation' => 'different_password',
        ];

        $response = $this->apiAs(User::find(1), 'patch', '/user/update-password', $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors']);
        $response->assertJsonFragment([
            'errors' => 'La contraseñas no coinciden'
        ]);
    }
}