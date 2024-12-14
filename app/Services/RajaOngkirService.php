<?php
namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class RajaOngkirService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('services.rajaongkir.key');
    }

    // Mendapatkan daftar provinsi
    public function getProvinces()
    {
        try {
            // Menggunakan cache untuk provinsi
            $cache = Cache::get('raja_ongkir_provinces');

            if (!$cache) {
                // Jika data provinsi tidak ada di cache, ambil dari API
                $response = $this->client->request('GET', 'https://api.rajaongkir.com/starter/province', [
                    'headers' => ['key' => $this->apiKey]
                ]);

                // Cache respons selama 24 jam
                $cache = $response->getBody()->getContents();
                Cache::put('raja_ongkir_provinces', $cache, 1440); // 1440 menit = 24 jam
            }

            // Mengembalikan data provinsi dari cache
            $data = json_decode($cache, true);
            return $data['rajaongkir']['results'];
        } catch (\Exception $e) {
            throw new \Exception("Gagal mengambil data provinsi: " . $e->getMessage());
        }
    }

    // Mendapatkan daftar kota berdasarkan provinsi
    public function getCities(int $provinceId)
    {
        try {
            // Menggunakan cache untuk kota berdasarkan provinsi
            $cache = Cache::get('raja_ongkir_cities_' . $provinceId);

            if (!$cache) {
                // Jika data kota tidak ada di cache, ambil dari API
                $response = $this->client->request('GET', 'https://api.rajaongkir.com/starter/city', [
                    'headers' => ['key' => $this->apiKey],
                    'query' => ['province' => $provinceId]
                ]);

                // Cache respons selama 24 jam
                $cache = $response->getBody()->getContents();
                Cache::put('raja_ongkir_cities_' . $provinceId, $cache, 1440);
            }

            // Mengembalikan data kota dari cache
            $data = json_decode($cache, true);
            return $data['rajaongkir']['results'];
        } catch (\Exception $e) {
            throw new \Exception("Gagal mengambil data kota: " . $e->getMessage());
        }
    }

    // Menghitung biaya pengiriman
    public function getShippingCost($origin, $destination, $weight, $courier)
{
    try {
        // Validasi apakah origin sudah terisi dengan benar
        if (empty($origin)) {
            throw new \Exception('Origin harus diisi dengan benar.');
        }

        // Menyusun key cache berdasarkan kombinasi origin, destination, weight, dan courier
        $dataKey = $origin . $destination . $weight . $courier;
        $cache = Cache::get('raja_ongkir_cost_' . $dataKey);

        if (!$cache) {
            // Jika data ongkir tidak ada di cache, ambil dari API
            $response = $this->client->request('POST', 'https://api.rajaongkir.com/starter/cost', [
                'headers' => ['key' => $this->apiKey],
                'form_params' => [
                    'origin' => $origin,
                    'destination' => $destination,
                    'weight' => $weight,
                    'courier' => $courier
                ]
            ]);

            // Cache respons selama 24 jam
            $cache = $response->getBody()->getContents();
            Cache::put('raja_ongkir_cost_' . $dataKey, $cache, 1440);
        }

        // Mengembalikan data ongkir dari cache
        $data = json_decode($cache, true);
        return $data['rajaongkir'];
    } catch (\Exception $e) {
        throw new \Exception("Gagal menghitung ongkir: " . $e->getMessage());
    }
}

}
