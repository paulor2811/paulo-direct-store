<?php

use Illuminate\Support\Facades\Route;
use Modules\Geolocation\Http\Controllers\GeolocationController;
use Modules\Geolocation\Http\Controllers\ShippingController;

Route::middleware(["auth:sanctum"])->prefix("v1")->group(function () {
    Route::apiResource("geolocations", GeolocationController::class)->names("geolocation");
});

// Shipping Calculator Route
Route::post("geolocation/calculate-shipping", [ShippingController::class, "calculate"]);
