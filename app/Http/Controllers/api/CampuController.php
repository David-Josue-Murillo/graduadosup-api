<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Campu;
use Illuminate\Http\Request;

class CampuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $campus = Campu::all();
        return $campus->isEmpty() 
        ? $this->jsonResponse('No se encontro campus', [], 200)
        : $this->jsonResponse('Campus encontrados con éxitos', $campus, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Campu $campu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Campu $campu)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Campu $campu)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campu $campu)
    {
        //
    }
}
