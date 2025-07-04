<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StoreAttachmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_upload_avatar_attachment()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $this->actingAs($user);

        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->post(route('profile.settings.store'), [
            'file' => $file,
        ]);

        $response->assertStatus(302);

        Storage::disk('public')->assertExists('attachments/' . $file->hashName());

        $this->assertDatabaseHas('attachments', [
            'user_id' => $user->id,
            'filepath' => $file->hashName(),
        ]);
    }
}
