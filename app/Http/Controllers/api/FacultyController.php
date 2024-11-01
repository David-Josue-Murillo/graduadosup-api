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

        if($faculties->isEmpty()){
            $data = [
                'message' => 'No hay facultades',
                'status' => 200 // 200: Indica que la solicitud fue realizada con exito
            ];

            return response()->json($data, 200);
        }

        $data = [
            'mesage' => $faculties,
            'status' => 201 // 201: Indica que la solicitud fue realizada con exito y se ha creado un nuevo recurso
        ];

        return response()->json($data, 201);
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
            $data = [
                'message' => 'Error en la validación de los datos',
                'errors'=> $validator->errors(),
                'status' => 400
            ];

            return response()->json($data, 400);
        }
        
        $faculty = Faculty::create([
            'name' => $request->name
        ]);

        // Si no se pudo crear la facultad
        if(!$faculty){
            $data = [
                'message' => 'Error al crear la facultad',
                'status' => 400
            ];

            return response()->json($data, 400);
        }

        // Datos de respuesta
        $data = [
            'message' => 'Nueva facultad creada',
            'facultad' => $faculty,
            'status' => 201
        ];

        return response()->json($data, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Faculty $faculty)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Faculty $faculty)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Faculty $faculty)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faculty $faculty)
    {
        //
    }
}
