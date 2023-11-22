<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\StockInDetailResource;
use Illuminate\Http\Resources\Json\JsonResource;

class StockInResource extends JsonResource
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
            'nama_supplier' => $this->supplier->nama_supplier,
            'nama_stock_in' => $this->nama_stock_in,
            'kuantiti' => $this->kuantiti,
            'details' => StockInDetailResource::collection($this->details),
        ];
    }
}
