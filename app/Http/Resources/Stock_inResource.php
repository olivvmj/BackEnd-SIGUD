<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Stock_inResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'manufaktur_id' => $this->manufaktur_id,
            'nama_stock_in' => $this->nama_stock_in,
            'kuantiti' => $this->kuantiti,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
