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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:100|unique:careers',
            'faculty_id' => 'required|exists:faculties,id'
        ];
    }

     /**
     * Custom messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.max' => 'El nombre no debe exceder los 100 caracteres.',
            'name.unique' => 'El nombre ya existe en la base de datos.',
            'faculty_id.required' => 'El campo facultad es obligatorio.',
            'faculty_id.exists' => 'La facultad seleccionada no existe.'
        ];
    }
}
