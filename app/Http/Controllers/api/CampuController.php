<?php

namespace App\Http\Controllers\api;

use App\Models\Campu;
use App\Services\CampusDataFormatterService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CampuRequest;

class CampuController extends Controller
{
    protected $formatter;

    public function __construct(CampusDataFormatterService $formatter) {
        $this->formatter = $formatter;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
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
     */
    public function store(CampuRequest $request)
    {
        $campu = Campu::create([
            'name' => $request->name
        ]);

        return $this->jsonResponse('Campus creado con éxito', $campu, 201);
    }

    /**
     * Display the specified resource.
     * 
     * @param int $campu_id
     */
    public function show(int $campu_id)
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
     */
    public function update(CampuRequest $request, Campu $campu)
    {
        $campu->update([
            'name' => $request->name
        ]);
        return $this->jsonResponse('Campus actualizado exitosamente', $campu, 200);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param Campu $campu
     */
    public function destroy(Campu $campu)
    {
        $campu->delete();
        return $this->jsonResponse('Campus eliminado con éxito', [], 200);
    }
}
