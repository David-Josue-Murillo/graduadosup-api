<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NumGraduatesRequest extends FormRequest
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
            'quantity' => 'required|integer',
            'year' => 'required|integer',
            'campus_id' => 'required|integer',
            'career_id' => 'required|integer',
        ];
    }

    /**
     * Custom messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'quantity.required' => 'La cantidad es obligatorio',
            'year.required' => 'El año es obligatorio',
            'campus_id.required' => 'El id del campus es obligatorio',
            'career_id.required' => 'El id de la carrera es obligatorio',
        ];
    }
}
