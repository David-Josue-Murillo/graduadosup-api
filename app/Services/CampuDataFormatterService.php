<?php

namespace App\Services;

use App\Models\Campu;
use Illuminate\Support\Collection;

class CampuDataFormatterService 
{
    /**
     * Formats and returns custom data structure for Campus with graduate information.
     * 
     * @param \Illuminate\Database\Eloquent\Collection $campus The collection of Campus models.
     * @return \Illuminate\Support\Collection
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