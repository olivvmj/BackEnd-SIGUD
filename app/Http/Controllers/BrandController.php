<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\BrandRequest;
use Illuminate\Support\Facades\Bus;
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
    public function show( Brand $brand)
    {
        return response()->json([
            'data' => new BrandResource($brand),
            'message' => 'data found',
            'success' => true
        ]);
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
    public function update(Request $request, Brand $brand)
    {
        $validator = Validator::make($request->all(), [
            'nama_brand'=> 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [],
                'message' => $validator->errors(),
                'success' => false
            ]);
        }

        $brand->update([
            'nama_brand'=> $request->get('nama_brand')
        ]);

        return response()->json([
            'data' => new BrandResource($brand)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        $brand->delete();

        return response()->json([
            'data' => [],
            'message' => 'hapus sukses',
            'success' => true
        ]);
    }
}
