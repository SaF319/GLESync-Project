<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GoogleMapsService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.google_maps.key');
    }

    public function obtenerCoordenadas($direccion)
    {
        $url = "https://maps.googleapis.com/maps/api/geocode/json";
/* se deja asi hasta que se soluciones tema certificados de API
        $response = Http::get($url, [
            'address' => $direccion,
            'key' => $this->apiKey,
        ]);
*/
$response = Http::withoutVerifying()->get(
    'https://maps.googleapis.com/maps/api/geocode/json',
    [
        'address' => $direccion,
        'key' => $this->apiKey,
    ]
);

        $data = $response->json();

        if ($data['status'] === 'OK') {
            $location = $data['results'][0]['geometry']['location'];
            return [
                'lat' => $location['lat'],
                'lng' => $location['lng'],
            ];
        }

        return null;
    }
}
