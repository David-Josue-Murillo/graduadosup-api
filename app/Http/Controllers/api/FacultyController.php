<?php

namespace App\Http\Controllers\api;

use Validator;
use App\Models\Faculty;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFacultyRequest;

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
    public function store(StoreFacultyRequest $request)
    {
        //Los datos ya estan validados
        $faculty = Faculty::create([
            'name' => $request->name
        ]);

        return $this->jsonResponse('Facultudad creada con éxito', $faculty, 201);
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
     * Generates a standardized JSON response for the API.
     * 
     * @param string $message Main message describing the status of the response.
     * @param mixed $data Additional data to be returned in the response (can be an array or an object).
     * @param int $status HTTP status code associated with the response (200 by default).
     * 
     * @return \Illuminate\Http\JsonResponse JSON response with the message, data, and status code.
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
