<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusPermintaan extends Model
{
    use HasFactory;

    protected $table = 'status_permintaan';

    protected $fillable = ['jenis_status', 'estimasi_tiba'];
}
