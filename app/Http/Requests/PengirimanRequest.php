<?php

namespace App\Http\Requests;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class PengirimanRequest extends FormRequest
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
            'permintaan_id' => 'required',
            'status_pengiriman' => 'required',
            'tanggal_pengiriman' => 'date_format:Y-m-d'
        ];
    }

    public function messages(): array
    {
        return [
            'permintaan_id.required' => 'Field permintaan_id wajib diisi.',
            'status_pengiriman.required' => 'Field status_pengiriman wajib diisi.',
            'tanggal_pengiriman.date_format' => 'Format tanggal_pengiriman harus YYYY-MM-DD.'
        ];
    }

}
