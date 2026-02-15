<?php

namespace App\Observers;

use App\Models\User;
use App\Services\ImageStorageService;

class UserObserver
{
    protected ImageStorageService $imageStorage;

    public function __construct(ImageStorageService $imageStorage)
    {
        $this->imageStorage = $imageStorage;
    }

    /**
     * Handle the User "deleting" event (soft delete).
     */
    public function deleting(User $user): void
    {
        // Delete all media files when user is soft deleted
        $this->imageStorage->deleteAllForModel($user);
    }

    /**
     * Handle the User "forceDeleting" event (hard delete).
     */
    public function forceDeleting(User $user): void
    {
        // Permanently delete all media files when user is force deleted
        $this->imageStorage->forceDeleteAllForModel($user);
    }
}
