<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        \Illuminate\Support\Facades\Mail::fake();

        $response = $this->post('/register', [
            'nama' => 'Test User',
            'email' => 'test@example.com',
            'no_hp' => '08123456789',
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => 'on',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'nama' => 'Test User',
            'no_hp' => '08123456789',
        ]);

        \Illuminate\Support\Facades\Mail::assertSent(\App\Mail\WelcomeMail::class, function ($mail) {
            return $mail->hasTo('test@example.com') && $mail->user->nama === 'Test User';
        });
    }
}
