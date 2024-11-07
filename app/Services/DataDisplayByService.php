<?php

namespace App\Services;

use App\Models\NumGraduate;

class DataDisplayByService 
{
    /**
     * Retrieves related data from a specific table through a model.
     * 
     * @param string $model The model class to query.
     * @param string $relation The relationship to load.
     * @param int $id The primary key of the model to retrieve.
     */
    public function numGraduateRelatedData(string $relation, int $id) {
        $data = NumGraduate::with($relation)->findOrFail($id);
        return $data->$relation()->get();
    }
}