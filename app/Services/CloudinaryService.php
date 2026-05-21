<?php

namespace App\Services;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    /**
     * Upload an image or file to Cloudinary.
     *
     * @param mixed $file (UploadedFile or base64 string)
     * @param string $folder
     * @return string|null The secure URL of the uploaded file
     */
    public function uploadFile($file, string $folder = 'st_michel/uploads')
    {
        try {
            if ($file instanceof UploadedFile) {
                $result = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                    'folder' => $folder,
                    'resource_type' => 'auto'
                ]);
            } else {
                // Assume base64 string
                $result = Cloudinary::uploadApi()->upload($file, [
                    'folder' => $folder,
                    'resource_type' => 'auto'
                ]);
            }
            
            return $result['secure_url'];
        } catch (\Exception $e) {
            Log::error('Cloudinary Upload Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Upload a video to Cloudinary.
     *
     * @param UploadedFile $file
     * @param string $folder
     * @return string|null The secure URL of the uploaded video
     */
    public function uploadVideo(UploadedFile $file, string $folder = 'st_michel/videos')
    {
        try {
            $result = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                'folder' => $folder,
                'resource_type' => 'video'
            ]);
            
            return $result['secure_url'];
        } catch (\Exception $e) {
            Log::error('Cloudinary Video Upload Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete an asset from Cloudinary.
     * Note: Requires public_id which we should store in DB if we want clean deletions.
     * 
     * @param string $publicId
     * @return bool
     */
    public function deleteAsset(string $publicId)
    {
        try {
            Cloudinary::uploadApi()->destroy($publicId);
            return true;
        } catch (\Exception $e) {
            Log::error('Cloudinary Delete Error: ' . $e->getMessage());
            return false;
        }
    }
}
