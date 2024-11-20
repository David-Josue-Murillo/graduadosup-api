<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
    }

    #[Test] public function an_existing_user_request_password_reset(): void
    {
        Notification::fake();
        $data = ['email' => 'test@test.com']; 

        $response = $this->postJson('/forgot-password', $data); // Send the email

        $response->assertStatus(200);
        $response->assertJsonFragment(['message' => 'Correo enviado exitosamente']); // Check if the response has the message
        
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
    }

    #[Test] public function an_existing_user_performs_a_password_reset(): void
    {
        $this->an_existing_user_request_password_reset();
        $data = [
            'email' => 'test@test.com',
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ];

        $response = $this->putJson("/api/reset-password?token={$this->token}", $data);

        $response->assertStatus(200);
        $response->assertJsonStructure(['message', 'data', 'errors', 'status']);

        $user = User::where('email', $data['email'])->first();
        $this->assertTrue(Hash::check('new_password', $user->password));
    }

    #[Test] public function it_requires_the_email_field(): void
    {
        Notification::fake();
        $data = ['email' => '']; 

        $response = $this->postJson('/forgot-password', $data); // Send the email

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'errors' => 'El correo electrónico es obligatorio.'
        ]); 
    }

    #[Test] public function it_require_the_email_field_to_be_valid(): void
    {
        Notification::fake();
        $data = ['email' => 'test@.test.com']; 

        $response = $this->postJson('/forgot-password', $data); // Send the email

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'errors' => 'El correo electrónico no es válido.'
        ]); 
    }

    #[Test] public function it_require_email_to_exit(): void
    {
        Notification::fake();
        $data = ['email' => 'admin@admin.com']; 

        $response = $this->postJson('/forgot-password', $data); // Send the email

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'errors' => 'El correo electrónico no está registrado.'
        ]); 
    }

    #[Test] public function it_requires_the_token(): void
    {
        $data = [
            'email' => 'test@test.com',
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ];

        $response = $this->putJson("/reset-password", $data);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'errors' => 'The token field is required.'
        ]); 
    }

    #[Test] public function it_requires_the_password_field(): void
    {
        $this->an_existing_user_request_password_reset();
        $data = [
            'email' => 'test@test.com',
            'password' => '',
            'password_confirmation' => 'new_password',
        ];

        $response = $this->putJson('/api/reset-password?token='.$this->token, $data);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'errors' => 'La contraseña es obligatoria.'
        ]); 
    }

    #[Test] public function it_require_the_password_to_be_at_least_6_characters(): void
    {
        $this->an_existing_user_request_password_reset();
        $data = [
            'email' => 'test@test.com',
            'password' => 'test',
            'password_confirmation' => 'test',
        ];

        $response = $this->putJson('/api/reset-password?token='.$this->token, $data);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'errors' => 'La contraseña debe tener al menos 6 caracteres.'
        ]); 
    }

    #[Test] public function it_requires_the_confirmation_password_field_and_to_be_match(): void
    {
        $this->an_existing_user_request_password_reset();
        $data = [
            'email' => 'test@test.com',
            'password' => 'new_password',
        ];

        $response = $this->putJson('/api/reset-password?token='.$this->token, $data);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'errors' => 'La contraseña de confirmación es obligatoria y debe coincidir.'
        ]); 
    }
}
