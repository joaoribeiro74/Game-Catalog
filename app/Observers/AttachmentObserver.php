<?php

namespace App\Observers;

use App\Models\Attachment;

class AttachmentObserver
{
    public function deleted(Attachment $attachment)
    {
        $filePath = public_path('storage/attachments/' . $attachment->filepath);

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
