<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules =  [
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ];

        return $rules;
    }

    public function messages(): array 
    {
        return [
            'current_password.required' => 'El campo contraseña actual es requerida',
            'current_password.string' => 'El campo contraseña actual debe ser un texto',

            'password.required' => 'El campo contraseña es requerida',
            'password.string' => 'El campo contraseña debe ser un texto',
            'password.min' => 'El campo contraseña debe tener al menos :min caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
        ];
    }
}
