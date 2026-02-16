<?php

namespace Modules\Products\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductReview extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'comment',
        'is_visible',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_visible' => 'boolean',
    ];

    protected static function booted()
    {
        // Global scope to hide invisible reviews for regular users
        static::addGlobalScope('visible', function (\Illuminate\Database\Eloquent\Builder $builder) {
            if (!auth()->check() || !auth()->user()->is_admin) {
                $builder->where('is_visible', true);
            }
        });
    }

    /**
     * Get the user who wrote the review
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product being reviewed
     */
    public function product()
    {
        return $this->belongsTo(Produto::class, 'product_id');
    }
}
