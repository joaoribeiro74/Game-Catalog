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

        $image = UploadedFile::fake()->image('avatar.jpg');
        $contents = file_get_contents($image->getPathname());
        $base64 = 'data:image/jpeg;base64,' . base64_encode($contents);

        $response = $this->post(route('profile.settings.update'), ['resizedImage' => $base64]);

        $response->assertStatus(302);

        $user->refresh();
        $attachment = $user->attachment;

        Storage::disk('public')->assertExists($attachment->filepath);

        $this->assertDatabaseHas('attachments', [
            'user_id' => $user->id,
            'filepath' => $attachment->filepath,
        ]);
    }

    public function test_upload_invalid_image_format_fails()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $this->actingAs($user);

        $invalidBase64 = 'data:image/gif;base64,R0lGODlhPQBEAPeoAJosM//AwO/AwHV7f//';

        $response = $this->post(route('profile.settings.update'), [
            'resizedImage' => $invalidBase64,
        ]);

        $response->assertSessionHasErrors('file');
    }
}
