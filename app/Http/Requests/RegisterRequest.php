<?php

namespace App\Http\Requests;

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
        $rules = [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['nullable', 'string', 'in:admin,user'],
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name is required.',
            'name.max' => 'The name must not exceed 100 characters.',
            'email.required' => 'The email is required.',
            'email.email' => 'The email must be a valid address.',
            'email.max' => 'The email must not exceed 150 characters.',
            'email.unique' => 'That email is already registered.',
            'password.required' => 'The password is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'role.in' => 'The role must be admin or user.',
        ];
    }
}
