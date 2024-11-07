<?php 

namespace App\Services;

use App\Models\NumGraduate;
use App\Models\Campu;
use App\Models\Career;

class GraduateService
{
    public function createGraduate(array $data)
    {
        $campus = Campu::find($data['campus_id']);
        $career = Career::find($data['career_id']);

        if (!$campus || !$career) {
            throw new \Exception('Campus o carrera no encontrados', 422);
        }

        $existingRecord = NumGraduate::where([
            'year' => $data['year'],
            'campus_id' => $data['campus_id'],
            'career_id' => $data['career_id']
        ])->first();

        if ($existingRecord) {
            throw new \Exception('Registro duplicado', 409);
        }

        return NumGraduate::create($data);
    }
}
