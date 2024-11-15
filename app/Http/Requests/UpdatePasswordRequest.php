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
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules =  [
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ];

        return $rules;
    }

    public function messages(): array 
    {
        return [
            'password.required' => 'El campo contraseña es requerida',
            'password.string' => 'El campo contraseña debe ser un texto',
            'password.min' => 'El campo contraseña debe tener al menos :min caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',

            'password_confirmation.required' => 'El campo contraseña es requerida',
            'password_confirmation.string' => 'El campo contraseña debe ser un texto',
            'password_confirmation.min' => 'El campo contraseña debe tener al menos :min caracteres',
            
        ];
    }
}
