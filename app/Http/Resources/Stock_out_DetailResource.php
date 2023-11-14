<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Stock_out_DetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'barang_id' => $this->barang_id,
            'stock_out_id' => $this->stock_out_id,
            'serial_number' => $this->serial_number,
            'serial_number_manufaktur' => $this->serial_number_manufaktur,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
