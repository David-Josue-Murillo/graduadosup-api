<?php

namespace App\Http\Controllers;

abstract class Controller
{
    //
    public function jsonResponse($message, $data = [], $statusCode = 200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'status' => $statusCode
        ], $statusCode);
    }

    public function displayByTable($model, $table, $id) {
        $data = $model::with($table)->findOrFail($id);
        return $data->$table()->get();
    }

    public function displayDataCampus($campus) {
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
