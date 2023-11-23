<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StatusPengirimanRequest;
use App\Http\Resources\StatusPengirimanResource;
use App\Models\StatusPengiriman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatusPengirimanController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $statuspengiriman;

    public function __construct(StatusPengiriman $statuspengiriman)
    {
        $this->statuspengiriman = $statuspengiriman;
    }
    public function index()
    {
        $statuspengiriman = StatusPengiriman::all();
        return response()->json([
            'kode' => 200,
            'status' => true,
            'message' => 'status pengiriman',
            'data' => StatusPengirimanResource::collection($statuspengiriman),
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
    public function store(StatusPengirimanRequest $request)
    {
        return DB::transaction(function() use ($request) {

            $this->statuspengiriman->create($request->all());

            return response()->json([
                'kode' => 201,
                'status' => true,
                'message' => "Data Berhasil di Tambahkan",
                "data" => $request->all()
            ]);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return DB::transaction(function () use ($id) {
            $data = StatusPengiriman::findOrFail($id);

            return response()->json([
                'kode' => 200,
                'status' => true,
                'message' => 'Data Status Pengiriman',
                'data' => $data,
            ]);
        });
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StatusPengiriman $statusPengiriman)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StatusPengirimanRequest $request, string $id)
    {
        return DB::transaction(function() use ($request, $id) {

            $update = $this->statuspengiriman->findOrFail($id);

            $update->update($request->all());

            return response()->json([
                'kode' => 200,
                'status' => true,
                'message' => "Data Berhasil di Simpan",
                "data" => $update
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
            $statuspengiriman = StatusPengiriman::findOrFail($id);

            $statuspengiriman->delete();

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
                'message' => 'Terjadi kesalahan saat menghapus data. Error: ' . $e->getMessage(),
            ]);
        }
    }
}
