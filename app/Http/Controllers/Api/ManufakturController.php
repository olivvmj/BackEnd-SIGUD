<?php

namespace App\Http\Controllers\Api;

use App\Models\Manufaktur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\ManufakturRequest;
use App\Http\Resources\ManufakturResource;

class ManufakturController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $manufaktur;

    public function __construct(Manufaktur $manufaktur)
    {
        $this->manufaktur = $manufaktur;
    }
    public function index()
    {
        $manufaktur = Manufaktur::latest()->get();
        return response()->json([
            'data' => ManufakturResource::collection($manufaktur),
            'message' => 'ini manufaktur',
            'success' => 200
        ]);
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ManufakturRequest $request)
    {
        return DB::transaction(function() use ($request) {

            $this->manufaktur->create($request->all());

            return response()->json([
                "status" => true,
                "message" => "Data Berhasil di Tambahkan",
                "data" => $request->all()
            ]);
        });
    }
    public function show($id)
    {
        return DB::transaction(function () use ($id) {
            $data = Manufaktur::findOrFail($id);

            return response()->json([
                'status' => 200,
                'pesan' => 'Data Supplier yang dipilih',
                'data' => $data,
            ]);
        });
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Manufaktur $manufaktur)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ManufakturRequest $request, string $id)
    {
        return DB::transaction(function() use ($request, $id) {

            $update = $this->manufaktur->findOrFail($id);

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
            $this->manufaktur->destroy($id);

            return response()->json([
                "status" => true,
                "message" => "Data Berhasil di Hapus"
            ]);

        });
    }
}
