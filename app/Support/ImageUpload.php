<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Menyimpan gambar ke disk "public" (butuh `php artisan storage:link`).
 * Path yang disimpan relatif terhadap public/, mis. "storage/uploads/abc.jpg".
 */
class ImageUpload
{
    public static function store(UploadedFile $file): string
    {
        return 'storage/'.$file->store('uploads', 'public');
    }

    public static function delete(?string $path): void
    {
        if ($path && str_starts_with($path, 'storage/uploads/')) {
            Storage::disk('public')->delete(substr($path, strlen('storage/')));
        }
    }
}
