<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;


class RajaOngkirController extends Controller
{
    public function provinces()
    {
        try {
            $response = Http::withHeaders([
                'key' => '93e1521f3008e2d37bce9631315c8a14', // Ganti dengan API Key RajaOngkir
            ])->get('https://api.rajaongkir.com/starter/province');

            if ($response->successful()) {
                return response()->json($response['rajaongkir']['results']);
            }

            return response()->json(['error' => 'Gagal memuat data provinsi'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Kesalahan: ' . $e->getMessage()], 500);
        }
    }

    // Get list of cities by province ID
    public function cities($provinceId)
{
    try {
        $response = Http::withHeaders([
            'key' => '93e1521f3008e2d37bce9631315c8a14',
        ])->get("https://api.rajaongkir.com/starter/city?province={$provinceId}");

        if ($response->successful()) {
            $cities = $response['rajaongkir']['results'];
            \Log::info('Cities for province ' . $provinceId . ':', $cities);  // Log data kota untuk debugging
            return response()->json($cities);
        }

        return response()->json(['error' => 'Gagal memuat data kota'], 500);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Kesalahan: ' . $e->getMessage()], 500);
    }
}

public function cekOngkir(Request $request)
{
    $request->validate([
        'origin' => 'required|string',
        'destination' => 'required|string',
        'weight' => 'required|integer|min:1',
        'courier' => 'required|string',
    ]);

    // Pastikan origin sudah terisi dan valid
    $origin = $request->origin;
    if (empty($origin)) {
        return response()->json(['error' => 'Origin harus diisi dengan benar.'], 400);
    }

    // Generate key unik berdasarkan parameter untuk caching
    $cacheKey = "cek_ongkir_{$origin}_{$request->destination}_{$request->weight}_{$request->courier}";

    $ongkir = Cache::remember($cacheKey, 60, function () use ($request) {
        $response = Http::withHeaders([
            'key' => '93e1521f3008e2d37bce9631315c8a14',
        ])->post('https://api.rajaongkir.com/starter/cost', [
            "origin" => $request->origin,
            "destination" => $request->destination,
            "weight" => $request->weight,
            "courier" => $request->courier,
        ]);

        if ($response->successful()) {
            return $response->json()['rajaongkir']['results'];
        }

        return null; // Jika gagal, kembalikan null
    });

    if ($ongkir) {
        return response()->json($ongkir);
    } else {
        return response()->json(['error' => 'Gagal memuat ongkos kirim.'], 500);
    }
}


}
