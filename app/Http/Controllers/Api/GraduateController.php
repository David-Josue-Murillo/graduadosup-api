<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NumGraduatesRequest;
use App\Models\NumGraduate;
use App\Services\DataDisplayByService;
use App\Services\GraduateDataFormatterService;
use App\Services\GraduateService;
use Illuminate\Http\JsonResponse;


class GraduateController extends Controller
{
    protected $formatter;
    protected $displayData;
    protected $graduateService;

    public function __construct(GraduateDataFormatterService $formatter, DataDisplayByService $displayData, GraduateService $graduateService)
    {
        $this->formatter = $formatter;
        $this->displayData = $displayData;
        $this->graduateService = $graduateService;
    }

    /**
     * Display a listing of the resource.
     * 
     * @param NumGraduatesRequest $request
     * @return JsonResponse
     */
    public function index(NumGraduatesRequest $request): JsonResponse
    {
        $query = NumGraduate::query();
        $query = $this->graduateService->verifyFilter($query, $request);

        $numGraduates = $query->with(['campus', 'career', 'faculty'])->paginate(15);
        $numGraduatesData = $this->formatter->formatNumGraduatedData($numGraduates);

        return $numGraduates->isEmpty()
        ?  jsonResponse('No hay datos', [], 200)
        :  jsonResponse('Datos obtenidos exitosamente', $numGraduatesData, 200);
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param NumGraduatesRequest $request
     * @return JsonResponse
     */
    public function store(NumGraduatesRequest $request): JsonResponse
    {   
        if($this->graduateService->ifNotExistsRecord($request)) {
            $graduate = NumGraduate::create($request->validated());
        }

        return  jsonResponse("Dato creado exitosamente", $graduate, 201);
    }

    /**
     * Display the specified resource.
     * 
     * @param int $graduate_id
     * @return JsonResponse
     */
    public function show(int $graduate_id): JsonResponse
    {
        $graduate = NumGraduate::with('campus', 'career', 'faculty')->findOrFail($graduate_id);
        $formattedGraduate = $this->formatter->formatGraduatedData($graduate);
        return  jsonResponse('Dato obtenido exitosamente', $formattedGraduate, 200);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param NumGraduatesRequest $request
     * @param int $graduate_id
     * @return JsonResponse
     */
    public function update(NumGraduatesRequest $request, int $graduate_id): JsonResponse
    {
        $graduate = NumGraduate::findOrFail($graduate_id);
        $this->graduateService->updateRecord($request, $graduate);

        return  jsonResponse('Dato actualizado exitosamente', $graduate, 200);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param int $graduate_id
     * @return JsonResponse
     */
    public function destroy(int $graduate_id): JsonResponse
    {
        $graduate = NumGraduate::findOrFail($graduate_id);
        $graduate->delete();
        return  jsonResponse('Dato eliminado exitosamente', $graduate, 200);
    }

    /**
     * Display the Campus of a specified graduate
     * 
     * @param int $graduate_id
     * @return JsonResponse
     */
    public function filterByCampus(int $graduate_id): JsonResponse 
    {
        $data = $this->displayData->numGraduateRelatedData('campus', $graduate_id);
        return  jsonResponse("Dato obtenido exitosamente", $data, 200);
    }

    /**
     * Display the career of a specified graduate
     * 
     * @param int $graduate_id
     * @return JsonResponse
     * 
     */
    public function filterByCareer(int $graduate_id):JsonResponse 
    {
        $data = $this->displayData->numGraduateRelatedData('career', $graduate_id);
        return  jsonResponse("Dato obtenido exitosamente", $data, 200);
    }

    /**
     * Display the faculty of a specified graduate
     * 
     * @param int $graduate_id
     * @return JsonResponse
     */
    public function filterByFaculty(int $graduate_id): JsonResponse 
    {
        $data = $this->displayData->numGraduateRelatedData('faculty', $graduate_id);
        return  jsonResponse("Dato obtenido exitosamente", $data, 200);
    }
}
