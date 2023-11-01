<?php

namespace App\Http\Requests;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class KategoriRequest extends FormRequest
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
            'nama_kategori' => 'required|string|max:255',
        ];
    }

    public function message()
    {
        return [
            'nama_kategori.required' =>'kolom nama kategori wajib diisi',
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
