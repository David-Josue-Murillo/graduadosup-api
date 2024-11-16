<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::find(11);
    }

    /** @test */
    public function an_authenticated_user_can_update_their_password(): void
    {
        $data = [
            'current_password' => 'Lucha599.',
            'password' => 'Lucha591.',
            'password_confirmation' => 'Lucha591.',
        ];

        $response = $this->actingAs($this->user)
            ->patchJson('/users/update-password', $data);
        
        $response->assertOk();
        $this->user->refresh();
        $this->assertTrue(
            Hash::check('Lucha591.', $this->user->password),
            'La nueva contraseña no se guardó correctamente'
        );
    }

    /** @test */
    public function user_cannot_update_password_with_invalid_current_password(): void
    {
        $data = [
            'current_password' => 'WrongPassword',
            'password' => 'Lucha533.',
            'password_confirmation' => 'Lucha533.',
        ];

        $response = $this->actingAs($this->user)
            ->patchJson('/users/update-password', $data);

        $response->assertStatus(422);
        $this->user->refresh();
        $this->assertTrue(
            Hash::check('Lucha533.', $this->user->password),
            'La contraseña no debería haber cambiado'
        );
    }

    /** @test */
    public function user_cannot_update_password_without_current_password(): void
    {
        $data = [
            'password' => 'Lucha533.',
            'password_confirmation' => 'Lucha533.',
        ];

        $response = $this->actingAs($this->user)
            ->patchJson('/users/update-password', $data);

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
            'current_password' => 'Lucha591.',
            'password' => 'test',
            'password_confirmation' => 'test',
        ];

        $response = $this->actingAs($this->user)
            ->patchJson('/users/update-password', $data);

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
            'current_password' => 'Lucha533.',
            'password' => 'Lucha507.',
            'password_confirmation' => 'DifferentPassword',
        ];

        $response = $this->actingAs($this->user)
            ->patchJson('/users/update-password', $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors']);
        $response->assertJsonFragment([
            'errors' => 'La contraseñas no coinciden'
        ]);
    }
}