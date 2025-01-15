<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('app:process-csv', function (\App\Services\Csv\DataProcessor $dataProcessor) {
    $this->info('Processing the CSV file...');
    $dataProcessor->processCsv();
    $this->info('CSV file processed successfully!');
})->purpose('Process the CSV files and store the data in the database')->hourly();
