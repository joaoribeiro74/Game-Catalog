<?php

namespace App\Observers;

use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;

class AttachmentObserver
{
    public function deleted(Attachment $attachment)
    {
        Storage::disk('public')->delete($attachment->filepath);

        // $filePath = public_path('storage/attachments/' . $attachment->filepath);

        // if (file_exists($filePath)) {
        //     unlink($filePath);
        // }
    }
}
