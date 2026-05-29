<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageService
{
    /**
     * Compress and resize an image, then store it.
     *
     * @param UploadedFile $file The uploaded image file.
     * @param string $path The directory path where the image should be stored (e.g. 'news/thumbnails').
     * @param int|null $maxWidth The maximum width of the image. Will retain aspect ratio.
     * @param int $quality The quality of the saved image (0-100).
     * @return string The path to the stored image, relative to the disk.
     */
    public function processAndStore(UploadedFile $file, string $path, ?int $maxWidth = 1200, int $quality = 80): string
    {
        $manager = new ImageManager(new Driver());
        $image = $manager->decode($file->getRealPath());

        if ($maxWidth !== null && $image->width() > $maxWidth) {
            $image->scaleDown(width: $maxWidth);
        }

        $filename = uniqid() . '_' . time() . '.webp'; // Convert to webp for better compression
        $fullPath = $path . '/' . $filename;

        // Ensure directory exists in public disk
        if (!Storage::disk('public')->exists($path)) {
            Storage::disk('public')->makeDirectory($path);
        }

        // Save the optimized image
        $encoded = $image->encode(new \Intervention\Image\Encoders\WebpEncoder($quality));
        Storage::disk('public')->put($fullPath, $encoded->toString());

        return $fullPath;
    }
}
