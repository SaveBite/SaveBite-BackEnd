<?php

namespace App\Http\Requests\Dashboard\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InfoSettingsRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required'  , 'string'],
            'image' => 'nullable',
            'email' => ['required' , Rule::unique('managers' , 'email')->ignore(auth()->id())],
            'phone' => ['nullable' , Rule::unique('managers' , 'phone')->ignore(auth()->id())],
            'current_status' => 'nullable'
        ];
    }
}
