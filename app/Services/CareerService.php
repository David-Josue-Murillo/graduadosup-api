<?php 

namespace App\Services;

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
}