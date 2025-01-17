<?php

namespace App\Services\Csv;

use App\Models\Campu;
use App\Models\Career;
use App\Models\Faculty;
use App\Models\NumGraduate;
use Illuminate\Support\Facades\DB;

class DataProcessor
{
    private const CSV_PATH = 'csv/';
    private FileValidator $fileValidator;
    private CsvReader $csvReader;

    public function __construct(
        FileValidator $fileValidator,
        CsvReader $csvReader
    ) {
        $this->fileValidator = $fileValidator;
        $this->csvReader = $csvReader;
    }

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
        $this->process('faculties.csv', Faculty::class, ['name' => 'faculty']);
        $this->process('campus.csv', Campu::class, ['name' => 'campus']);
        $this->process('careers.csv', Career::class, [
            'name' => 'career',
            'faculty_id' => 'faculty'
        ]);
        $this->process('num_graduates.csv', NumGraduate::class, [
            'quantity' => 'quantity',
            'year' => 'year',
            'campus_id' => 'campus_id',
            'career_id' => 'career_id'
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
    public function process(string $filename, string $model, array $columnMappings): void
    {
        $filePath = storage_path(self::CSV_PATH . $filename);
        if (!$this->fileValidator->validate($filePath)) return;

        $records = $this->csvReader->read($filePath);

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
}