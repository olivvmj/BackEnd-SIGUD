<?php

namespace App\Http\Controllers\Api;

use App\Models\Brand;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\BarangRequest;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\BarangResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $barang;
    protected $brand;
    protected $kategori;

    public function __construct(Barang $barang, Brand $brand, Kategori $kategori){
        $this->barang = $barang;
        $this->brand = $brand;
        $this->kategori = $kategori;

        $this->barang = Barang::join('kategori', 'kategori.id', '=', 'barang.kategori_id')
                    ->join('brand', 'brand.id', '=', 'barang.brand_id')
                    ->get();
    }

    public function index()
    {
        $barang = Barang::all();
        return response()->json([
            'status' => true,
            'message' => 'Daftar Barang',
            'data' => BarangResource::collection($barang),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BarangRequest $request)
    {
        return DB::transaction(function() use ($request) {
            $filename = "";

            if ($request->hasFile('gambar_barang')) {
                $image = $request->file('gambar_barang');
                $filename = Carbon::now()->format('YmdHis').'.'.$image->getClientOriginalExtension();
                $path = 'barang/'.$filename;
                Storage::disk('local')->put($path , file_get_contents($image));
            }

            $barang = new Barang();
            $barang->kategori_id = $request->kategori_id;
            $barang->brand_id = $request->brand_id;
            $barang->nama_barang = $request->nama_barang;
            $barang->gambar_barang = $filename;
            $result = $barang->save();

            if ($result) {
                return response()->json([
                    "status" => 200,
                    "message" => "Data Berhasil di Tambahkan",
                    "data" => $request->all()
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
            $data = Barang::findOrFail($id);

            return response()->json([
                'status' => 200,
                'pesan' => 'Data Barang yang dipilih',
                'data' => $data,
            ]);
        });
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barang $barang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(BarangRequest $request, $id)
    {
        try {
            $barang = Barang::findOrFail($id);
            $barang->fill($request->validated());

            if ($request->hasFile('gambar_barang')) {
                $image = $request->file('gambar_barang');
                $filename = Carbon::now()->format('YmdHis').'.'.$image->getClientOriginalExtension();
                $path = 'barang/'.$filename;
                Storage::disk('local')->put($path, file_get_contents($image));

                if ($barang->gambar_barang && file_exists($barang->gambar_barang)) {
                    unlink($barang->gambar_barang);
                }

                $barang->gambar_barang = $filename;
            }

            $barang->save();

            return response()->json([
                'status' => 200,
                'message' => 'Data berhasil diubah',
                'data' => $barang
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan saat mengubah data',
                'error' => $e->getMessage()
            ]);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $barang = Barang::findOrFail($id);

            $barang->delete();

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
