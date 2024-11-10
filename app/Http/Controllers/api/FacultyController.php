<?php

namespace App\Http\Controllers\api;

use App\Models\Faculty;
use App\Http\Controllers\Controller;
use App\Http\Requests\FacultyRequest;
use App\Services\FacultyDataFormatterService;
use App\Services\FacultyService;

class FacultyController extends Controller
{
    protected $formatter;
    protected $services;

    public function __construct(FacultyDataFormatterService $formatter, FacultyService $services){
        $this->formatter = $formatter;
        $this->services = $services;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $faculties = Faculty::with('careers')->get();
        $facultiesData = $this->formatter->formatFacultiesData($faculties);

        return $faculties->isEmpty()
            ? $this->jsonResponse('No se encontraron facultades', [], 200)
            : $this->jsonResponse('Facultades encontradas exitosamente', $facultiesData, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FacultyRequest $request)
    {
        if(!$this->services->verifyFacultyExists($request)){
            $faculty = Faculty::create($request->validated());
        }
        return $this->jsonResponse('Facultad creada con éxito', $faculty, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $faculty = Faculty::with('careers')->findOrFail($id);
        $facultyData = $this->formatter->formatFacultyData($faculty);
        return $this->jsonResponse('Facultad encontrada', $facultyData, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FacultyRequest $request, Faculty $faculty)
    {
        $faculty->update($request->validated());
        return $this->jsonResponse('Facultad actualizada exitosamente', $faculty, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faculty $faculty)
    {
        $faculty->delete();
        return $this->jsonResponse('Facultad eliminada con éxito', [], 200);
    }

    /**
     * Display careers from this faculty
     */
    public function displayCareers(Faculty $faculty)
    {
        $careers = $faculty->careers()->get();
        return $this->jsonResponse('Lista de carreras', $careers, 200);
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
}
