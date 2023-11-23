<?php

namespace App\Http\Controllers\API\MasterData;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\supplierRequest;
use Illuminate\Database\QueryException;
use App\Http\Resources\supplierResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $supplier;

    public function __construct(Supplier $supplier)
    {
        $this->supplier = $supplier;
    }

    public function index()
    {
        try {
            return DB::transaction(function () {
                $supplier = Supplier::all();

                if (empty($supplier)) {
                    $response = [
                        'kode' => 204,
                        'status' => false,
                        'message' => 'Data supplier Tidak Ditemukan',
                        'error' => 'Data supplier tidak ditemukan',
                    ];
                    return response()->json($response);
                } else {
                    $response = [
                        'kode' => 200,
                        'status' => true,
                        'message' => 'Data supplier',
                        'data' => $supplier,
                    ];
                    return response()->json($response);
                }
            });
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'kode' => 500,
                'status' => false,
                'message' => 'Terjadi kesalahan saat mengambil data.',
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
    public function store(supplierRequest $request)
    {
        try {
            return DB::transaction(function() use ($request) {
                $supplier = $this->supplier->create($request->all());

                return response()->json([
                    'kode' => 201,
                    'status' => true,
                    'message' => 'Data Berhasil di Tambahkan',
                    'data' => $supplier
                ]);
            });
        } catch (QueryException $e) {
            return response()->json([
                'kode' => 500,
                'status' => false,
                'message' => 'Terjadi kesalahan saat menambahkan data supplier',
                'error' => $e->getMessage()
            ]);
        }
    }
    public function show($id)
    {
        return DB::transaction(function () use ($id) {
            $data = $this->supplier->findOrFail($id);

            return new supplierResource($data);
        });
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(supplierRequest $request, string $id)
    {
        try {
            return DB::transaction(function() use ($request, $id) {
                $update = $this->supplier->findOrFail($id);

                $update->update($request->all());

                return response()->json([
                    'kode' => 200,
                    'status' => true,
                    'message' => 'Data Berhasil diupdate',
                    'data' => $update
                ]);
            });
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'kode' => 404,
                'status' => false,
                'message' => 'Data supplier tidak ditemukan',
                'error' => $e->getMessage()
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'kode' => 500,
                'status' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data supplier',
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
                $supplier = $this->supplier->findOrFail($id);
                $supplier->delete();

                return response()->json([
                    'kode' => 200,
                    'status' => true,
                    'message' => 'Data Berhasil di Hapus'
                ]);
            });
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json([
                'kode' => 500,
                'status' => false,
                'message' => 'Terjadi kesalahan saat menghapus data',
                'error' => $e->getMessage()
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'kode' => 404,
                'status' => false,
                'message' => 'Data supplier tidak ditemukan',
                'error' => $e->getMessage()
            ]);
        }
    }
}
