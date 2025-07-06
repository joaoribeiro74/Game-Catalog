<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;
    public function test_user_can_register_with_valid_data()
    {
        $response = $this->post(route('register'), [
            'email' => 'test@email.com',
            'username' => 'testuser',
            'password' => 'Strong@123',
            'password_confirmation' => 'Strong@123',
        ]);

        $response->assertRedirect(route('games.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'test@email.com',
            'username' => 'testuser',
        ]);
    }

    public function test_registration_fails_with_invalid_username()
    {
        $response = $this->from(route('register'))->post(route('register'), [
            'email' => 'test@email.com',
            'username' => 'invalid user!',
            'password' => 'Strong@123',
            'password_confirmation' => 'Strong@123',
        ]);

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrors('username');
    }

    public function test_password_is_hashed()
    {
        $this->post(route('register'), [
            'email' => 'hash@email.com',
            'username' => 'hashuser',
            'password' => 'Strong@123',
            'password_confirmation' => 'Strong@123',
        ]);

        $user = User::where('email', 'hash@email.com')->first();
        $this->assertTrue(Hash::check('Strong@123', $user->password));
    }

    public function test_registration_fails_with_weak_password()
    {
        $response = $this->from(route('register'))->post(route('register'), [
            'email' => 'weak@email.com',
            'username' => 'weakuser',
            'password' => 'weakpass',
            'password_confirmation' => 'weakpass',
        ]);

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrors('password');
        $this->assertDatabaseMissing('users', ['email' => 'weak@email.com']);
    }

    public function test_registration_fails_with_duplicate_email()
    {
        User::factory()->create(['email' => 'duplicate@email.com']);

        $response = $this->from(route('register'))->post(route('register'), [
            'email' => 'duplicate@email.com',
            'username' => 'newuser',
            'password' => 'Strong@123',
            'password_confirmation' => 'Strong@123',
        ]);

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrors('email');
    }

    public function test_registration_fails_with_duplicate_username()
    {
        User::factory()->create(['username' => 'duplicateuser']);

        $response = $this->from(route('register'))->post(route('register'), [
            'email' => 'new@email.com',
            'username' => 'duplicateuser',
            'password' => 'Strong@123',
            'password_confirmation' => 'Strong@123',
        ]);

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrors('username');
    }

    public function test_registration_fails_if_password_confirmation_does_not_match()
    {
        $response = $this->from(route('register'))->post(route('register'), [
            'email' => 'confirm@email.com',
            'username' => 'confirmuser',
            'password' => 'Strong@123',
            'password_confirmation' => 'WrongConfirm123',
        ]);

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrors('password');
    }
}
