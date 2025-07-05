<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_profile_with_valid_data()
    {
        $user = User::factory()->create([
            'username_changed_at' => now()->subMonths(4),
        ]);

        $this->actingAs($user);

        $user->username_changed_at;

        $response = $this->post(route('profile.settings.update'), [
            'username' => 'newusername',
            'display_name' => 'Novo Nome',
            'email' => 'new@example.com',
            'current_password' => 'password', // senha da factory
        ]);

        $response->assertRedirect(); // redireciona de volta
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'username' => 'newusername',
            'email' => 'new@example.com',
        ]);
    }

    public function test_user_cannot_change_username_if_less_than_3_months()
    {
        $user = User::factory()->create([
            'username' => 'oldname',
            'username_changed_at' => now()->subMonth(1), // menos de 3 meses
        ]);

        $this->actingAs($user);

        $response = $this->post(route('profile.settings.update'), [
            'username' => 'newname',
            'email' => $user->email,
        ]);

        $response->assertSessionHasErrors(['username']);
        $this->assertDatabaseHas('users', ['username' => 'oldname']);
    }

    public function test_user_must_provide_password_to_change_email()
    {
        $user = User::factory()->create([
            'email' => 'old@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->actingAs($user);

        $response = $this->post(route('profile.settings.update'), [
            'username' => $user->username,
            'email' => 'new@example.com',
            'current_password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors(['current_password']);
        $this->assertDatabaseHas('users', ['email' => 'old@example.com']);
    }

    public function test_avatar_upload_works()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('profile.settings.update'), [
            'username' => $user->username,
            'email' => $user->email,
            'file' => UploadedFile::fake()->image('avatar.jpg'),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertNotNull($user->attachment, 'Attachment não foi criado');
        Storage::disk('public')->assertExists($user->attachment->filepath);
    }
}
