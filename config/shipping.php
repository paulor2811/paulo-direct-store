<?php

return [
    'origin' => [
        'lat' => env('SHIPPING_ORIGIN_LAT', -23.2658375),
        'lon' => env('SHIPPING_ORIGIN_LON', -51.1450477),
    ],
    'price_per_km' => env('SHIPPING_PRICE_PER_KM', 2.00),
];
