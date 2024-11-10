<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FacultyRequest extends FormRequest
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
     * Mensajes personalizados para las reglas de validación.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.max' => 'El nombre de la facultad no debe exceder los 100 caracteres.',
            'name.min' => 'El nombre de la facultads debe exceder los 10 caracteres.',
            'name.unique' => 'Ya existe una facultad con este nombre.',
            'name.string' => 'El nombre de la facultad debe ser una cadena de texto.',
            'name.regex' => 'El nombre de la facultad debe contener solo caracteres alfanuméricos.'
        ];
    }
}
