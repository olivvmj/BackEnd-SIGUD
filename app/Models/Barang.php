<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';


    protected $fillable = [
        'kategori_id',
        'brand_id',
        'nama_barang',
        'gambar_barang',
        ];

        public function kategori()
        {
            return $this->belongsTo(Kategori::class, "kategori_id");
        }

        public function brand()
        {
            return $this->belongsTo(Brand::class, "brand_id");
        }

        public function detailStock()
        {
            return $this->hasMany(Stock_in_Detail::class);
        }
}
