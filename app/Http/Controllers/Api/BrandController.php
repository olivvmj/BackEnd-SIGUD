<?php

namespace App\Http\Controllers\API;

use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\BrandRequest;
use Illuminate\Support\Facades\Bus;
use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    protected $brand;

    public function __construct(Brand $brand)
    {
        $this->brand = $brand;
    }
    public function index()
    {
        $brand = Brand::latest()->get();
        return response()->json([
            'data' => BrandResource::collection($brand),
            'message' => 'ini brand',
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
    public function store(BrandRequest $request)
    {
        return DB::transaction(function() use ($request) {

            $this->brand->create($request->all());

            return response()->json([
                "status" => true,
                "pesan" => "Data Berhasil di Tambahkan",
                "data" => $request->all()
            ]);
        });
    }

    public function show($id)
    {
        return DB::transaction(function () use ($id) {
            $data = $this->brand->findOrFail($id);

            return new BrandResource($data);
        });
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BrandRequest $request, string $id)
    {
        return DB::transaction(function() use ($request, $id) {

            $update = $this->brand->findOrFail($id);

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
            $this->brand->destroy($id);

            return response()->json([
                "status" => true,
                "pesan" => "Data Berhasil di Hapus"
            ]);

        });
    }
}
