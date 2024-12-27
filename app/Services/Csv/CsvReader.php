<?php

namespace App\Services\Csv;

use League\Csv\Reader;

class CsvReader
{
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
    public function read(string $path): \Iterator
    {
        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0);
        return $csv->getRecords();
    }
}