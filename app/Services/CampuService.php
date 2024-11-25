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

    /**
     * Formats and returns custom data structure for Campus with graduate information.
     *
     * @param Campu $campu
     * @return array
     */
    public function formatCampuData(Campu $campu): array {
        return [
            'id' => $campu->id,
            'name' => $campu->name,
            'graduates_info' => [
                'total_graduates' => $campu->graduates->sum('quantity'),
                'by_year' => $campu->graduates
                    ->groupBy('year')
                    ->map(function($yearGroup) {
                        return [
                            'quantity' => $yearGroup->sum('quantity')
                        ];
                    })
            ]
        ];
    }

    public function formatCampusData(Collection $campus) {
        return $campus->map(function($campu){
            return $this->formatCampuData($campu);
        });
    }
}