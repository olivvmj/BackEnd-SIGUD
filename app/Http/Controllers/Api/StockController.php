<?php

namespace App\Http\Controllers\Api;

use App\Models\Stock;
use Illuminate\Http\Request;
use App\Http\Requests\StockRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\StockResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;


class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $barang;

    protected $stock;

    public function __construct(Stock $barang, Stock $stock)
    {
        $this->stock = $stock;
        $this->barang = $barang;
    }

    public function index()
    {
        $stock = Stock::latest()->get();
        return response()->json([
            'data' => StockResource::collection($stock),
            'message' => 'ini stock',
            'success' => true
        ]);
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
    public function store(StockRequest $request)
    {
        return DB::transaction(function() use ($request) {

            $this->stock->create($request->all());

            return response()->json([
                "status" => true,
                "message" => "Data Berhasil di Tambahkan",
                "data" => $request->all()
            ]);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return DB::transaction(function () use ($id) {
            $data = $this->stock->findOrFail($id);

            return new StockResource($data);
        });
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stock $stock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StockRequest $request, string $id)
    {
        return DB::transaction(function() use ($request, $id) {

            $update = $this->stock->findOrFail($id);

            $update->update($request->all());

            return response()->json([
                "status" => true,
                "message" => "Data Berhasil di Simpan",
                "data" => $request->all()
            ]);

        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return DB::transaction(function () use ($id) {
            $this->stock->destroy($id);

            return response()->json([
                "status" => true,
                "message" => "Data Berhasil di Hapus"
            ]);

        });
    }
}
