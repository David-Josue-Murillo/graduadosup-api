<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Validator;

class FacultyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $faculties = Faculty::all();

        return $faculties->isEmpty()
        ? $this->jsonResponse('No se encontraron facultades', [], 404)
        : $this->jsonResponse('Facultades encontradas exitosamente', $faculties, 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator =  Validator::make($request->all(), [
            'name' => 'required|max:100'
        ]);

        // Validando datos
        if($validator->fails()){
            return $this->jsonResponse('Error en la validación de los datos', $validator->errors(), 400);
        }
        
        $faculty = Faculty::create([
            'name' => $request->name
        ]);

        return !$faculty
        ? $this->jsonResponse('Error al crear la facultad', [], 404)
        : $this->jsonResponse('La facultudad fue creada con exito', $faculty, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $faculty = Faculty::find($id);

        return !$faculty
        ? $this->jsonResponse('La facultad no se encuentra o no existe', [], 404)
        : $this->jsonResponse('Facultad encontrada exitosamente', $faculty, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Faculty $faculty)
    {
        //
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100'
        ]);

        if($validator->fails()){
            return $this->jsonResponse('Error en la validación de los datos', $validator->errors(), 400);
        }

        $faculty->update([
            'name' => $request->name
        ]);

        return $this->jsonResponse('Facultasd actualizada exitosamente', $faculty, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faculty $faculty)
    {
        //
        $faculty->delete();
        return $this->jsonResponse('Facultad eliminada exitosamente', [], 200);
    }

    private function jsonResponse($message, $data = [], $status)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'status' => $status
        ], $status);
    }
}
