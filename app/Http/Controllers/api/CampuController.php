<?php

namespace App\Http\Controllers\api;

use App\Models\Campu;
use App\Services\CampuDataFormatterService;
use App\Http\Controllers\Controller;
use App\Http\Requests\CampuRequest;
use App\Services\CampuService;
use Illuminate\Http\JsonResponse;

class CampuController extends Controller
{
    protected $formatter;
    protected $services;

    public function __construct(CampuDataFormatterService $formatter, CampuService $services) 
    {
        $this->formatter = $formatter;
        $this->services = $services;
    }

    /**
     * Display a listing of the resource.
     * 
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $campus = Campu::with('graduates')->get();
        $campusData = $this->formatter->formatCampusData($campus);

        return $campus->isEmpty() 
        ? $this->jsonResponse('No se encontro campus', [], 200)
        : $this->jsonResponse('Campus encontrados con éxitos',$campusData, 200);
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param CampuRequest $request
     * @return JsonResponse
     */
    public function store(CampuRequest $request): JsonResponse
    {
        if(!$this->services->verifyCampusExist($request)){
            $campu = Campu::create($request->validated());
        }
        return $this->jsonResponse('Campus creado con éxito', $campu, 201);
    }

    /**
     * Display the specified resource.
     * 
     * @param int $campu_id
     * @return JsonResponse
     */
    public function show(int $campu_id): JsonResponse
    {   
        $campu = Campu::with('graduates')->findOrFail($campu_id);
        $data = $this->formatter->formatCampuData($campu);
        return $this->jsonResponse('Campus encontrado', $data, 200);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param CampuRequest $request
     * @param Campu $campu
     * @return JsonResponse
     */
    public function update(CampuRequest $request, Campu $campu): JsonResponse
    {
        $campu->update($request->validated());
        return $this->jsonResponse('Campus actualizado exitosamente', $campu, 200);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param Campu $campu
     * @return JsonResponse
     */
    public function destroy(Campu $campu): JsonResponse
    {
        $campu->delete();
        return $this->jsonResponse('Campus eliminado con éxito', $campu, 200);
    }
}
