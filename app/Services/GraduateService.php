<?php 

namespace App\Services;

use App\Http\Requests\NumGraduatesRequest;
use App\Models\NumGraduate;
use App\Models\Campu;
use App\Models\Career;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class GraduateService
{
    /**
     * Retrieves related data from a specific table through a model.
     *
     * @param string $model The model class to query.
     * @param string $relation The relationship to load.
     * @param int $id The primary key of the model to retrieve.
     */
    public function numGraduateRelatedData(string $relation, int $id) {
        $data = NumGraduate::with($relation)->findOrFail($id);
        return $data->$relation()->get();
    }

    /**
     * Verify if there are filters in the request
     * 
     * @param Builder $query
     * @param Request $request
     * @return Builder
     */
    public function verifyFilter(Builder $query, NumGraduatesRequest $request):Builder {
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }
        if ($request->filled('campus_id')) {
            $query->where('campus_id', $request->campus_id);
        }
        if ($request->filled('career_id')) {
            $query->where('career_id', $request->career_id);
        }

        return $query;
    }
    
    /**
     * Verify if exists campus and career 
     * if it exists, it return true, otherwise a exception is thrown.
     * 
     * @param Campu $campus
     * @param Career $career
     * @return bool
     */
    public function ifExistsCampusAndCareer(Campu $campus, Career $career):bool {
        return (!$campus || !$career) ? throw new \Exception('Campus o carrera no encontrados', 422) : true;
    }

    /**
     * verify that a record does not exist
     * if it doesn't exist, it return true, atherwise a exception is thrown.
     * 
     * @param mixed $record
     * @return bool
     */
    public function ifNotExistsRecord(NumGraduatesRequest $request):bool {
        $record = NumGraduate::where([
            'year' => $request['year'],
            'campus_id' => $request['campus_id'],
            'career_id' => $request['career_id']
        ])->first();

        return $record ? throw new \Exception('Registro duplicado', 409) : true;
    }

    public function updateRecord(NumGraduatesRequest $request, $record):void { 
        $record->update([
            'quantity' => $request->quantity,
            'year' => $request->year,
            'campus_id' => $request->campus_id,
            'career_id' => $request->career_id
        ]);
    }

    /**
     * Formats and returns custom data structure for NumGraduates with associated details.
     *
     * @param NumGraduate $numGraduates
     * @return array
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
     * @param NumGraduate $graduate
     * @return array Datos formateados para la respuesta
     */
    public function formatNumGraduatedData($numGraduates)
    {
        return $numGraduates->map(function ($graduate) {
            return $this->formatGraduatedData($graduate);
        });
    }
}
