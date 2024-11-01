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
            : $this->jsonResponse('Facultades encontradas exitosamente', $faculties, 200);
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
        ? $this->jsonResponse('Error al crear la facultad', [], 500)
        : $this->jsonResponse('La facultudad fue creada con éxito', $faculty, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $faculty = Faculty::find($id);

        return !$faculty
        ? $this->jsonResponse('La facultad no se encuentra o no existe', [], 404)
        : $this->jsonResponse('Facultad encontrada', $faculty, 200);
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

        return $this->jsonResponse('Facultasd actualizada exitosamente', $faculty, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faculty $faculty)
    {
        //
        $faculty->delete();
        return $this->jsonResponse('Facultad eliminada con éxito', [], 200);
    }

    /**
     * Genera una respuesta JSON estandarizada para la API.
     * 
     * @param string $message Mensaje principal que describe el estado de la respuesta.
     * @param mixed $data Datos adicionales que se devuelven en la respuesta (puede ser un array o un objeto).
     * @param int $status Código de estado HTTP asociado a la respuesta (200 por defecto).
     * 
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el mensaje, los datos y el código de estado.
     */
    private function jsonResponse($message, $data = [], $status = 200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'status' => $status
        ], $status);
    }
}
