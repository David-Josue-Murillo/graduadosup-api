<?php

namespace App\Http\Controllers;

use App\Models\Campu;
use App\Models\Faculty;
use League\Csv\Reader;

class CsvController extends Controller
{
    //
    public function processCsv(): void
    {
        $this->processCsvFaculty();
        $this->processCsvCampus();
    }

    private function processCsvFaculty(): void
    {
        $filePath = storage_path('app/csv/faculties.csv');
        if (!file_exists($filePath)) {
            response()->json(['error' => 'El archivo no existe en el sistema.'], 404);
            return;
        }

        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0); // Define la primera fila como encabezado
        $records = $csv->getRecords();

        foreach ($records as $record) {
            Faculty::create([
                'name' => $record['faculty'],
            ]);
        }
    }

    private function processCsvCampus(): void
    {
        $filePath = storage_path('app/csv/campus.csv');
        if (!file_exists($filePath)) {
            response()->json(['error' => 'El archivo no existe en el sistema.'], 404);
            return;
        }

        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0); // Define la primera fila como encabezado
        $records = $csv->getRecords();

        foreach ($records as $record) {
            Campu::create([
                'name' => $record['campus'],
            ]);
        }
    }
}
