<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CampuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'graduates' => [
                'total_graduates' => $this->graduates->sum('quantity'),
                'by_year' => $this->graduates
                    ->groupBy('year')
                    ->map(function($yearGroup) {
                        return [
                            'quantity' => $yearGroup->sum('quantity')
                        ];
                    })
            ]
        ];
    }
}
