<?php

namespace App\Http\Requests;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class BarangRequest extends FormRequest
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
            'kategori_id' => 'required|exists:kategori,id',
            'brand_id' => 'required|exists:brand,id',
            'nama_barang' => 'required|string|max:255',
            'gambar_barang' => 'string|sometimes|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'kategori_id.required' => 'Kategori harus diisi.',
            'kategori_id.exists' => 'Kategori yang dipilih tidak valid.',
            'brand_id.required' => 'Brand harus diisi.',
            'brand_id.exists' => 'Brand yang dipilih tidak valid.',
            'nama_barang.required' => 'Nama barang harus diisi.',
            'nama_barang.string' => 'Nama barang harus berupa teks.',
            'gambar_barang.image' => 'File harus berupa gambar.',
            'gambar_barang.mimes' => 'Format gambar tidak valid. Hanya format jpeg, png, dan gif yang diizinkan.',
            'gambar_barang.max' => 'Ukuran gambar terlalu besar. Maksimum 2MB.'
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
