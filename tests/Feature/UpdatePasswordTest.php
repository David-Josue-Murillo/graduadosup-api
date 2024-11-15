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
            'current_password' => 'Lucha507.',
            'password' => 'Lucha533.',
            'password_confirmation' => 'Lucha533.',
        ];

        $response = $this->actingAs($this->user)
            ->patchJson('/users/update-password', $data);
        
        $response->assertOk();
        $this->user->refresh();
        $this->assertTrue(
            Hash::check('Lucha533.', $this->user->password),
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
    public function password_confirmation_must_match(): void
    {
        $data = [
            'current_password' => 'Lucha507.',
            'password' => 'Lucha533.',
            'password_confirmation' => 'DifferentPassword',
        ];

        $response = $this->actingAs($this->user)
            ->patchJson('/api/users/update-password', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }
}