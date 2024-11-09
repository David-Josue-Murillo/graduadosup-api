<?php 

namespace App\Services;

use App\Models\NumGraduate;
use App\Models\Campu;
use App\Models\Career;
use Illuminate\Http\Request;

class GraduateService
{
    public function verifyFilter(Request $request) {
        $query = NumGraduate::query();

        // Aplicar filtros opcionales si se proporcionan en el request
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

    public function updateGraduate(NumGraduate $graduate, Request $request):NumGraduate {
        $graduate->update([
            'quantity' => $request->quantity,
            'year' => $request->year,
            'campus_id' => $request->campus_id,
            'career_id' => $request->career_id
        ]);

        return $graduate;
    }
}
