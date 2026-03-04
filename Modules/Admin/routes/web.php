<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\AdminProductController;
use Modules\Admin\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth', 'verified', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    
    // Products
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])->name('products.destroy');
    Route::patch('/products/{id}/toggle-status', [AdminProductController::class, 'toggleStatus'])->name('products.toggle-status');

    // Categories
    Route::get('/categories', [\Modules\Admin\Http\Controllers\AdminCategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [\Modules\Admin\Http\Controllers\AdminCategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [\Modules\Admin\Http\Controllers\AdminCategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{id}/edit', [\Modules\Admin\Http\Controllers\AdminCategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{id}', [\Modules\Admin\Http\Controllers\AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [\Modules\Admin\Http\Controllers\AdminCategoryController::class, 'destroy'])->name('categories.destroy');

    // Users
    Route::get('/users', [\Modules\Admin\Http\Controllers\AdminUserController::class, 'index'])->name('users.index');
    Route::patch('/users/{id}/toggle-ban', [\Modules\Admin\Http\Controllers\AdminUserController::class, 'toggleBan'])->name('users.toggle-ban');
    Route::patch('/users/{id}/silence', [\Modules\Admin\Http\Controllers\AdminUserController::class, 'silence'])->name('users.silence');
    Route::patch('/users/{id}/toggle-admin', [\Modules\Admin\Http\Controllers\AdminUserController::class, 'toggleAdmin'])->name('users.toggle-admin');
});
