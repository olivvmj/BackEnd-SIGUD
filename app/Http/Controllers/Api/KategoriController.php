<?php

namespace App\Http\Controllers\Api;

use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\KategoriRequest;
use App\Http\Resources\KategoriResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::latest()->get();
        return response()->json([
            'data' => KategoriResource::collection($kategori),
            'message' => 'Fetch all kategori',
            'success' => true
        ]);
    }

    public function store(KategoriRequest $request)
{
    return DB::transaction(function () use ($request) {
        try {
            $kategori = Kategori::create($request->all());
            return response()->json([
                "status" => true,
                "pesan" => "Data Berhasil di Tambahkan",
                "data" => $kategori
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "pesan" => "Gagal menambahkan data",
                "error" => $e->getMessage()
            ], 500);
        }
    });
}

    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'nama_kategori' => 'required|string|max:255',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'data' => [],
    //             'message' => $validator->errors(),
    //             'success' => false
    //         ]);
    //     }

    //     $kategori = Kategori::create([
    //         'nama_kategori' => $request->get('nama_kategori'),

    //     ]);

    //     return response()->json([
    //         'data' => new KategoriResource($kategori),
    //         'message' => 'Kategori created successfully.',
    //         'success' => true
    //     ]);
    // }

    public function show(Kategori $kategori)
    {
        return response()->json([
            'data' => new KategoriResource($kategori),
            'message' => 'Data kategori found',
            'success' => true
        ]);
    }

    public function update(Request $request, Kategori $kategori)
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [],
                'message' => $validator->errors(),
                'success' => false
            ]);
        }

        $kategori->update([
            'nama_kategori' => $request->get('nama_kategori'),
        ]);

        return response()->json([
            'data' => new KategoriResource($kategori),
            'message' => 'Kategori updated successfully',
            'success' => true
        ]);
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();

        return response()->json([
            'data' => [],
            'message' => 'Kategori deleted successfully',
            'success' => true
        ]);
    }
}
