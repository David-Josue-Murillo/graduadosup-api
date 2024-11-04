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
    public function index()
    {
        $num_graduates = NumGraduate::all();
        return $num_graduates->isEmpty()
        ? $this->jsonResponse('No hay datos', [], 200)
        : $this->jsonResponse('Datos obtenidos exitosamente', $num_graduates, 200);
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
        $graduate = NumGraduate::findOrFail($graduate_id);
        return $this->jsonResponse('Dato obtenido exitosamente', $graduate, 200);
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
        $graduate = NumGraduate::with('campus')->findOrFail($graduate_id);
        return $this->jsonResponse('Campu obtenido exitosamente', $graduate->campus()->get(), 200);
    }

    /**
     * Display the career of a specified graduate
     * @param \App\Models\NumGraduate $graduate
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function displayCareer(int $graduate_id) {
        $graduate = NumGraduate::with('career')->findOrFail($graduate_id);
        return $this->jsonResponse('Carrera obtenido exitosamente', $graduate->career()->get(), 200);
    }

    /**
     * Display the faculty of a specified graduate
     * @param int $graduate_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function displayFaculty(int $graduate_id) {
        $graduate = NumGraduate::with('faculty')->findOrFail($graduate_id);
        return $this->jsonResponse('Facultad obtenido exitosamente', $graduate->faculty()->get(), 200);
    }
}
