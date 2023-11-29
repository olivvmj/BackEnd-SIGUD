<?php

namespace App\Http\Controllers\Api;

use App\Models\Stock_Out;
use App\Models\Permintaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Stock_OutRequest;
use App\Http\Resources\Stock_OutResource;

class Stock_OutController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $stock_out;

    protected $permintaan;

    public function __construct(Stock_Out $stock_out, Permintaan $permintaan)
    {
        $this->stock_out = $stock_out;
        $this->permintaan = $permintaan;

        $this->stock_out = Stock_Out::join('permintaan', 'permintaan_id', '=', 'stock_out.permintaan_id')
                        ->get();
    }

    public function index()
    {
        $stock_out = Stock_Out::latest()->get();
        return response()->json([
            'data' => Stock_OutResource::collection($stock_out),
            'message' => 'ini stock in',
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
    public function store(Stock_OutRequest $request)
    {
        return DB::transaction(function() use ($request) {
            $filename = "";
            $stock_out = new Stock_Out();
            $stock_out->permintaan_id = $request->permintaan_id;
            $stock_out->kode_do = $request->kode_do;
            $stock_out->nama_do = $request->nama_do;
            $stock_out->kuantiti = $request->kuantiti;
            $stock_out->tanggal_permintaan = $request->tanggal_permintaan;
            $stock_out->tanggal_selesai = $request->tanggal_selesai;
            $stock_out->tanggal_pembatalan = $request->tanggal_pembatalan;
            $result = $stock_out->save();

            if ($result) {
                return response()->json([
                    "status" => 200,
                    "pesan" => "Data Berhasil di Tambahkan",
                    "data" => $stock_out
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
            $data = $this->stock_out->findOrFail($id);
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
     * Show the form for editing the specified resource.
     */
    public function edit(Stock_Out $stock_Out)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Stock_OutRequest $request, string $id)
    {
        return DB::transaction(function() use ($request, $id) {

            $update = Stock_Out::findOrFail($id);

            $update->update($request->all());

            return response()->json([
                "status" => 200,
                "pesan" => "Data Berhasil di Simpan",
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
            $stock_out = Stock_Out::findOrFail($id);

            $stock_out->delete();

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
