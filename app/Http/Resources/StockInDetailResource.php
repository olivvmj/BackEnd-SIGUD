<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockInDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nama_barang' => $this->barang->nama_barang,
            'gambar_barang' => $this->barang->gambar_barang,
            'stock_in_id' => $this->stock_in->nama_stock_in,
            'serial_number' => json_decode($this->serial_number),
            'serial_number_manufaktur' => json_decode($this->serial_number_manufaktur),
            'status' => $this->status,
        ];
    }
}
