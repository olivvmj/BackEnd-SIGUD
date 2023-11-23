<?php

namespace App\Models;

use App\Models\Supplier;
use App\Models\StockInDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockIn extends Model
{
    use HasFactory;

    protected $table = 'stock_in';


    protected $fillable = [
        'supplier_id',
        'nama_stock_in',
        'kuantiti',
        ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function details()
    {
        return $this->hasMany(StockInDetail::class);
    }
}
