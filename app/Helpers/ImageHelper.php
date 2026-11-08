<?php

namespace App\Helpers;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Exception;

class ImageHelper
{
    /**
     * Process and optimize uploaded image
     * 
     * @param UploadedFile $file
     * @param string $disk
     * @param string $path
     * @param int $maxWidth Maximum width in pixels (default: 1920)
     * @param int $quality JPEG quality 0-100 (default: 85)
     * @return string|null Path to saved image or null on failure
     */
    public static function processAndStore(
        UploadedFile $file,
        string $disk = 'public',
        string $path = 'images',
        int $maxWidth = 1920,
        int $quality = 85
    ): ?string {
        try {
            // Create image manager with GD driver
            $manager = new ImageManager(new Driver());
            
            // Read image from uploaded file
            $image = $manager->read($file->getRealPath());
            
            // Get original dimensions
            $width = $image->width();
            $height = $image->height();
            
            // Resize if image is larger than maxWidth
            if ($width > $maxWidth) {
                $image->scaleDown(width: $maxWidth);
            }
            
            // Get file extension and mime type
            $extension = strtolower($file->getClientOriginalExtension());
            $mimeType = $file->getMimeType();
            
            // Generate unique filename
            $filename = uniqid() . '_' . time() . '.' . $extension;
            $fullPath = $path . '/' . $filename;
            
            // Determine storage path
            $storagePath = Storage::disk($disk)->path($fullPath);
            
            // Ensure directory exists
            $directory = dirname($storagePath);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // Save image with optimization
            if (in_array($extension, ['jpg', 'jpeg'])) {
                // JPEG: save with quality compression
                $image->toJpeg($quality)->save($storagePath);
            } elseif ($extension === 'png') {
                // PNG: save as PNG (preserve transparency if exists)
                // For better compression, we could convert to JPEG if no transparency
                // but for simplicity, we'll keep PNG format
                $image->toPng()->save($storagePath);
            } elseif ($extension === 'gif') {
                // GIF: save as is (animated GIFs should not be processed)
                $image->toGif()->save($storagePath);
            } else {
                // Fallback: save as JPEG
                $filename = str_replace('.' . $extension, '.jpg', $filename);
                $fullPath = $path . '/' . $filename;
                $storagePath = Storage::disk($disk)->path($fullPath);
                $image->toJpeg($quality)->save($storagePath);
            }
            
            return $fullPath;
            
        } catch (Exception $e) {
            // Log error and return null
            \Log::error('Image processing failed: ' . $e->getMessage(), [
                'file' => $file->getClientOriginalName(),
                'error' => $e->getTraceAsString()
            ]);
            
            // Fallback: store original file if processing fails
            try {
                return $file->store($path, $disk);
            } catch (Exception $fallbackException) {
                \Log::error('Fallback storage also failed: ' . $fallbackException->getMessage());
                return null;
            }
        }
    }
    
    /**
     * Process multiple images
     * 
     * @param array $files Array of UploadedFile objects
     * @param string $disk
     * @param string $path
     * @param int $maxWidth
     * @param int $quality
     * @return array Array of stored image paths
     */
    public static function processMultiple(
        array $files,
        string $disk = 'public',
        string $path = 'images',
        int $maxWidth = 1920,
        int $quality = 85
    ): array {
        $processed = [];
        
        foreach ($files as $file) {
            if ($file instanceof UploadedFile && $file->isValid()) {
                $result = self::processAndStore($file, $disk, $path, $maxWidth, $quality);
                if ($result) {
                    $processed[] = $result;
                }
            }
        }
        
        return $processed;
    }
}

