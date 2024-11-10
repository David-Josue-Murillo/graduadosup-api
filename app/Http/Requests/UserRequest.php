<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    protected function prepareForValidation():void {
        if($this->has('name')) {
            $this->merge([
                'name' => trim($this->name)
            ]);
        }

        if($this->has('email')) {
            $this->merge([
                'email' => trim($this->email)
            ]);
        }
        
        if($this->has('password')) {
            $this->merge([
                'password' => trim($this->password)
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|regex:/^[\pL\s\-]+$/u',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'string|'. Rule::in('admin', 'user')
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array{
        return [
            'name.required' => 'El nombre es obligatorio',
            'name.max' => 'El nombre no puede tener más de :max caracteres',
            'name.regex' => 'El nombre solo puede contener letras, espacios y guiones',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'El correo electrónico debe ser válido',
            'email.unique' => 'Este correo electrónico ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.confirmed' => 'La confirmación de contraseña no coincide',
            'password.min' => 'La contraseña debe tener al menos :min caracteres',
            'role.in' => 'El rol seleccionado no es válido'
        ];
    }
}
