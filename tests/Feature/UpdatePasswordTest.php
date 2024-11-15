<?php

namespace Tests\Feature;

use Auth;
use Hash;
use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdatePasswordTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function an_authenticade_user_can_update_their_password(): void
    {
        $user = User::where('name', 'David')->first();

        $data = [
            'old_password' => 'Lucha507.',
            'password' => 'Lucha533.',
            'password_confirmation' => 'Lucha533.',
        ];

        $response = $this->actingAs($user)->patchJson('/api/users/update-password', $data);

        $response->assertStatus(200);
        $user = User::where('name', 'David')->first();
        $response->assertTrue(Hash::check('Lucha533.', $user->password));
    }
}
