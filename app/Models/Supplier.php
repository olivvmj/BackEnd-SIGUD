<?php

namespace App\Models;

use App\Models\StockIn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;


    protected $table = 'supplier';


    protected $fillable = [
        'nama_supplier',
        ];

    public function stockIn()
    {
        return $this->hasMany(StockIn::class);
    }
}
