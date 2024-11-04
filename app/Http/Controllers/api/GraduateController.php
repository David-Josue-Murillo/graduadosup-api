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
    public function update(NumGraduatesRequest $request, NumGraduate $graduate)
    {
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
    public function destroy(NumGraduatesRequest $graduate)
    {
        $graduate->delete();
        return $this->jsonResponse('Dato eliminado exitosamente', $graduate, 200);
    }

    public function displayCampu(NumGraduate $graduate) {
        $campu = $graduate->campu()->get();
        return $this->jsonResponse('Campu obtenido exitosamente', $campu, 200);
    }

    public function displyaCareer(NumGraduate $graduate) {
        $career = $graduate->career()->get();
        return $this->jsonResponse('Carrera obtenido exitosamente', $career, 200);
    }

    public function displayFaculty(NumGraduate $graduate) {
        $faculty = $graduate->career()->faculty()->get();
        return $this->jsonResponse('Facultad obtenido exitosamente', $faculty, 200);
    }
}
