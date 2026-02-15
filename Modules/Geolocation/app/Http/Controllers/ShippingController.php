<?php

namespace Modules\Geolocation\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ShippingController extends Controller
{
    // Origin: Rua Elzira Bittencourt 55, Londrina
    private $originLat = -23.2658375;
    private $originLon = -51.1450477;
    private $pricePerKm = 2.00;

    public function calculate(Request $request)
    {
        $request->validate([
            "cep" => "required|string|size:8"
        ]);

        $cep = $request->input("cep");

        // 1. Get Destination Coordinates via BrasilAPI
        $response = Http::get("https://brasilapi.com.br/api/cep/v2/{$cep}");

        if ($response->failed()) {
            return response()->json(["error" => "CEP inválido ou não encontrado."], 404);
        }

        $data = $response->json();

        if (!isset($data["location"]["coordinates"]["latitude"])) {
             return response()->json(["error" => "Não foi possível localizar as coordenadas deste CEP."], 422);
        }

        $destLat = $data["location"]["coordinates"]["latitude"];
        $destLon = $data["location"]["coordinates"]["longitude"];

        // 2. Calculate Distance (Haversine Formula)
        $distanceKm = $this->calculateHaversine(
            $this->originLat, 
            $this->originLon, 
            $destLat, 
            $destLon
        );

        // 3. Calculate Price
        $price = $distanceKm * $this->pricePerKm;

        return response()->json([
            "distance_km" => round($distanceKm, 2),
            "price" => round($price, 2),
            "city" => $data["city"] ?? "",
            "state" => $data["state"] ?? ""
        ]);
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
