<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermintaanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'users_id' => $this->users_id,
            'barang_id' => $this->barang_id,
            'tanggal_permintaan' => $this->tanggal_permintaan,
            'alamat_penerima' => $this->alamat_penerima,
            'nama_penerima' => $this->nama_penerima,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
