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
        'categoria_produto_id',
        'created_at',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->created_at)) {
                $model->created_at = time();
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
     * Get all media files for this product
     */
    public function mediaFiles()
    {
        return $this->morphMany(\App\Models\MediaFile::class, 'model');
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
}
