<?php

namespace App\Http\Controllers\API\MasterData;

use App\Models\Brand;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\BrandRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;

class BrandController extends Controller
{
    protected $brand;

    public function __construct(Brand $brand)
    {
        $this->brand = $brand;
    }
    public function index()
    {
        $brand = Brand::first();
        if (empty($brand)) {
            $response = [
                "status" => 204,
                "message" => "Data Tidak Ada",
                "data" => '',
            ];
            return response()->json($response);

        } else {
            $response = [
                "status" => 200,
                "message" => "Data Brand",
                "data" => $brand,
            ];
            return response()->json($response);
        }
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
                "status" => 201,
                "message" => "Data Berhasil di Tambahkan",
                "data" => $request->all()
            ]);
        });
    }

    public function show($id)
    {
        return DB::transaction(function () use ($id){
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
                "message" => "Data berhasil diperbarui",
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
                "message" => "Data Berhasil di Hapus"
            ]);

        });
    }
}
