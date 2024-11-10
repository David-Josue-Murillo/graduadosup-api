<?php

namespace App\Services;

use App\Models\NumGraduate;

class GraduateDataFormatterService
{
    /**
     * Formats and returns custom data structure for NumGraduates with associated details.
     * 
     * @param \Illuminate\Database\Eloquent\Collection $numGraduates The collection of NumGraduate models.
     * 
     * @return \Illuminate\Support\Collection
     */
    public function formatGraduatedData(NumGraduate $graduate): array
    {
        return [
            'id' => $graduate->id,
            'quantity' => $graduate->quantity,
            'year' => $graduate->year,
            'campus' => [
                'id' => $graduate->campus->id,
                'name' => $graduate->campus->name,
            ],
            'career' => [
                'id' => $graduate->career->id,
                'name' => $graduate->career->name,
                'faculty' => [
                    'id' => $graduate->career->faculty->id,
                    'name' => $graduate->career->faculty->name,
                ]
            ]
        ];
    }

    /**
     * Format all data for the number of graduates
     *
     * @param \App\Models\NumGraduate $graduate
     * @return array Datos formateados para la respuesta
     */
    public function formatNumGraduatedData($numGraduates)
    {
        return $numGraduates->map(function ($graduate) {
            return $this->formatGraduatedData($graduate);
        });
    }
}
