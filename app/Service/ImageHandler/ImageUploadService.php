<?php 

namespace App\Service\ImageHandler;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ImageUploadService{


    public function saveImageFromUrl($imageUrl,$path): string
    {
        try {
            $ext = pathinfo($imageUrl, PATHINFO_EXTENSION) ?: 'jpg'; // Default to jpg if no extension

            // Generate a unique file name
            $filename = Str::uuid() . '.' . $ext;

            // Store the image
            $imageContent = file_get_contents($imageUrl);

            if ($imageContent === false) {
                throw new \Exception('Unable to fetch image content from URL.');
            }

            // Save the image to the storage disk
            Storage::disk('public')->put($path . '/' . $filename, $imageContent);

            // Return the file path relative to the public directory
            return 'storage/' . $path . '/' . $filename;

        } catch (\Exception $e) {
            // Log or handle the error as needed
            throw new \Exception('Error saving image: ' . $e->getMessage());
        }
    }
}