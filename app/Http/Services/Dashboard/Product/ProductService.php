<?php

namespace App\Http\Services\Dashboard\Product;

use App\Http\Services\Mutual\FileManagerService;
use App\Repository\ProductRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function __construct(private readonly ProductRepositoryInterface $Repository,
                                private readonly FileManagerService $fileManagerService)
    {}


}