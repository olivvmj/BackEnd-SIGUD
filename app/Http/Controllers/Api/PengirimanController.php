<?php

namespace App\Http\Controllers\Api;

use App\Models\Pengiriman;
use App\Models\Permintaan;
use Illuminate\Http\Request;
use App\Models\StatusPengiriman;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\Http\Requests\PengirimanRequest;
use App\Http\Resources\PengirimanResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PengirimanController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $pengiriman;
    protected $permintaan;
    protected $status_pengiriman;

    public function __construct(Pengiriman $pengiriman, Permintaan $permintaan, StatusPengiriman $status_pengiriman)
    {
        $this->pengiriman = $pengiriman;
        $this->permintaan = $permintaan;
        $this->status_pengiriman = $status_pengiriman;

        $this->pengiriman = Pengiriman::join('permintaan', 'permintaan_id', '=', 'pengiriman.permintaan_id')
                        ->join('status_pengiriman', 'status_pengiriman_id', '=', 'pengiriman.status_pengiriman_id')
                        ->get();
    }
    public function index()
    {
        $pengiriman = Pengiriman::all();
        return response()->json([
            'kode' => 200,
            'status' => true,
            'message' => 'ini Pengiriman',
            'data' => PengirimanResource::collection($pengiriman)
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
    public function store(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $pengiriman = new Pengiriman();
                $pengiriman->permintaan_id = $request->permintaan_id;
                $pengiriman->status_pengiriman_id = $request->status_pengiriman_id;
                $pengiriman->tanggal_pengiriman = $request->tanggal_pengiriman;
                $result = $pengiriman->save();

                if ($result) {
                    return response()->json([
                        'kode' => 201,
                        'status' => true,
                        'message' => "Data Berhasil di Tambahkan",
                        'data' => $pengiriman
                    ]);
                }
            });
        } catch (QueryException $e) {
            return response()->json([
                'kode' => 500,
                'status' => false,
                'message' => 'Terjadi kesalahan saat menambahkan data',
                'error' => $e->getMessage()
            ]);
        } 
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return DB::transaction(function () use ($id) {
            $data = Pengiriman::findOrFail($id);

            return response()->json([
                'kode' => 200,
                'status' => true,
                'message' => 'Data Pengiriman yang dipilih',
                'data' => $data,
            ]);
        });
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pengiriman $pengiriman)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PengirimanRequest $request, string $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $update = $this->pengiriman->findOrFail($id);

                $update->update($request->all());

                return response()->json([
                    'kode' => 200,
                    'status' => true,
                    'message' => "Data Berhasil diupdate",
                    'data' => $update
                ]);
            });
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'kode' => 404,
                'status' => false,
                'message' => 'Data tidak ditemukan',
                'error' => $e->getMessage()
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'kode' => 500,
                'status' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error' => $e->getMessage()
            ]);
        } 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $pengiriman = Pengiriman::findOrFail($id);

            $pengiriman->delete();

            DB::commit();

            return response()->json([
                'kode' => 200,
                'status' => true,
                'message' => 'Success Menghapus Data!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'kode' => 500,
                'status' => false,
                'message' => 'Terjadi kesalahan saat menghapus data',
                'error' => $e->getMessage()
            ]);
        }
    }
}
