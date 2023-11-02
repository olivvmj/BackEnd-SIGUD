<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BarangResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'kategori_id' => $this->kategori_id,
            'brand_id' => $this->brand_id,
            'nama_barang' => $this->nama_barang,
            'serial_number' => $this->serial_number,
            'gambar_barang' => $this->gambar_barang,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
