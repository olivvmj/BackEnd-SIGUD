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
    }
    public function index()
    {
        $stock = Stock_in::latest()->get();
        return response()->json([
            'data' => Stock_inResource::collection($stock),
            'message' => 'ini stock in',
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
    public function store(Stock_inRequest $request)
    {
        return DB::transaction(function() use ($request) {

            $this->stock_in->create($request->all());

            return response()->json([
                "status" => true,
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
            $data = $this->stock_in->findOrFail($id);

            return new Stock_inResource($data);
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
                "status" => true,
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
        return DB::transaction(function () use ($id) {
            $this->stock_in->destroy($id);

            return response()->json([
                "status" => true,
                "pesan" => "Data Berhasil di Hapus"
            ]);

        });
    }
}
