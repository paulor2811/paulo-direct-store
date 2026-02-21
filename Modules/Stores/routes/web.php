<?php

use Illuminate\Support\Facades\Route;
use Modules\Stores\Http\Controllers\StoresController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('stores/set-active', [StoresController::class, 'setActive'])->name('stores.setActive');
    Route::resource('stores', StoresController::class)->names('stores');
});

Route::get('loja/{id}', [StoresController::class, 'show'])->name('stores.public.show');
