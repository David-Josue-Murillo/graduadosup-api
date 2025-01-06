<?php

namespace App\Services\Csv;

use Illuminate\Support\Facades\DB;

class DataProcessor
{
    private const CSV_PATH = 'app/csv/';
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