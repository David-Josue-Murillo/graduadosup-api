<?php

namespace App\Http\Requests\auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

     /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->login) {
            $this->merge([
                'login' => strtolower(trim($this->login)),
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
            'login' => 'required|string|max:100|min:4',
            'password' => 'required|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array {
        return [
            'login.required' => 'El correo electrónico o nombre de usuario es obligatorio.',
            'login.min' => 'El correo electrónico o nombre de usuario debe tener al menos :min caracteres.',
            'login.max' => 'El correo electrónico o nombre de usuario no puede tener más de :max caracteres.',
            'password.required' => 'La contraseña es obligatoria.'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function getCredentials(): array
    {
        $login = $this->get('login');

        // Determinar si el login es un email o username
        $loginType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        return [
            $loginType => $login,
            'password' => $this->get('password'),
        ];
    }
}
