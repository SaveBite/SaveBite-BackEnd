<?php

namespace App\Http\Resources\V1\Otp;

use App\Http\Resources\V1\PaginatorResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OtpCollection extends ResourceCollection
{
    public function toArray(Request $request)
    {
        return [
            "parent" => OtpResource::collection($this->collection),
            "pagination" => PaginatorResource::make($this),
        ];
    }
}