<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\StatusPermintaan;
use App\Http\Controllers\Controller;
use App\Http\Requests\StatusPermintaanRequest;
use App\Http\Resources\StatusPermintaanResource;
use Illuminate\Support\Facades\DB;

class StatusPermintaanController extends Controller
{
    protected $status_permintaan;

    public function __construct(StatusPermintaan $status_permintaan)
    {
        $this->status_permintaan = $status_permintaan;
    }
    public function index()
    {
        $status_permintaan = StatusPermintaan::latest()->get();
        return response()->json([
            'kode' => 200,
            'status' => true,
            'message' => 'status permintaan',
            'data' => StatusPermintaanResource::collection($status_permintaan),
        ]);
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StatusPermintaanRequest $request)
    {
        return DB::transaction(function() use ($request) {

            $this->status_permintaan->create($request->all());

            return response()->json([
                'kode' => 201,
                'status' => true,
                'message' => "Data Berhasil di Tambahkan",
                "data" => $request->all()
            ]);
        });
    }
    public function show($id)
    {
        return DB::transaction(function () use ($id) {
            $data = $this->status_permintaan->findOrFail($id);

            return new StatuspermintaanResource($data);
        });
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StatusPermintaan $status_permintaan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StatusPermintaanRequest $request, string $id)
    {
        return DB::transaction(function() use ($request, $id) {

            $update = $this->status_permintaan->findOrFail($id);

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
        return DB::transaction(function () use ($id) {
            $this->status_permintaan->destroy($id);

            return response()->json([
                'kode' => 200,
                'status' => true,
                'message' => "Data Berhasil di Hapus"
            ]);

        });
    }
}
