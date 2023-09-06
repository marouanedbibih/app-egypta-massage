<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreServiceRequest extends FormRequest
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
            'label' => 'required|string|max:255',
            'description' => 'required|string',
            'categorie' => 'required|int',
            'type_services' => 'required|array|min:1',
            'type_services.*.label' => 'required|string|max:255',
            'type_services.*.description' => 'required|string'
        ];
        
    }

    public function failedValidation(Validator $validator ){
        throw new HttpResponseException(response()->json([
            'message' => 'Validation Errors',
            'errors' => $validator->errors()
        ],422));

    }
}
