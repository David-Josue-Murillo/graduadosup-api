<?php

namespace Tests\Feature;

use Tests\TestCase;
use \App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordTest extends TestCase
{
    private $user;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function an_authenticade_user_can_update_their_password(): void
    {
        $this->user = User::where('name', 'David')->first();

        $data = [
            'current_password' => 'Lucha507.',
            'password' => 'Lucha533.',
            'password_confirmation' => 'Lucha533.',
        ];
        
        $response = $this->actingAs($this->user)->patchJson('/api/users/update-password', $data);

        $response->assertStatus(200);
        $this->user->refresh();
        $response->assertTrue(Hash::check('Lucha533.', $this->user->password));
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
            ->patchJson('/api/users/update-password', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['current_password']);

        $this->user->refresh();
        
        $this->assertTrue(
            Hash::check('Lucha507.', $this->user->password),
            'The password should not have changed'
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
