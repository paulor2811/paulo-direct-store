<?php

use Illuminate\Support\Facades\Route;
use Modules\Stores\Http\Controllers\StoresController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('stores', StoresController::class)->names('stores');
});
