<?php

namespace App\Http\Services\Api\V1\TrackingProduct;

use App\Http\Services\Api\V1\TrackingProduct\TrackingProductService;

class TrackingProductWebService extends TrackingProductService
{
    public static function platform(): string
    {
        return 'website';
    }
}
