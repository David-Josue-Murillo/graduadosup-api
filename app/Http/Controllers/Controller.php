<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    /**
     * Generates a standardized JSON response for the API.
     * 
     * @param string $message The main message of the response.
     * @param mixed $data The data to be returned in the response (array or object).
     * @param int $statusCode The HTTP status code (default is 200).
     * 
     * @return JsonResponse
     */
    protected function jsonResponse(string $message, $data = [], int $statusCode = 200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'status' => $statusCode
        ], $statusCode);
    }

    /**
     * Retrieves related data from a specific table through a model.
     * 
     * @param string $model The model class to query.
     * @param string $relation The relationship to load.
     * @param int $id The primary key of the model to retrieve.
     * 
     * @return JsonResponse
     */
    protected function displayByTable(string $model, string $relation, int $id) {
        $data = $model::with($relation)->findOrFail($id);
        $relatedData = $data->$relation()->get();

        return $this->jsonResponse('Datos obtenidos exitosamente', $relatedData, 200);
    }
}
