<?php

namespace App\Http\Controllers\Api;

use App\Models\Pengiriman;
use App\Models\Permintaan;
use Illuminate\Http\Request;
use App\Models\StatusPengiriman;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\PengirimanRequest;
use App\Http\Resources\PengirimanResource;

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
        $pengiriman = Pengiriman::latest()->get();
        return response()->json([
            'data' => PengirimanResource::collection($pengiriman),
            'message' => 'ini Pengiriman',
            'success' => true,
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
        return DB::transaction(function() use ($request) {

            $filename = "";
            $pengiriman = new Pengiriman();
            $pengiriman->permintaan_id = $request->permintaan_id;
            $pengiriman->status_pengiriman_id = $request->status_pengiriman_id;
            $pengiriman->tanggal_pengiriman = $request->tanggal_pengiriman;
            $result = $pengiriman->save();

            if ($result) {
                return response()->json([
                    "status" => 200,
                    "pesan" => "Data Berhasil di Tambahkan",
                    "data" => $pengiriman
                ]);
            } else {
                return response()->json(['success' => false]);
            }
        });
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return DB::transaction(function () use ($id) {
            $data = Pengiriman::findOrFail($id);

            return response()->json([
                'status' => 200,
                'pesan' => 'Data Pengiriman yang dipilih',
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
        return DB::transaction(function() use ($request, $id) {

            $update = $this->pengiriman->findOrFail($id);

            $update->update($request->all());

            return response()->json([
                "status" => 200,
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
        DB::beginTransaction();
        try {
            $pengiriman = Pengiriman::findOrFail($id);

            $pengiriman->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Success Menghapus Data!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menghapus data. Error: ' . $e->getMessage(),
            ]);
        }
    }
}
