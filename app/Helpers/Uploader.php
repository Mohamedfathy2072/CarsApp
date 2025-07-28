<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class Uploader
{
    public static function upload(?UploadedFile $file, string $folder, ?string $oldFile = null): ?string
    {
        if (!$file) return $oldFile;

        if ($oldFile && Storage::disk('public')->exists($oldFile)) {
            Storage::disk('public')->delete($oldFile);
        }

        return $file->store($folder, 'public');
    }
}
