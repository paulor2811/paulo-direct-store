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
