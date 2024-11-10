<?php 

namespace App\Services;

use App\Models\Career;

class CareerDataFormatterService 
{

    public function formatterData(Career $career):array {
        return [
            'id' => $career->id,
            'name' => $career->name,
            'total_graduates' => $career->graduates->count(),
            'faculty' => [
                'id' => $career->faculty->id,
                'name' => $career->faculty->name
            ]
        ];
    }

    public function formatCareerData($careers){
        return $careers->map(function ($career) {
            return $this->formatterData($career);
        });
    }
}