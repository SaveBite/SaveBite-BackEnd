<?php

namespace App\Http\Resources\V1\Product;

use App\Http\Resources\V1\PaginatorResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

use Illuminate\Support\Str;
use function Laravel\Prompts\search;

class ProductCollection extends ResourceCollection
{
    public function toArray(Request $request)
    {
        $products = $this->collection;
        $statistics = [
            'StockOnHand'   => number_format($products->sum('SalesValue'), 2) . ' EGP',
            'PositiveStock' => $products->where('StockQuantity', '>', 0)->count(),
            'NegativeStock' => $products->where('StockQuantity', '<', 0)->count(),
            'BelowPar'      => $products->filter(fn ($item) => $item['StockQuantity'] < $item['ReorderLevel'])->count(),
            'BelowMinimum'  => $products->filter(fn ($item) => $item['StockQuantity'] < $item['ReorderQuantity'])->count(),
            ];

        if (request()->filled('search')) {
            $search = request('search');
            $products = $products->filter(function ($item) use ($search) {
                return Str::contains(Str::lower($item['ProductName']), Str::lower($search));
            });
        }elseif(request()->filled('status'))
        {
            $products = match (request('status')) {
                'PositiveStock' => $products->filter(fn ($item) => $item['StockQuantity'] > 0),
                'NegativeStock' => $products->filter(fn ($item) => $item['StockQuantity'] < 0),
                'BelowPar'      => $products->filter(fn ($item) => $item['StockQuantity'] < $item['ReorderLevel']),
                'BelowMinimum'  => $products->filter(fn ($item) => $item['StockQuantity'] < $item['ReorderQuantity']),
                default => collect()
            };
        }

        return [
            ...$statistics,
            'Products'      => ProductResource::collection($products),
        ];
    }
}
