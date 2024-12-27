<?php

namespace App\Http\Controllers;

use App\Models\Campu;
use App\Models\Career;
use App\Models\Faculty;
use League\Csv\Reader;

class CsvController extends Controller
{
    private const CSV_PATH = 'app/csv/';

    public function processCsv(): void
    {
        $this->processCsvFaculty();
        $this->processCsvCampus();
        $this->processCsvCareers();
    }

    private function processCsvFaculty(): void
    {
        $filePath = storage_path(self::CSV_PATH.'faculties.csv');
        if(!$this->verifyIfExistFile($filePath)) return;
        $records = $this->readCsv($filePath);


        foreach ($records as $record) {
            Faculty::create([
                'name' => $record['faculty'],
            ]);
        }
    }

    private function processCsvCampus(): void
    {
        $filePath = storage_path(self::CSV_PATH.'campus.csv');
        if(!$this->verifyIfExistFile($filePath)) return;
        $records = $this->readCsv($filePath);


        foreach ($records as $record) {
            Campu::create([
                'name' => $record['campus'],
            ]);
        }
    }

    private function processCsvCareers(): void
    {
        $filePath = storage_path(self::CSV_PATH.'careers.csv');
        if(!$this->verifyIfExistFile($filePath)) return;
        $records = $this->readCsv($filePath);

        foreach ($records as $record) {
            Career::create([
                'name' => $record['career'],
                'faculty_id' => $record['faculty'],
            ]);
        }
    }

    private function verifyIfExistFile(string $file): bool
    {
        if (file_exists($file)) return true;
        response()->json(['error' => 'El archivo no existe en el sistema.'], 404);
        return false;
    }

    private function readCsv(string $path): \Iterator
    {
        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0);
        return $csv->getRecords();
    }
}
