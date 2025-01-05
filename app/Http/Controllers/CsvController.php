<?php

namespace App\Http\Controllers;

use App\Models\Campu;
use App\Models\Career;
use App\Models\Faculty;
use App\Models\NumGraduate;
use App\Services\Csv\DataProcessor;

class CsvController extends Controller
{
    private DataProcessor $dataProcessor;

    public function __construct(DataProcessor $dataProcessor)
    {
        $this->dataProcessor = $dataProcessor;
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
        $this->dataProcessor->process('faculties.csv', Faculty::class, ['name' => 'faculty']);
        $this->dataProcessor->process('campus.csv', Campu::class, ['name' => 'campus']);
        $this->dataProcessor->process('careers.csv', Career::class, [
            'name' => 'career',
            'faculty_id' => 'faculty'
        ]);
        $this->dataProcessor->process('num_graduates.csv', NumGraduate::class, [
            'quantity' => 'quantity',
            'year' => 'year',
            'campus_id' => 'campus_id',
            'career_id' => 'career_id'
        ]);
    }
}
