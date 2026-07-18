<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class UploadedImageOptimizer
{
    /**
     * Downscale and recompress a stored image when GD is available.
     * Existing oversized product/package/avatar uploads load much faster after this.
     */
    public static function optimize(string $path, string $disk = 'public', int $maxEdge = 1600, int $quality = 82): void
    {
        if ($path === '' || ! function_exists('imagecreatefromstring')) {
            return;
        }

        $storage = Storage::disk($disk);

        if (! $storage->exists($path)) {
            return;
        }

        $binary = $storage->get($path);

        if ($binary === null || $binary === '') {
            return;
        }

        $image = @imagecreatefromstring($binary);

        if ($image === false) {
            return;
        }

        $width = imagesx($image);
        $height = imagesy($image);

        if ($width < 1 || $height < 1) {
            imagedestroy($image);

            return;
        }

        $scale = min(1, $maxEdge / max($width, $height));
        $targetWidth = max(1, (int) round($width * $scale));
        $targetHeight = max(1, (int) round($height * $scale));

        if ($scale < 1) {
            $resized = imagecreatetruecolor($targetWidth, $targetHeight);

            if ($resized === false) {
                imagedestroy($image);

                return;
            }

            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
            imagefilledrectangle($resized, 0, 0, $targetWidth, $targetHeight, $transparent);
            imagealphablending($resized, true);
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
        }

        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $tmp = tempnam(sys_get_temp_dir(), 'elximg');

        if ($tmp === false) {
            imagedestroy($image);

            return;
        }

        $written = match (true) {
            in_array($extension, ['jpg', 'jpeg'], true) && function_exists('imagejpeg') => imagejpeg($image, $tmp, $quality),
            $extension === 'png' && function_exists('imagepng') => imagepng($image, $tmp, 6),
            $extension === 'webp' && function_exists('imagewebp') => imagewebp($image, $tmp, $quality),
            default => false,
        };

        imagedestroy($image);

        if ($written) {
            $storage->put($path, file_get_contents($tmp) ?: '');
        }

        @unlink($tmp);
    }
}
