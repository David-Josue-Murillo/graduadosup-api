<?php

namespace App\Http\Controllers;

use App\Models\Campu;
use App\Models\Career;
use App\Models\Faculty;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class CsvController extends Controller
{
    private const CSV_PATH = 'app/csv/';

    public function processCsv(): void
    {
        $this->processCsvData('faculties.csv', Faculty::class, ['name' => 'faculty']);
        $this->processCsvData('campus.csv', Campu::class, ['name' => 'campus']);
        $this->processCsvData('careers.csv', Career::class, [
            'name' => 'career',
            'faculty_id' => 'faculty'
        ]);
    }

    private function processCsvData(string $filename, string $model, array $columnMappings): void
    {
        $filePath = storage_path(self::CSV_PATH . $filename);
        if (!$this->verifyIfExistFile($filePath)) return;

        $records = $this->readCsv($filePath);

        DB::transaction(function() use ($records, $model, $columnMappings) {
            foreach ($records as $record) {
                $mappedData = collect($columnMappings)
                    ->mapWithKeys(fn($csvColumn, $dbColumn) =>
                    [$dbColumn => $record[$csvColumn]]
                    )
                    ->toArray();

                $model::create($mappedData);
            }
        });
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
