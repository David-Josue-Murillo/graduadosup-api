<?php

namespace App\Services;

use Illuminate\Support\Collection;

class CampusDataFormatterService 
{
    /**
     * Formats and returns custom data structure for Campus with graduate information.
     * 
     * @param \Illuminate\Database\Eloquent\Collection $campus The collection of Campus models.
     * 
     * @return \Illuminate\Support\Collection
     */
    public function formatCampusData(Collection $campus): Collection {
        $campusData = $campus->map(function($campus) {
            return [
                'id' => $campus->id,
                'name' => $campus->name,
                'graduates_info' => [
                    'total_graduates' => $campus->graduates->sum('quantity'),
                    'by_year' => $campus->graduates
                        ->groupBy('year')
                        ->map(function($yearGroup) {
                            return [
                                'quantity' => $yearGroup->sum('quantity')
                            ];
                        })
                ]
            ];
        });

        return $campusData;
    }
}