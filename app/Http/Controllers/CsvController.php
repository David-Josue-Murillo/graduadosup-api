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

    /**
     * Processes multiple CSV files and maps their data to corresponding models.
     *
     * This method processes three CSV files: 'faculties.csv', 'campus.csv', and 'careers.csv'.
     * It maps the data from these files to the Faculty, Campus, and Career models respectively.
     * The column mappings for each file are specified in the method calls.
     *
     * @return void
     */
    public function processCsv(): void
    {
        $this->processCsvData('faculties.csv', Faculty::class, ['name' => 'faculty']);
        $this->processCsvData('campus.csv', Campu::class, ['name' => 'campus']);
        $this->processCsvData('careers.csv', Career::class, [
            'name' => 'career',
            'faculty_id' => 'faculty'
        ]);
    }

    /**
     * Processes a CSV file and maps its data to a specified model.
     *
     * This method reads a CSV file, verifies its existence, and processes its records.
     * It maps the CSV columns to the model's database columns as specified in the column mappings.
     * The data is then inserted into the database within a transaction.
     *
     * @param string $filename The name of the CSV file to process.
     * @param string $model The model class to map the data to.
     * @param array $columnMappings An associative array mapping CSV columns to database columns.
     *
     * @return void
     */
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

    /**
     * Verifies if a file exists at the given path.
     *
     * This method checks if a file exists at the specified path.
     * If the file does not exist, it returns a JSON response with an error message and a 404 status code.
     *
     * @param string $file The path to the file to verify.
     * @return bool Returns true if the file exists, false otherwise.
     */
    private function verifyIfExistFile(string $file): bool
    {
        if (file_exists($file)) return true;
        response()->json(['error' => 'El archivo no existe en el sistema.'], 404);
        return false;
    }

    /**
     * Reads a CSV file and returns its records as an iterator.
     *
     * This method creates a CSV reader from the specified file path,
     * sets the header offset to 0 to use the first row as the header,
     * and returns an iterator for the CSV records.
     *
     * @param string $path The path to the CSV file.
     * @return \Iterator An iterator for the CSV records.
     */
    private function readCsv(string $path): \Iterator
    {
        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0);
        return $csv->getRecords();
    }
}
