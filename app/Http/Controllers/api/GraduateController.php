<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NumGraduatesRequest;
use App\Models\Campu;
use App\Models\Career;
use App\Models\NumGraduate;
use App\Services\DataDisplayByService;
use App\Services\GraduateDataFormatterService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Log;

class GraduateController extends Controller
{
    protected $formatter;
    protected $displayData;

    public function __construct(GraduateDataFormatterService $formatter, DataDisplayByService $displayData){
        $this->formatter = $formatter;
        $this->displayData = $displayData;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = NumGraduate::query();
    
            // Aplicar filtros opcionales si se proporcionan en el request
            if ($request->filled('year')) {
                $query->where('year', $request->year);
            }
            if ($request->filled('campus_id')) {
                $query->where('campus_id', $request->campus_id);
            }
            if ($request->filled('career_id')) {
                $query->where('career_id', $request->career_id);
            }
    
            $numGraduates = $query->with(['campus', 'career', 'faculty'])->paginate(15);
            $numGraduatesData = $this->formatter->formatNumGraduatedData($numGraduates);
    
            return $numGraduates->isEmpty()
            ? $this->jsonResponse('No hay datos', [], 200)
            : $this->jsonResponse('Datos obtenidos exitosamente', $numGraduatesData, 200);
        } catch (QueryException $e) {
            Log::error('Error en la consulta de graduados: ' . $e->getMessage());
            return $this->jsonResponse('Error al consultar la base de datos', null, 500);
        } catch (Exception $e) {
            Log::error('Error inesperado en index: ' . $e->getMessage());
            return $this->jsonResponse('Error interno del servidor', null, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NumGraduatesRequest $request)
    {
        try {
            $campus = Campu::find($request->campus_id);
            $career = Career::find($request->career_id);

            if(!$campus || !$career) {
                $missing = !$campus ? 'Campus' : !$career ? 'Carrera' : null;
                return $this->jsonResponse("El {$missing} especificado no existe", null, 422);
            }

            $graduates = NumGraduate::create([
                'quantity' => $request->quantity,
                'year' => $request->year,
                'campus_id' => $request->campus_id,
                'career_id' => $request->career_id
            ]);
    
            return $this->jsonResponse("Dato creado exitosamente", $graduates, 201);
        } catch (Exception $e) {
            return $this->jsonResponse("Error al crear el dato", null, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $graduate_id)
    {
        try {
            $graduate = NumGraduate::with('campus', 'career', 'faculty')->findOrFail($graduate_id);
            $formattedGraduate = $this->formatter->formatGraduatedData($graduate);
    
            return $this->jsonResponse('Dato obtenido exitosamente', $formattedGraduate, 200);
        } catch (Exception $e){
            return $this->jsonResponse("Error al obtener el dato", null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(NumGraduatesRequest $request, int $graduate_id)
    {
        try{
            $graduate = NumGraduate::findOrFail($graduate_id);
            $graduate->update([
                'quantity' => $request->quantity,
                'year' => $request->year,
                'campus_id' => $request->campus_id,
                'career_id' => $request->career_id
            ]);
    
            return $this->jsonResponse('Dato actualizado exitosamente', $graduate, 200);
        } catch (ModelNotFoundException $e) {
            return $this->jsonResponse('Registro de graduado no encontrado', null, 404);
        } catch (Exception $e) {
            return $this->jsonResponse('Error al actualizar los datos del graduado', null, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $graduate_id)
    {
        try {
            $graduate = NumGraduate::findOrFail($graduate_id);
            $graduate->delete();
            return $this->jsonResponse('Dato eliminado exitosamente', $graduate, 200);
        } catch (ModelNotFoundException $e) {
            return $this->jsonResponse('Registro de graduado no encontrado', null, 404);
        } catch (Exception $e) {
            return $this->jsonResponse('Error al eliminar el dato', null, 500);
        }
    }

    /**
     * Display the Campus of a specified graduate
     * @param \App\Models\NumGraduate $graduate
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function displayCampus(int $graduate_id) {
        $data = $this->displayData->numGraduateRelatedData('campus', $graduate_id);
        return $this->jsonResponse("Dato obtenido exitosamente", $data, 200);
    }

    /**
     * Display the career of a specified graduate
     * @param \App\Models\NumGraduate $graduate
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function displayCareer(int $graduate_id) {
        $data = $this->displayData->numGraduateRelatedData('career', $graduate_id);
        return $this->jsonResponse("Dato obtenido exitosamente", $data, 200);
    }

    /**
     * Display the faculty of a specified graduate
     * @param int $graduate_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function displayFaculty(int $graduate_id) {
        $data = $this->displayData->numGraduateRelatedData('faculty', $graduate_id);
        return $this->jsonResponse("Dato obtenido exitosamente", $data, 200);
    }
}
