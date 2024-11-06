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
    public function jsonResponse(string $message, $data = [], int $statusCode = 200)
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
    public function displayByTable(string $model, string $relation, int $id) {
        $data = $model::with($relation)->findOrFail($id);
        $relatedData = $data->$relation()->get();

        return $this->jsonResponse('Datos obtenidos exitosamente', $relatedData, 200);
    }

    /**
     * Formats and returns custom data structure for NumGraduates with associated details.
     * 
     * @param \Illuminate\Database\Eloquent\Collection $numGraduates The collection of NumGraduate models.
     * 
     * @return \Illuminate\Support\Collection
     */
    public function displayCustomNumGraduatesData($numGraduates) {
        $numGraduatesData = $numGraduates->map(function($graduate) {
            return [
                'id' => $graduate->id,
                'quantity' => $graduate->quantity,
                'year' => $graduate->year,
                'career' => [
                    'id' => $graduate->career->id,
                    'name' => $graduate->career->name,
                ],
                'faculty' => [
                    'id' => $graduate->faculty->id,
                    'name' => $graduate->faculty->name,
                ],
                'campus' => [
                    'id' => $graduate->campus->id,
                    'name' => $graduate->campus->name,
                ]
            ];
        });

        return $numGraduatesData;
    }

    /**
     * Formats and returns custom data structure for Campus with graduate information.
     * 
     * @param \Illuminate\Database\Eloquent\Collection $campus The collection of Campus models.
     * 
     * @return \Illuminate\Support\Collection
     */
    public function displayCustomCampusData($campus) {
        $campusData = $campus->map(function($campus) {
            return [
                'id' => $campus->id,
                'name' => $campus->name,
                'graduates_info' => [
                    'total_graduates' => $campus->graduates->sum('quantity'),
                    'by_year' => $campus->graduates
                        ->groupBy('year')
                        ->map(function($yearGroup) {
                            return [
                                'quantity' => $yearGroup->sum('quantity')
                            ];
                        })
                ]
            ];
        });

        return $campusData;
    }
}
