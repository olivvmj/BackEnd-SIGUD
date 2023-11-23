<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class BrandRequest extends FormRequest
{
    public function messages(): array
    {   
        return [
            "required" => "Kolom :attribute tidak boleh kosong",
        ]; 
    }

    public function rules(): array
    {
        return [
            "nama_brand" => "required",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = new JsonResponse([
            "messages" => $validator->errors(),
            'status' => 422
        ], 422);
        throw new ValidationException($validator, $response);
    }
}