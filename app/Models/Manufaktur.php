<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufaktur extends Model
{
    use HasFactory;


    protected $table = 'manufaktur';


    protected $fillable = [
        'nama_manufaktur',
        ];
}
