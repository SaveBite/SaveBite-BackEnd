<?php

namespace App\Http\Requests\Dashboard\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'date' => ['required', 'date'],
            'product_name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'reorder_level' => ['required', 'integer', 'min:0'],
            'reorder_quantity' => ['required', 'integer', 'min:0'],
            'units_sold' => ['required', 'integer', 'min:0'],
            'sales_value' => ['required', 'numeric', 'min:0'],
            'month' => ['required', 'string', 'max:7'], // e.g., 2024-09
        ];
    }
}
