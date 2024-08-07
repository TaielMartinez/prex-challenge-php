<?php

namespace App\Traits;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

trait GhipyTrait
{
    private $client;
    private $apiUrl;
    private $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiUrl = env('GIPHY_API_URL');
        $this->apiKey = env('GIPHY_API_KEY');

        if (empty($this->apiUrl)) {
            throw new Exception('La URL de GIPHY no esta configurada correctamente');
        }

        if (empty($this->apiKey)) {
            throw new Exception('La KEY de GIPHY no esta configurada correctamente');
        }
    }

    /**
     * Obtiene un GIF específico por ID.
     * Utiliza caché para almacenar los resultados durante 1 día.
     *
     * @param string $ghipyId
     * @return array
     */
    public function ghipyById($ghipyId, $minutesCache = 1440)
    {
        $cacheKey = "giphy_getById_{$ghipyId}";

        // Comprueba si los datos están en caché
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            // Realiza la solicitud a la API de Giphy
            $response = $this->client->get($this->apiUrl . '/gifs/' . $ghipyId, [
                'query' => ['api_key' => $this->apiKey]
            ]);
        } catch (\Throwable $th) {
            if ($th->getCode() == 404) {
                $error = "Gif $ghipyId not exist";
            } else {
                $error = $th->getMessage();
            }

            return [
                'data' => null,
                'error' => $error,
            ];
        }

        // Verifica si la respuesta es exitosa
        if ($response->getStatusCode() == 200) {
            $data = json_decode($response->getBody(), true);
            $gifData = $data['data'];

            $result = [
                'data' => $gifData,
                'error' => null,
            ];

            // Guarda el resultado en caché durante 1 dia
            Cache::put($cacheKey, $result, $minutesCache);

            return $result;
        } else {
            // Devuelve el error de la api
            return [
                'data' => null,
                'error' => $response->getBody(),
            ];
        }
    }

    /**
     * Obtiene una lista de GIFs paginados según la consulta, límite y desplazamiento.
     * Utiliza caché para almacenar los resultados durante 1 hora.
     *
     * @param string $query
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function ghipyPaginate($query, $limit, $offset)
    {
        $cacheKey = "giphy_paginate_{$query}_{$limit}_{$offset}";

        // Comprueba si los datos están en caché
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        // Realiza la solicitud a la API de Giphy
        $response = $this->client->get($this->apiUrl . '/gifs/search', [
            'query' => [
                'api_key' => $this->apiKey,
                'q' => $query,
                'limit' => $limit,
                'offset' => $offset,
            ]
        ]);

        // Verifica si la respuesta es exitosa
        if ($response->getStatusCode() == 200) {
            $data = json_decode($response->getBody(), true);

            $result = [
                'data' => $data['data'],
                'error' => null,
            ];

            // Guarda el resultado en caché durante 1 hora
            Cache::put($cacheKey, $result, 60);

            return $result;
        } else {
            // Devuelve el error de la api
            return [
                'data' => null,
                'error' => $response->getBody(),
            ];
        }
    }
}
