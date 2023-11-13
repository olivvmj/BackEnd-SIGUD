<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class StatusPengirimanRequest extends FormRequest
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
            "jenis_status" => "required",
            "notes" => "nullable",
            "tanggal" => "required"
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = new JsonResponse([
            "messages" => $validator->errors(),
            "status" => 422
        ], 422);
        throw new ValidationException($validator, $response);
    }
}