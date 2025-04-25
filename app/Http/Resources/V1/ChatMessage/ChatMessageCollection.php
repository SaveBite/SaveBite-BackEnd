<?php

namespace App\Http\Resources\V1\ChatMessage;

use App\Http\Resources\V1\PaginatorResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ChatMessageCollection extends ResourceCollection
{
    public function toArray(Request $request)
    {
        return [
            "parent" => ChatMessageResource::collection($this->collection),
            "pagination" => PaginatorResource::make($this),
        ];
    }
}