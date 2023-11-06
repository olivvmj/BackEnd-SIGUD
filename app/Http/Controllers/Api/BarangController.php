<?php

namespace App\Http\Controllers\Api;

use App\Models\Brand;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\BarangRequest;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\BarangResource;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $barang;
    protected $brand;

    protected $kategori;
    public function __construct(Barang $barang, Brand $brand, Kategori $kategori)

    {
        $this->barang = $barang;
        $this->brand = $brand;
        $this->kategori = $kategori;
    }

    public function index()
    {
        $barang = Barang::latest()->get();
        return response()->json([
            'data' => BarangResource::collection($barang),
            'message' => 'ini barang',
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
    public function store(BarangRequest $request)
    {
        return DB::transaction(function() use ($request) {

            $this->barang->create($request->all());

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
            $data = $this->barang->findOrFail($id);

            return new BarangResource($data);
        });
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barang $barang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BarangRequest $request, string $id)
    {
        return DB::transaction(function() use ($request, $id) {

            $update = $this->barang->findOrFail($id);

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
            $this->barang->destroy($id);

            return response()->json([
                "status" => true,
                "pesan" => "Data Berhasil di Hapus"
            ]);

        });
    }
}
