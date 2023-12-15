<?php

namespace App\Http\Controllers\Api;

use App\Models\Barang;
use App\Models\Stock_Out;
use Illuminate\Http\Request;
use App\Models\Stock_out_Detail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Stock_out_DetailRequest;
use App\Http\Resources\Stock_out_DetailResource;

class Stock_out_DetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $stock_out_detail;
    protected $barang;
    protected $stock_out;

    public function __construct(Stock_out_Detail $stock_out_detail, Barang $barang, Stock_Out $stock_out)

    {
        $this->stock_out_detail = $stock_out_detail;
        $this->barang = $barang;
        $this->stock_out = $stock_out;

        $this->stock_out_detail = Stock_out_Detail::join('barang', 'barang.id', '=', 'stock_out_detail.barang_id')
                    ->join('stock_out', 'stock_out.id', '=', 'stock_out_detail.stock_out_id')
                    ->get();
    }
    public function index()
    {
        $stock_out_detail = Stock_out_Detail::latest()->get();
        return response()->json([
            'data' => Stock_out_DetailResource::collection($stock_out_detail),
            'message' => 'ini stock out detail',
            'success' => true,
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
    public function store(Stock_out_DetailRequest $request)
    {
        return DB::transaction(function() use ($request) {

            $filename = "";
            $stock_out_detail = new Stock_out_Detail();
            $stock_out_detail->barang_id = $request->barang_id;
            $stock_out_detail->stock_out_id = $request->stock_out_id;
            $stock_out_detail->permintaan_id = $request->permintaan_id;
            $stock_out_detail->serial_number = $request->serial_number;
            $stock_out_detail->serial_number_manufaktur = $request->serial_number_manufaktur;
            $stock_out_detail->status = $request->status;
            $result = $stock_out_detail->save();

            if ($result) {
                return response()->json([
                    "status" => 200,
                    "pesan" => "Data Berhasil di Tambahkan",
                    "data" => $stock_out_detail
                ]);
            } else {
                return response()->json(['success' => false]);
            }
        });
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return DB::transaction(function () use ($id) {
            $data = Stock_out_Detail::findOrFail($id);
            if(is_null($data)){
                return $this->sendError('Data Stock out Detail tidak ditemukan');
            }

            return response()->json([
                "status" => 200,
                "pesan" => "Data Stock out Detail yang dipilih",
                "data" => $data,
            ]);
        });
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stock_out_Detail $stock_out_Detail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Stock_out_DetailRequest $request, string $id)
    {
        return DB::transaction(function() use ($request, $id) {

            $update = Stock_out_Detail::findOrFail($id);

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
        DB::beginTransaction();
        try {
            $stock_out_detail = Stock_out_Detail::findOrFail($id);

            $stock_out_detail->delete();

            DB::commit();

            return response()->json([
                'kode' => 200,
                'status' => true,
                'message' => 'Success Menghapus Data!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'kode' => 500,
                'status' => false,
                'message' => 'Terjadi kesalahan saat menghapus data. Error: ',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
