<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permintaan extends Model
{
    use HasFactory;

    protected $table = 'permintaan';


    protected $fillable = [
        'users_id',
        'barang_id',
        'tanggal_permintaan',
        'alamat_penerima',
        'nama_penerima',
        ];

        public function users()
        {
            return $this->belongsTo(User::class, "users_id");
        }

        public function barang()
        {
            return $this->belongsTo(Barang::class, "barang_id");
        }
}
