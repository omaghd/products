<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class FileUploadService
{
    public function uploadFile(UploadedFile $file, string $savePath): string
    {
        return str_replace(
            'public',
            'storage',
            $file->storePublicly($savePath)
        );
    }
}
