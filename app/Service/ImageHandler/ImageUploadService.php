<?php 

namespace App\Service\ImageHandler;
use Illuminate\Support\Facades\Storage;

class ImageUploadService{


    public function saveImageFromUrl(string $url, string $path): string
    {
        // Initialize cURL session
        $ch = curl_init($url);
        
        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        
        // Execute cURL session
        $imageContent = curl_exec($ch);
        
        // Get the content type of the response
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        
        // Close the cURL session
        curl_close($ch);

        // Check if the content was successfully retrieved
        if ($imageContent === false) {
            throw new \Exception('Nuk mund të merrni përmbajtjen e imazhit nga URL.');
        }

        // Check if the content type is an image
        if (strpos($contentType, 'image/') !== 0) {
            throw new \Exception('URL nuk përmban një imazh të vlefshëm.');
        }

        // Determine the image extension from the content type
        $imageExtension = substr($contentType, strpos($contentType, '/') + 1); // Get the extension after "image/"

        // Create a unique name for the image
        $imageName = 'image_' . uniqid() . '.' . $imageExtension; // Create a unique file name

        // Define the full path where the image will be stored
        $pathImage = $path . '/' . $imageName;

        // Store the image content in the specified path
        Storage::disk('public')->put($pathImage, $imageContent);

        // Return the path to the stored image
        return 'storage/' . $pathImage;
    }
}