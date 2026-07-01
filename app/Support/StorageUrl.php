<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class StorageUrl
{
    public static function asset(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $normalized = ltrim(str_replace('\\', '/', $path), '/');

        return asset('storage/'.$normalized);
    }

    public static function exists(?string $path): bool
    {
        if ($path === null || $path === '') {
            return false;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return true;
        }

        return Storage::disk('public')->exists(ltrim(str_replace('\\', '/', $path), '/'));
    }
}
