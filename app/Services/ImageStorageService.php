<?php

namespace App\Services;

use App\Models\MediaFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageStorageService
{
    /**
     * Upload a profile photo for a user
     */
    public function uploadProfilePhoto(Model $user, UploadedFile $file): MediaFile
    {

        // Delete existing profile photo if exists
        $this->deleteAllForModel($user, 'profile');

        $path = $this->generatePath('profiles', $user->id, 'profile', $file->extension());
        
        return $this->storeFile($user, $file, $path, 'profile');
    }

    /**
     * Upload a product image
     */
    public function uploadProductImage(Model $product, UploadedFile $file): MediaFile
    {

        // Generate unique filename for product images (allowing multiple)
        $filename = Str::random(10);
        $path = $this->generatePath('products', $product->id, $filename, $file->extension());
        
        return $this->storeFile($product, $file, $path, 'product_image');
    }

    /**
     * Delete a specific media file
     */
    public function deleteFile(MediaFile $mediaFile): bool
    {
        return DB::transaction(function () use ($mediaFile) {
            \Log::info('Deleting media file', [
                'id' => $mediaFile->id,
                'path' => $mediaFile->path,
                'disk' => $mediaFile->disk,
            ]);
            
            // Delete from S3
            try {
                Storage::disk($mediaFile->disk)->delete($mediaFile->path);
                \Log::info('File deleted from S3', ['path' => $mediaFile->path]);
            } catch (\Exception $e) {
                \Log::error('Failed to delete file from S3', [
                    'path' => $mediaFile->path,
                    'error' => $e->getMessage()
                ]);
            }

            // Soft delete the record
            $deleted = $mediaFile->delete();
            \Log::info('Media file soft-deleted from database', ['id' => $mediaFile->id, 'success' => $deleted]);
            
            return $deleted;
        });
    }

    /**
     * Delete all media files for a model
     */
    public function deleteAllForModel(Model $model, ?string $fileType = null): int
    {
        $query = MediaFile::forModel($model);

        if ($fileType) {
            $query->where('file_type', $fileType);
        }

        $files = $query->get();
        $deleted = 0;

        foreach ($files as $file) {
            if ($this->deleteFile($file)) {
                $deleted++;
            }
        }

        return $deleted;
    }

    /**
     * Force delete a media file (permanent)
     */
    public function forceDeleteFile(MediaFile $mediaFile): bool
    {
        return DB::transaction(function () use ($mediaFile) {
            // Delete from S3
            if ($mediaFile->exists()) {
                Storage::disk($mediaFile->disk)->delete($mediaFile->path);
            }

            // Permanently delete the record
            return $mediaFile->forceDelete();
        });
    }

    /**
     * Force delete all media files for a model (permanent)
     */
    public function forceDeleteAllForModel(Model $model): int
    {
        $files = MediaFile::withTrashed()->forModel($model)->get();
        $deleted = 0;

        foreach ($files as $file) {
            if ($this->forceDeleteFile($file)) {
                $deleted++;
            }
        }

        return $deleted;
    }

    /**
     * Get URL for a media file
     */
    public function getUrl(MediaFile $mediaFile, bool $temporary = false, int $minutes = 60): string
    {
        if ($temporary) {
            return $mediaFile->getTemporaryUrl($minutes);
        }

        return $mediaFile->url;
    }

    /**
     * Store file in S3 and create database record
     */
    protected function storeFile(Model $model, UploadedFile $file, string $path, string $fileType): MediaFile
    {
        return DB::transaction(function () use ($model, $file, $path, $fileType) {
            try {
                // Upload to S3 (bucket policy controls public access, not ACLs)
                $uploaded = Storage::disk('s3')->putFileAs(
                    dirname($path),
                    $file,
                    basename($path)
                );

                if (!$uploaded) {
                    throw new \Exception('Failed to upload file to S3');
                }

                \Log::info('File uploaded successfully to S3', [
                    'path' => $path,
                    'size' => $file->getSize(),
                ]);

                // Create database record
                return MediaFile::create([
                    'model_type' => get_class($model),
                    'model_id' => $model->id,
                    'file_type' => $fileType,
                    'disk' => 's3',
                    'path' => $path,
                    'filename' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'metadata' => [
                        'original_extension' => $file->getClientOriginalExtension(),
                        'uploaded_at' => now()->toIso8601String(),
                    ],
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to store file in S3', [
                    'path' => $path,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }
        });
    }

    /**
     * Generate S3 path for a file
     */
    protected function generatePath(string $directory, string $modelId, string $filename, string $extension): string
    {
        return sprintf('%s/%s/%s.%s', $directory, $modelId, $filename, $extension);
    }


    /**
     * Get all media files for a model
     */
    public function getMediaFiles(Model $model, ?string $fileType = null)
    {
        $query = $model->mediaFiles();

        if ($fileType) {
            $query->where('file_type', $fileType);
        }

        return $query->get();
    }
}
