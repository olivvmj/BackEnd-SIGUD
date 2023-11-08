<?php

namespace App\Http\Requests;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class StockRequest extends FormRequest
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
            'stock_in' => 'required|exists:stock,id',
            'stock_out' => 'required|integer|min:1',
            'total_stock' => 'required|integer|min:1',
        ];
    }


    public function messages()
{
    return [
        'barang_id.required' => 'Barang harus diisi.',
        'barang_id.exists' => 'Barang yang dipilih tidak valid.',
        'stock_in.required' => 'Stock harus diisi.',
        'stock_in.exists' => 'Stock yang dipilih tidak valid.',
        'stock_out.required' => 'Stock Out harus diisi.',
        'stock_out.integer' => 'Stock Out harus berupa angka.',
        'stock_out.min' => 'Stock Out minimal harus bernilai 1.',
        'total_stock.required' => 'Total Stock harus diisi.',
        'total_stock.integer' => 'Total Stock harus berupa angka.',
        'total_stock.min' => 'Total Stock minimal harus bernilai 1.'
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
