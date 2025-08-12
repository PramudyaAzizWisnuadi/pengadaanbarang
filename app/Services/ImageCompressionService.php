<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;

class ImageCompressionService
{
    private const TARGET_SIZE = 500; // 500KB in KB
    private const MAX_WIDTH = 1920;
    private const MAX_HEIGHT = 1080;

    /**
     * Compress image to target size
     */
    public function compressImage(UploadedFile $file, string $directory = 'approval-photos'): string
    {
        // Generate unique filename
        $filename = time() . '_' . uniqid() . '.jpg';
        $path = $directory . '/' . $filename;

        // Create image manager
        $manager = new ImageManager(new Driver());

        // Create image instance
        $image = $manager->read($file);

        // Resize if too large
        if ($image->width() > self::MAX_WIDTH || $image->height() > self::MAX_HEIGHT) {
            $image->scale(width: self::MAX_WIDTH, height: self::MAX_HEIGHT);
        }

        // Start with quality 90
        $quality = 90;
        $compressed = null;

        do {
            // Encode with current quality
            $compressed = $image->toJpeg($quality);

            // Check file size (in KB)
            $sizeKB = strlen($compressed) / 1024;

            // If size is acceptable, break
            if ($sizeKB <= self::TARGET_SIZE) {
                break;
            }

            // Reduce quality
            $quality -= 10;
        } while ($quality >= 20 && $sizeKB > self::TARGET_SIZE);

        // If still too large, resize more aggressively
        if (strlen($compressed) / 1024 > self::TARGET_SIZE) {
            $image->scale(width: 1280, height: 720);
            $compressed = $image->toJpeg(60);
        }

        // Save to storage
        Storage::disk('public')->put($path, $compressed);

        return $path;
    }

    /**
     * Delete image file
     */
    public function deleteImage(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        return true;
    }

    /**
     * Get image size in KB
     */
    public function getImageSize(string $path): float
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->size($path) / 1024;
        }

        return 0;
    }
}
