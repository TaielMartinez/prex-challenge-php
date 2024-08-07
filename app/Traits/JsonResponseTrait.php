<?php

namespace App\Traits;

trait JsonResponseTrait
{
    public function responseJson($data, $error = null, $statusCode = 200)
    {
        $response = [
            'data' => $data,
            'error' => $error,
        ];

        if (!empty($error) && $statusCode == 200) {
            $statusCode = 400;
        }

        return response()->json($response, $statusCode);
    }
}
