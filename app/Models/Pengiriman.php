<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    use HasFactory;

    protected $table = 'pengiriman';


    protected $fillable = [
        'permintaan_id',
        'status_pengiriman_id',
        'tanggal_pengiriman',
        ];

        public function permintaan()
        {
            return $this->belongsTo(Permintaan::class, "permintaan_id");
        }

        public function statuspengiriman()
        {
            return $this->belongsTo(StatusPengiriman::class, "status_pengiriman_id");
        }
}
