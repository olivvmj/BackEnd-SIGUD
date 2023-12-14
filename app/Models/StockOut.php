<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOut extends Model
{
    use HasFactory;

    protected $table = 'stock_out';


    protected $fillable = [
        'permintaan_id',
        'kode_do',
        'nama_do',
        'kuantiti',
        'tanggal_permintaan',
        'tanggal_selesai',
        'tanggal_pembatalan',
        ];

        public function permintaan()
        {
            return $this->belongsTo(Permintaan::class, "permintaan_id");
        }

        public function details()
        {
            return $this->hasMany(StockOutDetail::class);
        }
}
