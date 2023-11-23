<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function messages(): array
    {
        return [
            "required" => "Kolom :attribute tidak boleh kosong",
            "min" => "Kolom :attribute minimal harus 8 Digit"
        ];
    }

    public function rules(): array
    {
        return [
            "username" => "required",
            "password" => "required|min:8"
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
