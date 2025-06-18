<?php

namespace App\Http\Resources\V1\TrackingProduct;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrackingProductResource extends JsonResource
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
            'numberId' => $this->numberId,
            'category' => $this->category,
            'quantity' => $this->quantity,
            'label' => $this->label,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'image' => $this->image ? asset('storage/' . $this->image) : null, // Assuming image is stored in public storage
        ];
    }
}
