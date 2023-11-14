<?php

namespace App\Http\Requests;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class Stock_out_DetailRequest extends FormRequest
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
            'barang_id' => 'required|exists:barang,id',
            'stock_out_id' => 'required|exists:stock_out,id',
            'serial_number' => 'required|string|max:255',
            'serial_number_manufaktur' =>'required|string|max:255',
            'status' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'barang_id.required' => 'Field barang_id wajib diisi.',
            'barang_id.exists' => 'Barang dengan ID yang dipilih tidak valid.',
            'stock_out_id.required' => 'Field stock_out_id wajib diisi.',
            'stock_out_id.exists' => 'Stock_out dengan ID yang dipilih tidak valid.',
            'serial_number.required' => 'Field serial_number wajib diisi.',
            'serial_number.string' => 'Field serial_number harus berupa string.',
            'serial_number.max' => 'Field serial_number maksimal 255 karakter.',
            'serial_number_manufaktur.required' => 'Field serial_number_manufaktur wajib diisi.',
            'serial_number_manufaktur.string' => 'Field serial_number_manufaktur harus berupa string.',
            'serial_number_manufaktur.max' => 'Field serial_number_manufaktur maksimal 255 karakter.',
            'status.required' => 'status wajib diisi',
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
