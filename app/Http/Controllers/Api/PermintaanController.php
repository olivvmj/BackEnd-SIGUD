<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Barang;
use App\Models\StockOut;
use App\Models\Permintaan;
use Illuminate\Http\Request;
use App\Models\StockOutDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\Http\Requests\PermintaanRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PermintaanResource;

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
        $this->users = $users;
        $this->barang = $barang;

        $this->permintaan = Permintaan::join('users', 'users_id','=','permintaan.users_id')
                        ->join('barang', 'barang.id', '=', 'permintaan.barang_id')
                        ->get();
    }
    public function index()
    {
        $permintaan = Permintaan::all();
        return response()->json([
            'kode' => 200,
            'status' => true,
            'message' => 'ini Pengiriman',
            'data' => PermintaanResource::collection($permintaan),
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
        try {
            return DB::transaction(function () use ($request) {
                $permintaan = new Permintaan();
                $permintaan->users_id = $request->users_id;
                $permintaan->barang_id = $request->barang_id;
                $permintaan->tanggal_permintaan = now()->format('Y-m-d');
                $permintaan->alamat_penerima = $request->alamat_penerima;
                $permintaan->nama_penerima = $request->nama_penerima;
                $result = $permintaan->save();

                if ($result) {
                    return response()->json([
                        'kode' => 200,
                        'status' => true,
                        'message' => "Data Berhasil di Tambahkan",
                        'data' => $permintaan
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
            $data = Permintaan::findOrFail($id);

            return response()->json([
                'kode' => 200,
                'status' => true,
                'message' => 'Data Permintaan yang dipilih',
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

            $update = Permintaan::findOrFail($id);

            $update->update($request->all());

            return response()->json([
                'kode' => 200,
                'status' => true,
                'message' => "Data Berhasil diupdate",
                'data' => $update
            ]);

        });
    }

    // public function validasiPermintaan($id, $status)
    // {
    //     $permintaan = Permintaan::find($id);

    //     if (!$permintaan) {
    //         return response()->json(['message' => 'Permintaan tidak ditemukan'], 404);
    //     }

    //     if ($status == 'diterima') {
    //         $permintaan->status = 'diterima';
    //         $permintaan->save();

    //         // Mendapatkan informasi dari tabel Permintaan
    //         $tanggalPermintaan = $permintaan->tanggal_permintaan;
    //         $alamatPenerima = $permintaan->alamat_penerima;
    //         $namaPenerima = $permintaan->nama_penerima;

    //         // Nomor Dokumen yang unik (contoh: menggunakan ID permintaan)
    //         $nomorDoc = $permintaan->id . '/BAST/JMI-JSA/' . date('m/Y');

    //         // Informasi dari tabel Stock_out_detail
    //         $stock_out_detail = StockOutDetail::where('permintaan_id', $id)->get();

    //         // Gunakan data ini untuk mengisi template berita acara
    //         $data = [
    //             'nomor_doc' => $nomorDoc,
    //             'tanggal' => $tanggalPermintaan,
    //             'alamat' => $alamatPenerima,
    //             'nama' => $namaPenerima,
    //             'stock_detail' => $stock_out_detail
    //         ];

    //         return response()->json([
    //             'status' => 200,
    //             'message' => 'Berita acara berhasil dibuat',
    //             'data' => $data,
    //         ]);
    //     } elseif ($status == 'ditolak') {

    //         return response()->json([
    //             'status' => 400,
    //             'message' => 'Permintaan ditolak',
    //         ]);
    //     } else {
    //         // Jika status tidak valid (bukan 'diterima' atau 'ditolak')
    //         return response()->json(['message' => 'Status tidak valid'], 400);
    //     }

    // }

    public function validasiPermintaan($id, $status)
    {
        $permintaan = Permintaan::find($id);

        if (!$permintaan) {
            return response()->json(['message' => 'Permintaan tidak ditemukan'], 404);
        }

        if ($status == 'diterima') {
            $permintaan->status = 'diterima';
            $permintaan->save();

            // Update tabel StockOut
            $stockOut = StockOut::where('permintaan_id', $id)->first();
            if ($stockOut) {
                $stockOut->tanggal_selesai = now()->format('Y-m-d H:i:s');
                $stockOut->save();
            }

            // Mendapatkan informasi dari tabel Permintaan
            $tanggalPermintaan = $permintaan->tanggal_permintaan;
            $alamatPenerima = $permintaan->alamat_penerima;
            $namaPenerima = $permintaan->nama_penerima;

            // Nomor Dokumen yang unik (contoh: menggunakan ID permintaan)
            $nomorDoc = $permintaan->id . '/BAST/JMI-JSA/' . date('m/Y');

            // Informasi dari tabel Stock_out_detail
            $stock_out_detail = StockOutDetail::where('permintaan_id', $id)->get();

            // Gunakan data ini untuk mengisi template berita acara
            $data = [
                'nomor_doc' => $nomorDoc,
                'tanggal' => $tanggalPermintaan,
                'alamat' => $alamatPenerima,
                'nama' => $namaPenerima,
                'stock_detail' => $stock_out_detail
            ];

            return response()->json([
                'status' => 200,
                'message' => 'Berita acara berhasil dibuat',
                'data' => $data,
            ]);
        } elseif ($status == 'ditolak') {
            $permintaan->status = 'ditolak';
            $permintaan->save();

            // Update tabel StockOut
            $stockOut = StockOut::where('permintaan_id', $id)->first();
            if ($stockOut) {
                $stockOut->tanggal_pembatalan = now()->format('Y-m-d-H-i-s');
                $stockOut->save();
            }

            return response()->json([
                'status' => 400,
                'message' => 'Permintaan ditolak',
            ]);
        } else {
            return response()->json(['message' => 'Status tidak valid'], 400);
        }
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
                'kode' => 200,
                'status' => true,
                'message' => 'Success Menghapus Data!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'kode' => 500,
                'status' => false,
                'message' => 'Terjadi kesalahan saat menghapus data. Error: ',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
