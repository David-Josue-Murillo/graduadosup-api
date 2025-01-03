<?php 

namespace App\Services;

use App\Http\Requests\CampuRequest;
use App\Models\Campu;
use Illuminate\Support\Collection;

class CampuService
{
    public function verifyCampusExist(CampuRequest $request):bool {
        $record = Campu::where([
            'name' => $request->name
        ])->first();

        return $record ? throw new \Exception('Registro duplicado') : false;
    }
}