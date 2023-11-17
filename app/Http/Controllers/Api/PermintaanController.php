<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Barang;
use App\Models\Permintaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\PermintaanRequest;
use App\Http\Resources\PermintaanResource;
use Illuminate\Support\Facades\Validator;

class PermintaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    protected $permintaan;
    protected $users;
    protected $barang;

    public function __construct(Permintaan $permintaan, User $users, Barang $barang)
    {
        $this->permintaan = $permintaan;
        $this->users = $users;
        $this->barang = $barang;

        $this->permintaan = Permintaan::join('users', 'users_id','=','permintaan.users_id')
                        ->join('barang', 'barang.id', '=', 'permintaan.barang_id')
                        ->get();
    }
    public function index()
    {
        $permintaan = Permintaan::latest()->get();
        return response()->json([
            'data' => PermintaanResource::collection($permintaan),
            'message' => 'ini Permintaan',
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
    public function store(PermintaanRequest $request)
    {
        return DB::transaction(function() use ($request) {

            $filename = "";
            $permintaan = new Permintaan();
            $permintaan->users_id = $request->users_id;
            $permintaan->barang_id = $request->barang_id;
            $permintaan->tanggal_permintaan = $request->tanggal_permintaan;
            $permintaan->alamat_penerima= $request->alamat_penerima;
            $permintaan->nama_penerima = $request->nama_penerima;
            $result = $permintaan->save();

            if ($result) {
                return response()->json([
                    "status" => 200,
                    "pesan" => "Data Berhasil di Tambahkan",
                    "data" => $permintaan
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
            $data = Permintaan::findOrFail($id);

            return response()->json([
                'status' => 200,
                'pesan' => 'Data Permintaan yang dipilih',
                'data' => $data,
            ]);
        });
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permintaan $permintaan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(permintaanRequest $request, string $id)
    {
        return DB::transaction(function() use ($request, $id) {

            $update = $this->permintaan->findOrFail($id);

            $update->update($request->all());

            return response()->json([
                "status" => 200,
                "pesan" => "Data Berhasil diupdate",
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
            $permintaan = Permintaan::findOrFail($id);

            $permintaan->delete();

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
