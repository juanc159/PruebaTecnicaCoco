<?php

namespace App\Http\Requests\Reservation;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ReservationStoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'resource_id' => 'required|exists:resources,id',
            'reserved_at' => 'required',
            'duration' => 'required|integer',
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'resource_id.required' => 'El recurso es obligatorio.',
            'resource_id.exists' => 'El recurso seleccionado no existe.',

            'reserved_at.required' => 'La fecha y hora de la reserva son obligatorias.',
            'reserved_at.date' => 'La fecha y hora de la reserva no tienen un formato válido.',

            'duration.required' => 'La duración de la reserva es obligatoria.',
            'duration.integer' => 'La duración debe ser un número entero.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([]);
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'code' => 422,
            'message' => 'Se evidencia algunos errores',
            'errors' => $validator->errors(),
        ], 422));
    }
}
