<?php

namespace App\Http\Requests\Api\V1\TrackingProduct;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TrackingProductUpdateRequest extends FormRequest
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
            "name" => ['sometimes', 'string'],
            "numberId" => [
                'sometimes',
                'string',
                Rule::unique('tracking_products', 'numberId')->ignore($this->route('tracking_product'))
            ],
            "category" => ['sometimes', 'string'],
            "quantity" => ['sometimes', 'integer'],
            "label" => ['sometimes', 'string'],
            "start_date" => ['sometimes', 'date_format:Y-m-d'],
            "end_date" => ['sometimes', 'date_format:Y-m-d', 'after_or_equal:start_date'],
            "status" => ['sometimes', 'string'],
        ];
    }
}
