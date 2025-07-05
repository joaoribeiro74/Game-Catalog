<?php

namespace Tests\Feature;

use App\Models\Attachment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserDestroyTest extends TestCase
{
    use RefreshDatabase;
    public function test_user_can_delete_avatar_attachment()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $this->actingAs($user);

        $file = UploadedFile::fake()->image('avatar.jpg');
        $this->post(route('profile.settings.store'), ['file' => $file]);
        $user->refresh();
        $attachment = $user->attachment;

        Storage::disk('public')->assertExists('attachments/' . $file->hashName());

        $attachment = $user->attachment;
        $response = $this->delete(route('profile.settings.destroy', $attachment->id));

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        Storage::disk('public')->assertMissing('attachments/' . $file->hashName());

        $this->assertDatabaseMissing('attachments', ['id' => $attachment->id]);
    }

    public function test_observer_deletes_file_when_attachment_deleted()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $this->actingAs($user);

        $file = UploadedFile::fake()->image('avatar.jpg');
        $this->post(route('profile.settings.store'), ['file' => $file]);

        $user->refresh();

        $attachment = $user->attachment;

        Storage::disk('public')->assertExists('attachments/' . $attachment->filepath);

        $attachment->delete();

        Storage::disk('public')->assertMissing('attachments/' . $attachment->filepath);
    }
}
