<?php

namespace App\Http\Requests\Api\V1\TrackingProduct;

use Illuminate\Foundation\Http\FormRequest;

class TrackingProductStoreRequest extends FormRequest
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
            "name" => ['required', 'string'],
            "numberId" => ['required', 'string', 'unique:tracking_products,numberId'],
            "category" => ['required', 'string'],
            "quantity" => ['required', 'integer'],
            "label" => ['required', 'string'],
            "start_date" => ['required', 'date_format:Y-m-d'],
            "end_date" => ['required', 'date_format:Y-m-d', 'after_or_equal:start_date'],
            "status" => ['required', 'string'],
        ];
    }
}
