<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Storage;

if (! function_exists('storage_public_url')) {
    /**
     * Full public URL for a path on the "public" filesystem disk.
     * Uses APP_URL/storage locally, or the S3/R2 URL when FILESYSTEM_PUBLIC_DRIVER=s3.
     */
    function storage_public_url(?string $path): string
    {
        if ($path === null || $path === '') {
            return '';
        }

        return Storage::disk('public')->url(ltrim($path, '/'));
    }
}
