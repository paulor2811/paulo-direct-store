<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class MediaFile extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'model_type',
        'model_id',
        'file_type',
        'disk',
        'path',
        'filename',
        'mime_type',
        'size',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'size' => 'integer',
    ];

    /**
     * Get the owning model (User, Produto, etc)
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the full URL for this media file
     */
    public function getUrlAttribute(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    /**
     * Get a temporary signed URL (valid for 1 hour by default)
     */
    public function getTemporaryUrl(int $minutes = 60): string
    {
        return Storage::disk($this->disk)->temporaryUrl(
            $this->path,
            now()->addMinutes($minutes)
        );
    }

    /**
     * Check if file exists in storage
     */
    public function exists(): bool
    {
        return Storage::disk($this->disk)->exists($this->path);
    }

    /**
     * Get human-readable file size
     */
    public function getHumanSizeAttribute(): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    /**
     * Scope: Get only profile photos
     */
    public function scopeProfilePhotos($query)
    {
        return $query->where('file_type', 'profile');
    }

    /**
     * Scope: Get only product images
     */
    public function scopeProductImages($query)
    {
        return $query->where('file_type', 'product_image');
    }

    /**
     * Scope: For a specific model
     */
    public function scopeForModel($query, $model)
    {
        return $query->where('model_type', get_class($model))
                     ->where('model_id', $model->id);
    }
}
