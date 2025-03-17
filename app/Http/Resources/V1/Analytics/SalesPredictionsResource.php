<?php

namespace App\Http\Resources\V1\Analytics;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesPredictionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'week' => $this->date,
            'sales_predictions' => $this->sales_predictions
        ];
    }
}
