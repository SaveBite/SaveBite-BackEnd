<?php

namespace App\Http\Requests\Api\V1\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            "ProductName" => ['required', "string"],
            "Date" => ['required', "date_format:Y-m-d"],
            "Category" => ['required', "string"],
            "UnitPrice" => ['required', "integer"],
            "StockQuantity" => ['required', "decimal:2"],
            "ReorderLevel" => ['required', "integer"],
            "ReorderQuantity" => ['required', "integer"],
            "UnitsSold" => ['required', "integer"],
            "SalesValue" => ['required', "integer"],
            "Month" => ['required', "date_format:Y-m"],
        ];
    }
}
