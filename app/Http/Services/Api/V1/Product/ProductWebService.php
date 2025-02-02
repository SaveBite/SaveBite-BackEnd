<?php

namespace App\Http\Services\Api\V1\Product;

use App\Http\Services\Api\V1\Product\ProductService;

class ProductWebService extends ProductService
{

    public static function platform(): string
    {
        return 'web';
    }
}
