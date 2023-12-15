<?php

namespace App\Http\Controllers\API\MasterData;

use App\Models\Brand;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\BrandRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BrandController extends Controller
{
    protected $brand;

    public function __construct(Brand $brand)
    {
        $this->brand = $brand;
    }
    public function index()
    {
        try {
            return DB::transaction(function () {
                $brand = Brand::all();

                if (empty($brand)) {
                    $response = [
                        'kode' => 204,
                        'status' => false,
                        'message' => "Data Tidak Ada",
                        'error' => "Data brand tidak ditemukan",
                    ];
                    return response()->json($response);
                } else {
                    $response = [
                        'kode' => 200,
                        'status' => true,
                        'message' => "Data Brand",
                        'data' => $brand,
                    ];
                    return response()->json($response);
                }
            });
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'kode' => 500,
                'status' => false,
                'message' => "Terjadi kesalahan saat mengambil data. Error: ",
                'error' => $e->getMessage(),
            ]);
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
        try {
            return DB::transaction(function() use ($request) {
                $brand = $this->brand->create($request->all());

                return response()->json([
                    'kode' => 201,
                    'status' => true,
                    'message' => "Data Berhasil di Tambahkan",
                    'data' => $brand
                ]);
            });
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'kode' => 500,
                'status' => false,
                'message' => "Terjadi kesalahan saat menambahkan data",
                'error' => $e->getMessage()
            ]);
        }
    }

    public function show($id)
    {
        return DB::transaction(function () use ($id) {
            $data = Brand::findOrFail($id);

            return response()->json([
                'kode' => 200,
                'status' => true,
                'message' => 'Data Brand yang dipilih',
                'data' => $data,
            ]);
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
        try {
            return DB::transaction(function() use ($request, $id) {
                $update = $this->brand->findOrFail($id);
                $update->update($request->all());
                return response()->json([
                    'kode' => 200,
                    'status' => true,
                    'message' => "Data berhasil diperbarui",
                    'data' => $update
                ]);
            });
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'kode' => 404,
                'status' => false,
                'message' => "Data tidak ditemukan",
                'error' => $e->getMessage()
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'kode' => 500,
                'status' => false,
                'message' => "Terjadi kesalahan saat memperbarui data",
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            return DB::transaction(function () use ($id) {
                $brand = Brand::findOrFail($id);
                $brand->delete();

                return response()->json([
                    'kode' => 200,
                    'status' => true,
                    'message' => "Data Berhasil di Hapus"
                ]);
            });
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json([
                'kode' => 500,
                'status' => false,
                'message' => "Terjadi kesalahan saat menghapus data",
                'error' => $e->getMessage()
            ]);
        }
    }
}
