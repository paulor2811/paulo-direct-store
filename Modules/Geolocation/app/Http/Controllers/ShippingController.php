<?php

namespace Modules\Geolocation\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ShippingController extends Controller
{
    // Origin: Rua Elzira Bittencourt 55, Londrina
    private $originLat;
    private $originLon;
    private $pricePerKm;

    public function __construct()
    {
        // Default fallbacks are no longer needed globally,
        // but we'll keep the empty constructor for structure.
    }

    // Fallback coordinates for local cities if CEP geocoding fails
    private $cityFallbacks = [
        'Londrina' => ['lat' => -23.3102, 'lon' => -51.1628],
        'Cambé' => ['lat' => -23.2758, 'lon' => -51.2783],
        'Ibiporã' => ['lat' => -23.2692, 'lon' => -51.0464],
        'Rolândia' => ['lat' => -23.3117, 'lon' => -51.3692],
        'Arapongas' => ['lat' => -23.4111, 'lon' => -51.4236],
        'Apucarana' => ['lat' => -23.5517, 'lon' => -51.4614],
        'Sertanópolis' => ['lat' => -23.0583, 'lon' => -51.0361],
        'Bela Vista do Paraíso' => ['lat' => -22.9972, 'lon' => -51.1908],
        'Jataizinho' => ['lat' => -23.2536, 'lon' => -50.9786],
        'Tamarana' => ['lat' => -23.7214, 'lon' => -51.0967],
    ];

    public function calculate(Request $request)
    {
        $request->validate([
            "cep" => "required|string|size:8",
            "product_id" => "nullable|string|exists:produtos,id"
        ]);

        $cep = $request->input("cep");
        $productId = $request->input("product_id");

        // 1. Get Address via BrasilAPI
        $response = Http::get("https://brasilapi.com.br/api/cep/v2/{$cep}");

        if ($response->failed()) {
            return response()->json(["error" => "CEP inválido ou não encontrado."], 404);
        }

        $data = $response->json();
        
        // Prepare address data
        $addressData = [
            "street" => $data["street"] ?? "",
            "neighborhood" => $data["neighborhood"] ?? "",
            "city" => $data["city"] ?? "",
            "state" => $data["state"] ?? ""
        ];

        // 2. Try to get coordinates (CEP v2 or City Fallback)
        $destLat = null;
        $destLon = null;

        if (isset($data["location"]["coordinates"]["latitude"]) && $data["location"]["coordinates"]["latitude"] != 0) {
            $destLat = $data["location"]["coordinates"]["latitude"];
            $destLon = $data["location"]["coordinates"]["longitude"];
        } elseif (isset($addressData['city']) && isset($this->cityFallbacks[$addressData['city']])) {
            $destLat = $this->cityFallbacks[$addressData['city']]['lat'];
            $destLon = $this->cityFallbacks[$addressData['city']]['lon'];
        }

        // 3. Math (Only if product is provided and has shipping config)
        if ($productId) {
            $product = \Modules\Products\Models\Produto::find($productId);
            
            if ($product && $product->shipping_price_per_km && $product->shipping_origin_lat && $product->shipping_origin_lon) {
                if ($destLat && $destLon) {
                    $distanceKm = $this->calculateHaversine(
                        $product->shipping_origin_lat, 
                        $product->shipping_origin_lon, 
                        $destLat, 
                        $destLon
                    );

                    $addressData["distance_km"] = round($distanceKm, 2);
                    $addressData["price"] = round($distanceKm * $product->shipping_price_per_km, 2);
                } else {
                    $addressData["distance_km"] = null;
                    $addressData["price"] = null;
                    $addressData["geocoding_failed"] = true;
                }
                
                return response()->json($addressData);
            }
        }

        // Return only the address info if strictly used for autocomplete or if product doesn't have shipping setup
        return response()->json($addressData);
    }

    private function calculateHaversine($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
