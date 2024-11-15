<?php

namespace Tests\Feature;

use Auth;
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
        $user = auth()->user();
        dd($user);

        $data = [
            'old_password' => 'Lucha507.',
            'password' => 'Lucha533.',
            'password_confirmation' => 'Lucha533.',
        ];

        $response = $this->put('/', $data);

        $response->assertStatus(200);
    }
}
