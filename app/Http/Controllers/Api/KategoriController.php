<?php

namespace App\Http\Controllers\Api;

use App\Models\Kategori;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Bus;
use App\Http\Controllers\Controller;
use App\Http\Requests\KategoriRequest;
use App\Http\Resources\KategoriResource;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    protected $kategori;

    public function __construct(Kategori $kategori)
    {
        $this->kategori = $kategori;
    }
    public function index()
    {
        $kategori = Kategori::latest()->get();
        return response()->json([
            'data' => KategoriResource::collection($kategori),
            'message' => 'ini kategori',
            'success' => true
        ]);
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(KategoriRequest $request)
    {
        return DB::transaction(function() use ($request) {

            $this->kategori->create($request->all());

            return response()->json([
                "status" => true,
                "pesan" => "Data Berhasil di Tambahkan",
                "data" => $request->all()
            ]);
        });
    }
    public function show( Kategori $kategori)
    {
        return response()->json([
            'data' => new KategoriResource($kategori),
            'message' => 'data found',
            'success' => true
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kategori $kategori)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(KategoriRequest $request, string $id)
    {
        return DB::transaction(function() use ($request, $id) {

            $update = $this->kategori->findOrFail($id);

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
            $this->kategori->destroy($id);

            return response()->json([
                "status" => true,
                "pesan" => "Data Berhasil di Hapus"
            ]);

        });
    }
}
