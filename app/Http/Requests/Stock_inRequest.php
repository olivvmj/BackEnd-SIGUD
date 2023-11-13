<?php

namespace App\Http\Requests;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class Stock_inRequest extends FormRequest
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
            'manufaktur_id' => 'required|exists:manufaktur,id',
            'nama_stock_in' => 'required|string|max:255',
            'kuantiti' =>'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'manufaktur_id.required' => 'Manufaktur harus diisi.',
            'manufaktur_id.exists' => 'Manufaktur yang dipilih tidak valid.',
            'nama_stock_in.required' => 'Nama Stock In harus diisi.',
            'nama_stock_in.string' => 'Nama Stock In harus berupa teks.',
            'nama_stock_in.max' => 'Nama Stock In tidak boleh lebih dari :max karakter.',
            'kuantiti.required' => 'Kuantiti harus diisi.',
            'kuantiti.string' => 'Kuantiti harus berupa teks.',
            'kuantiti.max' => 'Kuantiti tidak boleh lebih dari :max karakter.',
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
