<?php

namespace App\Models;

use App\Models\Barang;
use App\Models\StockIn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockInDetail extends Model
{
    use HasFactory;

    protected $table = 'stock_in_detail';


    protected $fillable = [
        'barang_id',
        'stock_in_id',
        'serial_number',
        'serial_number_manufaktur',
        'status',
        ];

        public function barang()
        {
            return $this->belongsTo(Barang::class, 'barang_id');
        }

        public function stockIn()
        {
            return $this->belongsTo(StockIn::class, 'stock_in_id')->onDelete('cascade');
        }
}
