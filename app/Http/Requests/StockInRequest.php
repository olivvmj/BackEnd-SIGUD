<?php

namespace App\Http\Requests;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class StockInRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Ubah menjadi false jika memerlukan otentikasi
    }

    public function rules()
    {
        return [
            'supplier_id' => 'required|exists:supplier,id',
            'nama_stock_in' => 'required|string|max:255',
            'kuantiti' => 'required|numeric|min:1',
            'barang'=>'required|array',
            'barang.*.barang_id' => 'required|exists:barang,id',
            'barang.*.serial_number' => 'required|string',
            'barang.*.serial_number_manufaktur' => 'required|string|max:255',
            'barang.*.status' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'supplier_id.required' => 'Data supplier tidak boleh kosong.',
            'supplier_id.exists' => 'Supplier tidak valid.',
            'nama_stock_in.required' => 'Keterangan Stock In tidak boleh kosong',
            'nama_stock_in.max' => 'Keterangan Stock In tidak boleh lebih dari :max karakter.',
            'kuantiti.required' => 'Kuantitas tidak boleh kosong.',
            'kuantiti.numeric' => 'Kuantitas harus berupa angka.',
            'kuantiti.min' => 'Kuantitas harus minimal :min.',
            'barang_id.required' => 'Data Barang tidak boleh kosong.',
            'barang_id.exists' => 'Data Barang tidak valid.',
            'serial_number.required' => 'Nomor Serial tidak boleh kosong.',
            'serial_number.max' => 'Nomor Serial tidak boleh lebih dari :max karakter.',
            'serial_number_manufaktur.required' => 'Nomor Serial Manufaktur tidak boleh kosong.',
            'serial_number_manufaktur.max' => 'Nomor Serial Manufaktur tidak boleh lebih dari :max karakter.',
            'status.required' => 'Status tidak boleh kosong.',
            'status.max' => 'Status tidak boleh lebih dari :max karakter.',
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
