<?php 

namespace App\Services;

use App\Models\NumGraduate;
use App\Models\Campu;
use App\Models\Career;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class GraduateService
{
    /**
     * Verify if there are filters in the request
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return Builder
     */
    public function verifyFilter(Builder $query, Request $request):Builder {
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
     * @param \App\Models\Campu $campus
     * @param \App\Models\Career $career
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
    public function ifNotExistsRecord($record):bool {
        return $record ? throw new \Exception('Registro duplicado', 409) : true;
    }

}
