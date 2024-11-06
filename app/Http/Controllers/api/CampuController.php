<?php

namespace App\Http\Controllers\api;

use App\Models\Campu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CampuRequest;

class CampuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $campus = Campu::with(['graduates' => function($query) {
            $query->with(['career' => function($q) {
                $q->with('faculty');
            }]);
        }])->get();

        $campusData = $this->displayDataCampus($campus);

        return $campus->isEmpty() 
        ? $this->jsonResponse('No se encontro campus', [], 200)
        : $this->jsonResponse('Campus encontrados con éxitos',$campusData, 200);
    }

    /**
     * Store a newly created resource in storage.
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
     */
    public function show(int $campu_id)
    {   
        $campu = Campu::findOrFail($campu_id);
        return $this->jsonResponse('Campus encontrado', $campu, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Campu $campu)
    {
        $campu->update([
            'name' => $request->name
        ]);
        return $this->jsonResponse('Campus actualizado exitosamente', $campu, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campu $campu)
    {
        $campu->delete();
        return $this->jsonResponse('Campus eliminado con éxito', [], 200);
    }
}
