<?php

namespace App\Http\Controllers\Api;

use App\Models\Faculty;
use App\Http\Controllers\Controller;
use App\Http\Requests\FacultyRequest;
use App\Services\FacultyService;
use Illuminate\Http\JsonResponse;

class FacultyController extends Controller
{
    protected $services;

    public function __construct(FacultyService $services)
    {
        $this->services = $services;
    }

    /**
     * Display a listing of the resource.
     * 
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $faculties = Faculty::with('careers')->get();
        $facultiesData = $this->services->formatFacultiesData($faculties);

        return $faculties->isEmpty()
            ?  jsonResponse('No se encontraron facultades', [], 200)
            :  jsonResponse('Facultades encontradas exitosamente', $facultiesData, 200);
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param FacultyRequest $request
     * @return JsonResponse
     */
    public function store(FacultyRequest $request): JsonResponse
    {
        if(!$this->services->verifyFacultyExists($request)){
            $faculty = Faculty::create($request->validated());
        }
        return  jsonResponse('Facultad creada con éxito', $faculty, 201);
    }

    /**
     * Display the specified resource.
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $faculty = Faculty::with('careers')->findOrFail($id);
        $facultyData = $this->services->formatFacultyData($faculty);
        return  jsonResponse('Facultad encontrada', $facultyData, 200);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param FacultyRequest $request
     * @param Faculty $faculty
     * @return JsonResponse
     */
    public function update(FacultyRequest $request, Faculty $faculty): JsonResponse
    {
        $faculty->update($request->validated());
        return  jsonResponse('Facultad actualizada exitosamente', $faculty, 200);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param Faculty $faculty
     * @return JsonResponse
     */
    public function destroy(Faculty $faculty): JsonResponse
    {
        $faculty->delete();
        return  jsonResponse('Facultad eliminada con éxito', [], 204);
    }

    /**
     * Display careers from this faculty
     * 
     * @param Faculty $faculty
     * @return JsonResponse
     */
    public function displayCareers(Faculty $faculty): JsonResponse
    {
        $careers = $faculty->careers()->get();
        return  jsonResponse('Lista de carreras', $careers, 200);
    }
}
