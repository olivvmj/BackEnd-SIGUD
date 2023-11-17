<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock_in extends Model
{
    use HasFactory;

    protected $table = 'stock_in';


    protected $fillable = [
        'manufaktur_id',
        'nama_stock_in',
        'kuantiti',
        ];

        public function kategori()
        {
            return $this->belongsTo(Manufaktur::class, "manufraktur_id");
        }
}
