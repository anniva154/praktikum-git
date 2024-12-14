<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class ShippingService
{
    public function getShippingRates($originCityId, $destinationCityId, $weight, $courier)
    {
        $apiKey = env('RAJAONGKIR_API_KEY');
        $url = 'https://api.rajaongkir.com/starter/cost';

        $response = Http::withHeaders([
            'key' => $apiKey,
        ])->post($url, [
            'origin' => $originCityId,
            'destination' => $destinationCityId,
            'weight' => $weight,
            'courier' => $courier,
        ]);

        if ($response->successful()) {
            return $response->json()['rajaongkir']['results'][0]['costs'];
        }

        return [];
    }
}
