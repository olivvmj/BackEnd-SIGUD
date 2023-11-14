<?php

namespace App\Http\Controllers\Api;

use App\Models\Stock_in;
use App\Models\Manufaktur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Stock_inRequest;
use App\Http\Resources\Stock_inResource;

class Stock_inController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $stock_in;

    protected $manufaktur;

    public function __construct(Stock_in $stock_in, Manufaktur $manufaktur)
    {
        $this->stock_in = $stock_in;
        $this->manufaktur = $manufaktur;

        $this->stock_in = Stock_in::join('manufaktur', 'manufaktur_id', '=', 'stock_in.manufaktur_id')
                        ->get();
    }
    public function index()
    {
        $stock_in = Stock_in::latest()->get();
        return response()->json([
            'data' => Stock_inResource::collection($stock_in),
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
    public function store(Stock_inRequest $request)
    {
        return DB::transaction(function() use ($request) {
            $filename = "";
            $stock_in = new Stock_in();
            $stock_in->manufaktur_id = $request->manufaktur_id;
            $stock_in->nama_stock_in = $request->nama_stock_in;
            $stock_in->kuantiti = $request->kuantiti;
            $result = $stock_in->save();

            if ($result) {
                return response()->json([
                    "status" => 200,
                    "pesan" => "Data Berhasil di Tambahkan",
                    "data" => $stock_in
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
            $data = $this->stock_in->findOrFail($id);
            if(is_null($data)){
                return $this->sendError('Data Stock In tidak ditemukan');
            }

            return response()->json([
                "status" => 200,
                "pesan" => "Data Stock In yang dipilih",
                "data" => $data,
            ]);
        });
    }

    /**
     * Show the form for editing the specified resource.
     */


    /**
     * Update the specified resource in storage.
     */
    public function update(Stock_inRequest $request, string $id)
    {
        return DB::transaction(function() use ($request, $id) {

            $update = $this->stock_in->findOrFail($id);

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
            $stock_in = Stock_in::findOrFail($id);

            $stock_in->delete();

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
