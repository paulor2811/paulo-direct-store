<?php

namespace Modules\Products\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

use Illuminate\Database\Eloquent\SoftDeletes;

class Produto extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'produtos';
    public $timestamps = false;

    protected $fillable = [
        'nome',
        'descricao',
        'marca',
        'modelo',
        'cor',
        'preco',
        'condicao',
        'is_active',
        'categoria_produto_id',
        'store_id',
        'user_id',
        'created_at',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->created_at)) {
                $model->created_at = time();
            }
        });

        // Global scope to hide inactive products for regular users
        static::addGlobalScope('active', function (\Illuminate\Database\Eloquent\Builder $builder) {
            if (!auth()->check() || !auth()->user()->is_admin) {
                $builder->where('is_active', true);
            }
        });
    }

    public function categoria()
    {
        return $this->belongsTo(CategoriaProduto::class, 'categoria_produto_id');
    }

    public function fotos()
    {
        return $this->hasMany(FotoProduto::class, 'produto_id');
    }

    /**
     * Get the store this product belongs to (if any)
     */
    public function store()
    {
        return $this->belongsTo(\Modules\Stores\Models\Store::class);
    }

    /**
     * Get the user who owns this product
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Get all media files for this product
     */
    public function mediaFiles()
    {
        return $this->morphMany(\App\Models\MediaFile::class, 'model');
    }

    /**
     * Alias for mediaFiles (snake_case version)
     */
    public function media_files()
    {
        return $this->mediaFiles();
    }

    /**
     * Get product images from S3
     */
    public function images()
    {
        return $this->mediaFiles()->where('file_type', 'product_image')->get();
    }

    /**
     * Get main/first product image URL
     */
    public function getMainImageUrlAttribute(): ?string
    {
        $image = $this->mediaFiles()->where('file_type', 'product_image')->first();
        return $image ? $image->url : null;
    }

    /**
     * Get all reviews for this product
     */
    public function reviews()
    {
        return $this->hasMany(ProductReview::class, 'product_id');
    }

    /**
     * Get average rating
     */
    public function getAverageRatingAttribute(): float
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    /**
     * Get total reviews count
     */
    public function getReviewsCountAttribute(): int
    {
        return $this->reviews()->count();
    }
}
