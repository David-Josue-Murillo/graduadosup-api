<?php

namespace App\Console\Commands;

use App\Services\Csv\DataProcessor;
use Illuminate\Console\Command;

class ProcessCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-csv';

    /**
     * La clase DataProcessor, inyectada en el constructor
     *
     * @var DataProcessor
     */
    private DataProcessor $dataProcessor;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process the CSV file and store the data in the database';

    /**
     * Crea una nueva instancia del comando.
     *
     * @param DataProcessor $dataProcessor
     */
    public function __construct(DataProcessor $dataProcessor)
    {
        parent::__construct();
        $this->dataProcessor = $dataProcessor;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Process the CSV file
        $this->info('Processing the CSV file...');

        // Llamar al método de la clase DataProcessor para procesar el CSV
        $this->dataProcessor->processCsv();

        $this->info('CSV file processed successfully!');
    }
}
