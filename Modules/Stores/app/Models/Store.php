<?php

namespace Modules\Stores\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use Modules\Products\Models\Produto;

class Store extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'user_id',
        'nome',
        'username',
        'endereco',
        'telefone_fixo',
        'whatsapp',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function produtos()
    {
        return $this->hasMany(Produto::class);
    }

    /**
     * Get all media files for this store
     */
    public function mediaFiles()
    {
        return $this->morphMany(\App\Models\MediaFile::class, 'model');
    }

    /**
     * Get the store logo
     */
    public function logo()
    {
        return $this->mediaFiles()->where('file_type', 'store_logo')->first();
    }

    /**
     * Get logo URL
     */
    public function getLogoUrlAttribute(): ?string
    {
        $logo = $this->logo();
        return $logo ? $logo->url : null;
    }
}
