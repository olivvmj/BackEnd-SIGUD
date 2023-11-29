<?php

namespace App\Http\Controllers\API\MasterData;

use App\Models\Kategori;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Bus;
use App\Http\Controllers\Controller;
use App\Http\Requests\KategoriRequest;
use Illuminate\Database\QueryException;
use App\Http\Resources\KategoriResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class KategoriController extends Controller
{
    protected $kategori;

    public function __construct(Kategori $kategori)
    {
        $this->kategori = $kategori;
    }
    public function index()
    {
        try {
            return DB::transaction(function () {
                $kategori = Kategori::all();

                if (empty($kategori)) {
                    $response = [
                        'kode' => 204,
                        'status' => false,
                        'message' => "Data Kategori Tidak Ditemukan",
                        'error' => 'Data kategori tidak ditemukan',
                    ];
                    return response()->json($response);
                } else {
                    $response = [
                        'kode' => 200,
                        'status' => true,
                        'message' => "Data kategori",
                        'data' => $kategori,
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
    public function store(KategoriRequest $request)
    {
        try {
            return DB::transaction(function() use ($request) {
                $kategori = $this->kategori->create($request->all());

                return response()->json([
                    'kode' => 201,
                    'status' => true,
                    'message' => "Data Kategori Berhasil di Tambahkan",
                    'data' => $kategori
                ]);
            });
        } catch (QueryException $e) {
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
            $data = Kategori::findOrFail($id);

            return response()->json([
                'kode' => 200,
                'status' => true,
                'message' => 'Data Kategori yang dipilih',
                'data' => $data,
            ]);
        });
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
        try {
            return DB::transaction(function() use ($request, $id) {
                $update = $this->kategori->findOrFail($id);
                $update->update($request->all());
                return response()->json([
                    'kode' => 200,
                    'status' => true,
                    'message' => "Data Berhasil di Simpan",
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
        } catch (QueryException $e) {
            return response()->json([
                'kode' => 500,
                'status' => false,
                'message' => "Terjadi kesalahan saat menyimpan data",
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
                $kategori = $this->kategori->findOrFail($id);
                $kategori->delete();

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
