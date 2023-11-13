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

        $this->barang = Stock_in_detail::join('barang', 'barang.id', '=', 'stock_in_detail.barang_id')
                    ->join('stock_in', 'stock_in.id', '=', 'stock_in_detail.brand_id')
                    ->get();
    }

    public function index()
    {
        $stock_in_detail = Stock_in_Detail::latest()->get();
        return response()->json([
            'data' => Stock_in_DetailResource::collection($stock_in_detail),
            'message' => 'ini stock in detail',
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
    public function store(Stock_in_DetailRequest $request)
    {
        return DB::transaction(function() use ($request) {

            $filename = "";
            $stock_in_detail = new Stock_in_Detail();
            $stock_in_detail->barang_id = $request->barang_id;
            $stock_in_detail->stock_in_id = $request->stock_in_id;
            $stock_in_detail->serial_number = $request->serial_number;
            $stock_in_detail->serial_number_manufaktur = $request->serial_number_manufaktur;
            $stock_in_detail->status = $request->status;
            $result = $stock_in_detail->save();

            if ($result) {
                return response()->json([
                    "status" => 200,
                    "pesan" => "Data Berhasil di Tambahkan",
                    "data" => $stock_in_detail
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
            $data = $this->stock_in_detail->findOrFail($id);
            if(is_null($data)){
                return $this->sendError('Data Stock In Detail tidak ditemukan');
            }

            return response()->json([
                "status" => 200,
                "pesan" => "Data Stock In Detail yang dipilih",
                "data" => $data,
            ]);
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
        DB::beginTransaction();
        try {
            $stock_in_detail = Stock_in_Detail::findOrFail($id);

            $stock_in_detail->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Success Menghapus Data!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menghapus data. Error: ' . $e->getMessage(),
            ]);
        }
    }
}
