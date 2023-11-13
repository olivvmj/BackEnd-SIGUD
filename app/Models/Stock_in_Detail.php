<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock_in_Detail extends Model
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
            return $this->belongsTo(Barang::class, "barang_id");
        }

        public function stock_in()
        {
            return $this->belongsTo(Stock_in::class, "stock_in_id");
        }

}
