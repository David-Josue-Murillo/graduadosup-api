<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NumGraduatesRequest;
use App\Models\Campu;
use App\Models\Career;
use App\Models\NumGraduate;
use App\Services\DataDisplayByService;
use App\Services\GraduateDataFormatterService;
use App\Services\GraduateService;
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
    public function index(Request $request, GraduateService $graduateService)
    {
        $query = $graduateService->verifyFilter($request);

        $numGraduates = $query->with(['campus', 'career', 'faculty'])->paginate(15);
        $numGraduatesData = $this->formatter->formatNumGraduatedData($numGraduates);

        return $numGraduates->isEmpty()
        ? $this->jsonResponse('No hay datos', [], 200)
        : $this->jsonResponse('Datos obtenidos exitosamente', $numGraduatesData, 200);
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NumGraduatesRequest $request, GraduateService $graduateService)
    {   
        
        $graduates = $graduateService->createGraduate($request->validated());
        return $this->jsonResponse("Dato creado exitosamente", $graduates, 201);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(int $graduate_id)
    {
        $graduate = NumGraduate::with('campus', 'career', 'faculty')->findOrFail($graduate_id);
        $formattedGraduate = $this->formatter->formatGraduatedData($graduate);

        return $this->jsonResponse('Dato obtenido exitosamente', $formattedGraduate, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(NumGraduatesRequest $request, int $graduate_id)
    {
        $graduate = NumGraduate::findOrFail($graduate_id);
        $graduate->update([
            'quantity' => $request->quantity,
            'year' => $request->year,
            'campus_id' => $request->campus_id,
            'career_id' => $request->career_id
        ]);

        return $this->jsonResponse('Dato actualizado exitosamente', $graduate, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $graduate_id)
    {
        $graduate = NumGraduate::findOrFail($graduate_id);
        $graduate->delete();
        return $this->jsonResponse('Dato eliminado exitosamente', $graduate, 200);
    }

    /**
     * Display the Campus of a specified graduate
     * @param \App\Models\NumGraduate $graduate
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function filterByCampus(int $graduate_id) {
        $data = $this->displayData->numGraduateRelatedData('campus', $graduate_id);
        return $this->jsonResponse("Dato obtenido exitosamente", $data, 200);
    }

    /**
     * Display the career of a specified graduate
     * @param \App\Models\NumGraduate $graduate
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function filterByCareer(int $graduate_id) {
        $data = $this->displayData->numGraduateRelatedData('career', $graduate_id);
        return $this->jsonResponse("Dato obtenido exitosamente", $data, 200);
    }

    /**
     * Display the faculty of a specified graduate
     * @param int $graduate_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function filterByFaculty(int $graduate_id) {
        $data = $this->displayData->numGraduateRelatedData('faculty', $graduate_id);
        return $this->jsonResponse("Dato obtenido exitosamente", $data, 200);
    }
}
