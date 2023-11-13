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
    public function index(Request $request)
    {
        $statuspengiriman = StatusPengiriman::latest()->get();
        return response()->json([
            'data' => StatusPengirimanResource::collection($statuspengiriman),
            'message' => 'ini tampilan status',
            'success' => true
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

            $this->statuspengiriman->create($request->all());

            return response()->json([
                "status" => true,
                "pesan" => "Data Berhasil di Tambahkan",
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
            $data = $this->statuspengiriman->findOrFail($id);

            return new StatusPengirimanResource($data);
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
            $this->statuspengiriman->destroy($id);

            return response()->json([
                "status" => true,
                "pesan" => "Data Berhasil di Hapus" 
            ]);

        });

    }
}
