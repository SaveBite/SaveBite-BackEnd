<?php

namespace App\Http\Resources\V1\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'Date' => $this['Date'],
            'ProductName'     => $this['ProductName'],
            'Category'        => $this['Category'],
            'UnitPrice'       => $this['UnitPrice'],
            'StockQuantity'   => $this['StockQuantity'],
            'ReorderLevel'    => $this['ReorderLevel'],
            'ReorderQuantity' => $this['ReorderQuantity'],
            'UnitsSold'       => $this['UnitsSold'],
            'SalesValue'      => number_format($this['SalesValue'],2)
        ];
    }
}
