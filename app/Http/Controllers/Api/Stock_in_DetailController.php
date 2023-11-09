<?php

namespace App\Http\Controllers\Api;

use in;
use App\Models\Stock_in;
use Illuminate\Http\Request;
use App\Models\Stock_in_Detail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Stock_in_DetailRequest;
use App\Http\Resources\Stock_in_DetailResource;

class Stock_in_DetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $stock_in_detail;
    protected $barang;
    protected $stock_in;

    public function __construct(Stock_in_Detail $stock_in_detail, Stock_in $barang, Stock_in $stock_in)

    {
        $this->stock_in_detail = $stock_in_detail;
        $this->barang = $barang;
        $this->stock_in = $stock_in;
    }

    public function index()
    {
        $stock_in_detail = Stock_in_Detail::latest()->get();
        return response()->json([
            'data' => Stock_in_DetailResource::collection($stock_in_detail),
            'message' => 'ini stock in detail',
            'success' => 200
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
    public function store(Stock_in_DetailRequest $request)
    {
        return DB::transaction(function() use ($request) {

            $this->stock_in_detail->create($request->all());

            return response()->json([
                "status" => 201,
                "pesan" => "Data Berhasil di Tambahkan",
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
            $data = $this->stock_in_detail->findOrFail($id);

            return new Stock_in_DetailResource($data);
        });
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Stock_in_DetailRequest $request, string $id)
    {
        return DB::transaction(function() use ($request, $id) {

            $update = $this->stock_in_detail->findOrFail($id);

            $update->update($request->all());

            return response()->json([
                "status" => 200,
                "pesan" => "Data Berhasil di Ubah",
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
            $this->stock_in_detail->destroy($id);

            return response()->json([
                "status" => 204,
                "pesan" => "Data Berhasil di Hapus"
            ]);
        });
    }
}
