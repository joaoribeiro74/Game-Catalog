<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'email' => 'required|email:rfc,dns|max:255|unique:users,email',
            'username' => 'required|string|min:4|max:32|regex:/^[a-zA-Z0-9_-]+$/|unique:users,username',
            'password' => 'required|string|min:8|regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).+$/|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'password.regex' => 'A senha deve conter pelo menos uma letra maiúscula, um número e um caractere especial.',
        ];
    }
}
