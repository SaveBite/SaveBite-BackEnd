<?php

namespace App\Http\Resources\V1\Product;

use App\Http\Resources\V1\PaginatorResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    public function toArray(Request $request)
    {
        $products = $this->collection;
        $groupedProducts = $products->groupBy('ProductName')->map(function ($group) {
            return [
                'ProductName'       => $group->first()->ProductName,
                'Category'          => $group->first()->Category,
                'StockQuantity'     => $group->sum('StockQuantity'),
                'UnitPrice'         => $group->first()->UnitPrice,
                'ReorderLevel'      => $group->first()->ReorderLevel,
                'ReorderQuantity'   => $group->first()->ReorderQuantity,
                'UnitsSold'         => $group->sum('UnitsSold'),
                'SalesValue'        => $group->sum('SalesValue'),
                'Month'             => $group->first()->Month,
            ];
        });

        return [
            'StockOnHand'   => number_format($groupedProducts->sum('SalesValue'), 2) . ' EGP',
            'PositiveStock' => $groupedProducts->where('StockQuantity', '>', 0)->count(),
            'NegativeStock' => $groupedProducts->where('StockQuantity', '<', 0)->count(),
            'BelowPar'      => $groupedProducts->where('StockQuantity', '<', $groupedProducts->pluck('ReorderLevel'))->count(),
            'BelowMinimum'  => $groupedProducts->where('StockQuantity', '<', $groupedProducts->pluck('ReorderQuantity'))->count(),
            'Products'      => ProductResource::collection($groupedProducts->values()),
        ];
    }
}
