<?php

namespace App\Observers;

use App\Services\ImageStorageService;
use Modules\Products\Models\Produto;

class ProdutoObserver
{
    protected ImageStorageService $imageStorage;

    public function __construct(ImageStorageService $imageStorage)
    {
        $this->imageStorage = $imageStorage;
    }

    /**
     * Handle the Produto "deleting" event (soft delete).
     */
    public function deleting(Produto $produto): void
    {
        // Delete all media files when product is soft deleted
        $this->imageStorage->deleteAllForModel($produto);
    }

    /**
     * Handle the Produto "forceDeleting" event (hard delete).
     */
    public function forceDeleting(Produto $produto): void
    {
        // Permanently delete all media files when product is force deleted
        $this->imageStorage->forceDeleteAllForModel($produto);
    }
}
