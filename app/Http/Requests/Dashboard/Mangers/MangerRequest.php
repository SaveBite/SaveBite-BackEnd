<?php

namespace App\Http\Requests\Dashboard\Mangers;

use App\Rules\Phone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class MangerRequest extends FormRequest
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
            'id' => ['nullable',Rule::exists('managers', 'id')] ,
            'name' => 'required|string',
            'email' => [
                'required',
                'email:rfc,dns',
                $this->method() == 'POST'
                    ? Rule::unique('managers', 'email')
                    : Rule::unique('managers', 'email')->ignore($this->id, 'id')
            ],
            'phone' => [
                'required',
                new Phone,
                $this->method() == 'POST'
                    ? Rule::unique('managers', 'phone')
                    : Rule::unique('managers', 'phone')->ignore($this->id, 'id'),
            ],
            'password' => $this->method() == 'POST'?Password::min(8)->required():'nullable',
            'image' => ['nullable', 'exclude', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'is_active'=>'in:on,',

        ];
    }
}
