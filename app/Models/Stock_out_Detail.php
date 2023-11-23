<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock_out_Detail extends Model
{
    use HasFactory;

    protected $table = 'stock_out_detail';


    protected $fillable = [
        'barang_id',
        'stock_out_id',
        'permintaan_id',
        'serial_number',
        'serial_number_manufaktur',
        'status',
        ];

        public function barang()
        {
            return $this->belongsTo(Barang::class, "barang_id");
        }

        public function stock_out()
        {
            return $this->belongsTo(Stock_in::class, "stock_out_id");
        }

        public function permintaan()
        {
            return $this->belongsTo(Permintaan::class, "permintaan_id");
        }

}
