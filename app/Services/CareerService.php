<?php 

namespace App\Services;

use App\Models\Career;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CareerService 
{

    public function verifyFilter(Builder $query, Request $request):Builder {
        if($request->filled('name')){
            $query->where('name', $request->name);
        }

        if($request->filled('faculty_id')){
            $query->where('faculty_id', $request->faculty_id);
        }

        return $query;
    }

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