<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CampuRequest extends FormRequest
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
            'name' => 'required|max:100|min:10|unique:careers|string|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
        ];
    }


    /**
     * Custom messages for validation rules.
     */
    public function messages(): array {
        return [
            'name.required' => 'El campo nombre es obligatorio',
            'name.max' => 'El nombre del campus no debe exceder los 100 caracteres',
            'name.min' => 'El nombre del campus debe exceder los 10 caracteres.',
            'name.unique' => 'Ya existe un campus con este nombre.',
            'name.string' => 'El nombre del campus debe ser una cadena de texto.',
            'name.regex' => 'El nombre del campus debe contener solo caracteres alfanuméricos.'
        ];
    }
}
