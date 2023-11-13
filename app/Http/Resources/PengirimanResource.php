<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PengirimanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'permintaan_id' => $this->permintaan_id,
            'status_pengiriman_id' => $this->status_pengiriman_id,
            'tanggal_pengiriman'=> $this->tanggal_pengiriman,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
