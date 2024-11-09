<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CareerRequest extends FormRequest
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
        $this->merge([
            'name' => trim($this->name),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:100|min:10|unique:careers|string|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'faculty_id' => 'required|integer|exists:faculties,id'
        ];
    }

     /**
     * Custom messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El campo nombre de la carrera es obligatorio.',
            'name.max' => 'El nombre no debe exceder los 100 caracteres.',
            'name.min' => 'El nombre debe exceder los 10 caracteres.',
            'name.unique' => 'Ya existe una carrera con este nombre.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.regex' => 'El nombre debe contener solo caracteres alfanuméricos.',
            'faculty_id.required' => 'El campo facultad es obligatorio.',
            'faculty_id.integer' => 'El ID de la facultad debe ser un número entero.',
            'faculty_id.exists' => 'La facultad seleccionada no existe.'
        ];
    }
}
