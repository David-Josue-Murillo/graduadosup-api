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
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'quantity' => filter_var($this->quantity, FILTER_SANITIZE_NUMBER_INT),
            'year' => filter_var($this->year, FILTER_SANITIZE_NUMBER_INT),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'quantity' => 'required|integer|min:0|max:2999',
            'year' => 'required|integer|min:2018|max:'. date('Y'),
            'campus_id' => 'required|integer|exists:campus,id',
            'career_id' => 'required|integer|exists:careers,id',
        ];

        // Change rules based on HTPP method
        if($this->method() === 'GET'){
            $rules['quantity'] = 'integer|min:0|max:2999';
            $rules['year'] = 'integer|min:2018|max:'. date('Y');
            $rules['campus_id'] = 'integer|exists:campus,id';
            $rules['career_id'] = 'integer|exists:careers,id';
        }

        return $rules;
    }

    /**
     * Custom messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'quantity.required' => 'La cantidad es obligatorio',
            'quantity.integer' => 'La cantidad debe ser un número entero',
            'quantity.min' => 'La cantidad debe ser al menos :min',
            'quantity.max' => 'La cantidad no puede ser mayor a :max',

            'year.required' => 'El año es obligatorio',
            'year.integer' => 'El año debe ser un número entero',
            'year.min' => 'El año debe ser mayor o igual a :min',
            'year.max' => 'El año debe ser menor o igual a :max',

            'campus_id.required' => 'El id del campus es obligatorio',
            'campus_id.integer' => 'El ID del campus debe ser un número entero',
            'campus_id.exists' => 'El campus seleccionado no existe',

            'career_id.required' => 'El id de la carrera es obligatorio',
            'career_id.integer' => 'El ID de la carrera debe ser un número entero',
            'career_id.exists' => 'La carrera seleccionado no existe',
        ];
    }
}
