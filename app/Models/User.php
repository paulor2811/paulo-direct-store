<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'cpf',
        'phone',
        'gender',
        'birthdate',
        'profile_photo_path',
        'is_admin',
        'is_banned',
        'silenced_until',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birthdate' => 'date',
            'is_admin' => 'boolean',
            'is_banned' => 'boolean',
            'silenced_until' => 'datetime',
        ];
    }

    /**
     * Check if user is currently silenced
     */
    public function is_silenced(): bool
    {
        if (!$this->silenced_until) {
            return false;
        }

        return $this->silenced_until->isFuture();
    }

    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    /**
     * Get all stores owned by this user
     */
    public function stores()
    {
        return $this->hasMany(\Modules\Stores\Models\Store::class);
    }

    /**
     * Get all media files for this user
     */
    public function mediaFiles()
    {
        return $this->morphMany(MediaFile::class, 'model');
    }

    /**
     * Get the user's profile photo
     */
    public function profilePhoto()
    {
        return $this->mediaFiles()->where('file_type', 'profile')->first();
    }

    /**
     * Get profile photo URL
     */
    public function getProfilePhotoUrlAttribute(): ?string
    {
        $photo = $this->profilePhoto();
        return $photo ? $photo->url : null;
    }

    /**
     * Get all product reviews by this user
     */
    public function productReviews()
    {
        return $this->hasMany(\Modules\Products\Models\ProductReview::class);
    }
}
