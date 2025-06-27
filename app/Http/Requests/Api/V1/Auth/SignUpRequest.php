<?php

namespace App\Http\Requests\Api\V1\Auth;

use App\Http\Enums\QuestionAnswer;
use App\Http\Enums\UserType;
use App\Rules\Phone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class SignUpRequest extends FormRequest
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
            'user_name' => ['required', 'string', 'max:255', 'unique:users,user_name'],
            'email' => ['required', 'email:rfc,dns', 'unique:users,email'],
            'password' => [Password::min(8)->letters()->numbers()->symbols(),'confirmed','required'],
            'phone' => ['required', new Phone(), 'digits:11','unique:users,phone'],
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'answer' => ['required', 'exists:login_answers,id'],
            'type' => ['required', Rule::enum(UserType::class)],
        ];
    }
}
