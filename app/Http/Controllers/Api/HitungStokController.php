<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\StockInDetail;
use App\Models\Stock_out_Detail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;

class HitungStokController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function hitungTotalStok()
    {
        try {
            $totalStokMasuk = DB::table('stock_in_detail')
                ->count();

            $totalStokKeluar = DB::table('stock_out_detail')
                ->count(); 

            $totalStok = $totalStokMasuk - $totalStokKeluar;

            return response()->json([
                'kode' => 200,
                'status' => true,
                'message' => 'Total Stok Barang',
                'total_stok' => $totalStok,
            ]);

        } catch (QueryException $e) {
            return response()->json([
                'kode' => 500,
                'status' => false,
                'message' => 'Terjadi kesalahan saat menghitung total stok',
                'error' => $e->getMessage()
            ]);
        }
    }
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
