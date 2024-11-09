<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CareerRequest;
use App\Models\Career;
use App\Models\Faculty;
use App\Services\CareerDataFormatterService;
use App\Services\CareerService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CareerController extends Controller
{
    protected $careerService;
    protected $formatterData;

    public function __construct(CareerService $careerService, CareerDataFormatterService $formatterData){
        $this->careerService = $careerService;
        $this->formatterData = $formatterData;
    }

    /**
     * Display a listing of the resource.
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $query = Career::query();
        $query = $this->careerService->verifyFilter($query, $request);

        $careers = $query->with(['graduates', 'faculty'])->paginate(15);
        $careersData = $this->formatterData->formatCareerData($careers);
        
        return $careers->isEmpty() 
        ? $this->jsonResponse('No se encontraron carreras', [], 200)
        : $this->jsonResponse('Carreras encontradas exitosamente', $careersData, 200);
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param CareerRequest $request
     * @return JsonResponse
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
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id)
    {
        $career = Career::with(['graduates', 'faculty'])->findOrFail($id);
        $careerData = $this->formatterData->formatterData($career);

        return $this->jsonResponse('Carrera encontrada', $careerData, 200);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param CareerRequest $request
     * @param Career $career
     * @return JsonResponse
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
}
