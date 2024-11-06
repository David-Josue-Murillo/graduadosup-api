<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NumGraduatesRequest;
use App\Models\NumGraduate;
use Illuminate\Http\Request;

class GraduateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
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

        $numGraduates = $query->with(['campus', 'career', 'faculty'])->get();
        $numGraduatesData = $this->formatGraduatesData($numGraduates);

        return $numGraduates->isEmpty()
        ? $this->jsonResponse('No hay datos', [], 200)
        : $this->jsonResponse('Datos obtenidos exitosamente', $numGraduatesData, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NumGraduatesRequest $request)
    {
        $graduates = NumGraduate::create([
            'quantity' => $request->quantity,
            'year' => $request->year,
            'campus_id' => $request->campus_id,
            'career_id' => $request->career_id
        ]);

        return $this->jsonResponse("Dato creado exitosamente", $graduates, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $graduate_id)
    {
        $graduate = NumGraduate::with('campus', 'career', 'faculty')->findOrFail($graduate_id);
        $formattedGraduate = $this->formatGraduateData($graduate);

        return $this->jsonResponse('Dato obtenido exitosamente', $formattedGraduate, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(NumGraduatesRequest $request, int $graduate_id)
    {
        $graduate = NumGraduate::findOrFail($graduate_id);
        $graduate->update([
            'quantity' => $request->quantity,
            'year' => $request->year,
            'campus_id' => $request->campus_id,
            'career_id' => $request->career_id
        ]);

        return $this->jsonResponse('Dato actualizado exitosamente', $graduate, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $graduate_id)
    {
        $graduate = NumGraduate::findOrFail($graduate_id);
        $graduate->delete();
        return $this->jsonResponse('Dato eliminado exitosamente', $graduate, 200);
    }

    /**
     * Display the Campus of a specified graduate
     * @param \App\Models\NumGraduate $graduate
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function displayCampus(int $graduate_id) {
        return $this->displayByTable(NumGraduate::class, 'campus', $graduate_id);
    }

    /**
     * Display the career of a specified graduate
     * @param \App\Models\NumGraduate $graduate
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function displayCareer(int $graduate_id) {
        return $this->displayByTable(NumGraduate::class, 'career', $graduate_id);
    }

    /**
     * Display the faculty of a specified graduate
     * @param int $graduate_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function displayFaculty(int $graduate_id) {
        return $this->displayByTable(NumGraduate::class, 'faculty', $graduate_id);
    }
}
