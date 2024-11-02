<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CareerRequest;
use App\Models\Career;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $careers = Career::all();
        return $careers->isEmpty() 
        ? $this->jsonResponse('No se encontraron carreras', [], 200)
        : $this->jsonResponse('Carreras encontradas exitosamente', $careers, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CareerRequest $request)
    {
        $career = Career::create([
            'name' => $request->name,
            'faculty_id' => $request->faculty_id
        ]);
        return $this->jsonResponse('Carrera creada con éxito', $career, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $career = Career::findOrFail($id);
        return $this->jsonResponse('Carrera encontrada', $career, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CareerRequest $request, Career $career)
    {
        $career->update([
            'name' => $request->name,
            'faculty_id' => $request->faculty_id
        ]);
        return $this->jsonResponse('Carrera actualizada exitosamente', $career, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Career $career)
    {
        $career->delete();
        return $this->jsonResponse('Carrera eliminada con éxito', [], 200);
    }

    /**
     * Display faculty from this career
     */
    public function displayFaculty(Career $career)
    {
        $faculty = $career->faculty()->get();
        return $this->jsonResponse('Obteniendo Facultad', $faculty, 200);
    }

    /**
     * Generates a standardized JSON response for the API.
     * 
     * @param string $message Main message describing the status of the response.
     * @param mixed $data Additional data to be returned in the response (can be an array or an object).
     * @param int $statusCode HTTP status code associated with the response (200 by default).
     * 
     * @return \Illuminate\Http\JsonResponse JSON response with the message, data, and status code.
     */
    private function jsonResponse($message, $data = [], $statusCode = 200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'status' => $statusCode
        ], $statusCode);
    }
}
