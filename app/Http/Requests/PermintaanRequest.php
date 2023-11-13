<?php

namespace App\Http\Requests;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class PermintaanRequest extends FormRequest
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
            'users_id' => 'required|exists:users,id',
            'barang_id' => 'required|exists:barang,id',
            'tanggal_permintaan' => 'required|date',
            'alamat_penerima' => 'required|string',
            'nama_penerima' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'users_id.required' => 'Users harus diisi.',
            'users_id.exists' => 'Users yang dipilih tidak valid.',
            'barang_id.required' => 'Barang harus diisi.',
            'barang_id.exists' => 'Barang yang dipilih tidak valid.',
            'tanggal_permintaan.required' => 'Tanggal Permintaan harus diisi.',
            'tanggal_permintaan.date' => 'Tanggal Permintaan harus berupa tanggal yang valid.',
            'alamat_penerima.required' => 'Alamat Penerima harus diisi.',
            'alamat_penerima.string' => 'Alamat Penerima harus berupa teks.',
            'nama_penerima.required' => 'Nama Penerima harus diisi.',
            'nama_penerima.string' => 'Nama Penerima harus berupa teks.',
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
