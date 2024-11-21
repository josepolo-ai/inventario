<?php

namespace App\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

trait ConsumesExternalServices{
    
    public function performRequest($method, $requestUrl, $formParams = [], $headers = [])
    {
        $client = new Client([
            'base_uri' => $this->baseUri,
        ]);

        if(isset($this->secret)){
            $headers['Authorization'] = 'Bearer ' . $this->secret;
        }
        try {
            $response = $client->request($method, $requestUrl, ['form_params' => $formParams, 'headers' => $headers]);

            $statusCode = $response->getStatusCode();
            $responseBody = $response->getBody()->getContents();
            
            if ($statusCode === 200) {
                $data = json_decode($responseBody);
                return response()->json($data, 200);
            } else {
                return response()->json(['message' => 'Error en la solicitud a la API'], $statusCode);
            }
        } catch (RequestException $e) {
            return response()->json(['message' => 'Error en la solicitud a la API: ' . $e->getMessage()], 500);
        }
    }

}