<?php

namespace App\Http\Requests;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class Stock_OutRequest extends FormRequest
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
        'permintaan_id' => 'required|exists:permintaan,id',
        'kode_do' => 'required|string|max:255',
        'nama_do' => 'required|string|max:255',
        'kuantiti' => 'required|string|max:255',
        'tanggal_permintaan' => 'nullable|date',
        'tanggal_selesai' => 'nullable|date',
        'tanggal_pembatalan' => 'nullable|date',
    ];
}

public function messages(): array
{
    return [
        'permintaan_id.required' => 'Field permintaan_id wajib diisi.',
        'permintaan_id.exists' => 'Permintaan dengan ID yang dipilih tidak valid.',
        'kode_do.required' => 'Field kode_do wajib diisi.',
        'kode_do.string' => 'Field kode_do harus berupa string.',
        'kode_do.max' => 'Field kode_do maksimal 255 karakter.',
        'nama_do.max' => 'Field nama_do maksimal 255 karakter.',
        'kuantiti.required' => 'Field kuantiti wajib diisi.',
        'kuantiti.string' => 'Field kuantiti harus berupa string.',
        'kuantiti.max' => 'Field kuantiti maksimal 255 karakter.',
        'tanggal_permintaan.date' => 'Field tanggal_permintaan harus berupa tanggal yang valid.',
        'tanggal_selesai.date' => 'Field tanggal_selesai harus berupa tanggal yang valid.',
        'tanggal_pembatalan.date' => 'Field tanggal_pembatalan harus berupa tanggal yang valid.',
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
