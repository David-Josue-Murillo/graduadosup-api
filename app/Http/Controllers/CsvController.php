<?php

namespace App\Http\Controllers;

use App\Models\Campu;
use App\Models\Career;
use App\Models\Faculty;
use League\Csv\Reader;

class CsvController extends Controller
{
    //
    public function processCsv(): void
    {
        //$this->processCsvFaculty();
        //$this->processCsvCampus();
        $this->processCsvCareers();
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

    private function processCsvCareers(): void
    {
        $filePath = storage_path('app/csv/careers.csv');
        if (!file_exists($filePath)) {
            response()->json(['error' => 'El archivo no existe en el sistema.'], 404);
            return;
        }

        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0); // Define la primera fila como encabezado
        $records = $csv->getRecords();

        foreach ($records as $record) {
            Career::create([
                'name' => $record['career'],
                'faculty_id' => $record['faculty'],
            ]);
        }
    }
}
