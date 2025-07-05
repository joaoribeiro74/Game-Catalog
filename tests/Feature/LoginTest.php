<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    public function test_user_can_authenticate_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
            'username' => 'user123',
        ]);

        $response = $this->post(route('login'), [
            'login' => 'user@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('games.index'));
        $this->assertAuthenticatedAs($user);

        $this->post(route('logout'));

        $response = $this->post(route('login'), [
            'login' => 'user123',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('games.index'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_authenticate_with_invalid_credentials()
    {
        $user = User::factory()->create();

        $response = $this->from(route('login'))->post(route('login'), [
            'login' => 'wronguser',
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', 'Credenciais incorretas.');
        $this->assertGuest();
    }

    public function test_logout_logs_out_and_redirects()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('logout'));
        $response->assertRedirect(route('welcome'));
        $this->assertGuest();
    }
}
