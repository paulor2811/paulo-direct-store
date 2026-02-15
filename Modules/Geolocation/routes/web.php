<?php

use Illuminate\Support\Facades\Route;
use Modules\Geolocation\Http\Controllers\GeolocationController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('geolocations', GeolocationController::class)->names('geolocation');
});
