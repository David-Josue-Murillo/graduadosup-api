<?php

namespace App\Services\Csv;

class FileValidator
{
    /**
     * Verifies if a file exists at the given path.
     *
     * This method checks if a file exists at the specified path.
     * If the file does not exist, it returns a JSON response with an error message and a 404 status code.
     *
     * @param string $path The path to the file to verify.
     * @return bool Returns true if the file exists, false otherwise.
     */
    public function validate(string $path): bool
    {
        if (!file_exists($path)) {
            response()->json(['error' => 'El archivo no existe en el sistema.'], 404);
            return false;
        }
        return true;
    }
}