<?php

namespace App\Http\Resources\V1\Chat;

use App\Http\Resources\V1\PaginatorResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ChatCollection extends ResourceCollection
{
    public function toArray(Request $request)
    {
        return [
            "parent" => ChatResource::collection($this->collection),
            "pagination" => PaginatorResource::make($this),
        ];
    }
}