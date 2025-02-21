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

        if (request()->filled('search')) {
            $search = request('search');
            $products = $groupedProducts->filter(function ($item) use ($search) {
                return Str::contains(Str::lower($item['ProductName']), Str::lower($search));
            });
        }elseif(request()->filled('status'))
        {
            $products = match (request('status')) {
                'PositiveStock' => $groupedProducts->where('StockQuantity', '>', 0),
                'NegativeStock' => $groupedProducts->where('StockQuantity', '<', 0),
                'BelowPar'      => $groupedProducts->where('StockQuantity', '<', $groupedProducts->pluck('ReorderLevel')),
                'BelowMinimum'  => $groupedProducts->where('StockQuantity', '<', $groupedProducts->pluck('ReorderQuantity')),
                default => collect()
            };
        }else{
            $products = $groupedProducts;
        }

        return [
            'StockOnHand'   => number_format($groupedProducts->sum('SalesValue'), 2) . ' EGP',
            'PositiveStock' => $groupedProducts->where('StockQuantity', '>', 0)->count(),
            'NegativeStock' => $groupedProducts->where('StockQuantity', '<', 0)->count(),
            'BelowPar'      => $groupedProducts->where('StockQuantity', '<', $groupedProducts->pluck('ReorderLevel'))->count(),
            'BelowMinimum'  => $groupedProducts->where('StockQuantity', '<', $groupedProducts->pluck('ReorderQuantity'))->count(),
            'Products'      => ProductResource::collection($products->values()),
        ];
    }
}
