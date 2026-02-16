<?php

use Illuminate\Support\Facades\Route;
use Modules\Products\Http\Controllers\ProductsController;


// Cart Routes
Route::get('/cart', [Modules\Products\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [Modules\Products\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove/{id}', [Modules\Products\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/update/{id}', [Modules\Products\Http\Controllers\CartController::class, 'update'])->name('cart.update');

// Public resource routes (auth handled in controller)
Route::get('products/categories', [Modules\Products\Http\Controllers\CategoriesController::class, 'index'])->name('products.categories');
Route::resource('products', ProductsController::class)->names('products');

// Delete individual product image
Route::delete('products/{product}/images/{image}', [ProductsController::class, 'deleteImage'])
    ->middleware(['auth', 'verified'])
    ->name('products.images.delete');

// Product Review Routes (authenticated users only)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('products/{product}/reviews', [Modules\Products\Http\Controllers\ReviewController::class, 'store'])->name('products.reviews.store');
    Route::put('reviews/{review}', [Modules\Products\Http\Controllers\ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('reviews/{review}', [Modules\Products\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::patch('reviews/{review}/toggle-visibility', [Modules\Products\Http\Controllers\ReviewController::class, 'toggleVisibility'])
        ->middleware(['is_admin'])
        ->name('reviews.toggle-visibility');
});
