<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Notification;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    protected $token;
    protected $email;

    protected function setUp(): void
    {
        parent::setUp();
    }
    
    /** @test */
    public function an_existing_user_can_reset_their_password(): void
    {
        Notification::fake();
        $data = ['email' => 'dm514822@gmail.com']; 

        $response = $this->postJson('/forgot-password', $data); // Send the email

        $response->assertStatus(200);
        $response->assertJsonFragment(['messages' => 'Correo enviado exitosamente']); // Check if the response has the message
        
        $user = User::where('email', $data['email'])->first();
        Notification::assertSentTo(
            [$user], 
            function (ResetPasswordNotification $notification){
                $url = $notification->url;
                $parts = parse_url($url);

                parse_str($parts['query'], $query);
                $this->token = $query['token'];
                $this->email = $query['email'];

                return str_contains($url, 'http://127.0.0.1:8000/api/reset-password?token=');
            }
        );

        // Reset the password
        $response = $this->putJson("/api/reset-password?token={$this->token}", [
            'email' => $this->email,
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['message', 'data', 'errors', 'status']);

        $user = User::where('email', $data['email'])->first();
        $this->assertTrue(Hash::check('new_password', $user->password));
    }
}
