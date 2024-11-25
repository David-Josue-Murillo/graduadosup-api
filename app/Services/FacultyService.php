<?php 

namespace App\Services;

use App\Http\Requests\FacultyRequest;
use App\Models\Faculty;

class FacultyService 
{
    public function verifyFacultyExists(FacultyRequest $request):bool {
        $faculty = Faculty::where([
            'name' => $request->name
        ])->first();

        return $faculty ? throw new \Exception('Registro duplicado') : false;
    }

    /**
     * Format and return custom one data structure for faculty with careers information.
     *
     * @param Faculty $faculty
     * @return array
     */
    public function formatFacultyData(Faculty $faculty):array {
        return [
            'id' => $faculty->id,
            'name' => $faculty->name,
            'total_careers' => $faculty->careers->count(),
            'careers' => $faculty->careers->map(function($career){
                return [
                    'id' => $career->id,
                    'name' => $career->name
                ];
            })
        ];
    }

    /**
     * Format and return custom all data structure for faculty with careers information.
     *
     * @param mixed $faculties
     * @return mixed
     */
    public function formatFacultiesData($faculties){
        return $faculties->map(function($faculty){
            return $this->formatFacultyData($faculty);
        });
    }
}