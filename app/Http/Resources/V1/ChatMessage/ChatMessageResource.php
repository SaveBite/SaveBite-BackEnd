<?php

namespace App\Http\Resources\V1\ChatMessage;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatMessageResource extends JsonResource
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
            'message' => $this->message,
            'me' => !$this->is_bot,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'favourite' =>boolval( $this->is_favorite),
        ];
    }
}
