<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Stock_OutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'permintaan_id' => $this->permintaan_id,
            'kode_do' => $this->kode_do,
            'nama_do' => $this->nama_do,
            'kuantiti' => $this->kuantiti,
            'tanggal_permintaan' => $this->tanggal_permintaan,
            'tanggal_selesai' => $this->tanggal_selesai,
            'tanggal_pembatalan' => $this->tanggal_pembatalan,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
