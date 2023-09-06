<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateTypeServiceRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'label' => 'required|string',
            'description' => 'required|string',
            'service_id'=> 'required|integer|exists:services,id',
            'durations' => 'required|array',
            'durations.*.price' => 'required|numeric',
            'durations.*.duration' => 'required|integer',
        ];
    }

        // Handle validation errors
        protected function failedValidation(Validator $validator)
        {
            throw new HttpResponseException(response()->json([
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 422));
        }
}
