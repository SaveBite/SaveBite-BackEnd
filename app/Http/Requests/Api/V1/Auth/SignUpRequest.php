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
            'user_name' => ['required', 'string'],
            'email' => ['required', 'email:rfc,dns'],
            'password' => [Password::min(8)->letters()->numbers()->symbols(),'confirmed'],
            'phone' => ['required', new Phone(), 'digits:11'],
            'image' => 'required|image|mimes:png,jpg,jpeg',
            'answer' => ['required', 'exists:login_answers,id'],
            'type' => ['required', Rule::enum(UserType::class)],
        ];
    }
}
