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
}