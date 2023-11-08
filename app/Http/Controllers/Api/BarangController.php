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
        // $uploadedFile = $request->file('gambar_barang');
        // $filename = $uploadedFile->store('barang', 'public');
        // $originalFilename = $uploadedFile->getClientOriginalName();
        return DB::transaction(function() use ($request) {

            $filename = "";

            if ($request->hasfile('gambar_barang')){
                $image = $request->file('gambar_barang');
                $extension = $image->getClientOriginalExtension();
                $filename = Carbon::now()->format('YmdHis').'.'.$extension;
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
                    "pesan" => "Data Berhasil di Tambahkan",
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
            $data = $this->barang->findOrFail($id);
            if(is_null($data)){
                return $this->sendError('Data barang tidak ditemukan');
            }

            return response()->json([
                "status" => 200,
                "pesan" => "Data Berhasil di Update",
                "data" => $data,
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
    public function update(BarangRequest $request, string $id)
    {
        // return DB::transaction(function() use ($request, $id) {

        //     $filename = "";

        //     if ($request->hasfile('gambar_barang')){
        //         $image = $request->file('gambar_barang');
        //         $extension = $image->getClientOriginalExtension();
        //         $filename = Carbon::now()->format('YmdHis').'.'.$extension;
        //         $path = 'barang/'.$filename;
        //         Storage::disk('local')->put($path , file_get_contents($image));
        //     }

        //     $barang = Barang::find($id);
        //     $barang->kategori_id = $request->kategori_id;
        //     $barang->brand_id = $request->brand_id;
        //     $barang->nama_barang = $request->nama_barang;
        //     if ($filename != "") {
        //         $barang->gambar_barang = $filename;
        //     }
        //     $result = $barang->save();

        //     if ($result) {
        //         return response()->json([
        //             "status" => 200,
        //             "pesan" => "Data Berhasil di Update",
        //             "data" => $request->all()
        //         ]);
        //     } else {
        //         return response()->json(['success' => false]);
        //     }
        // });
        return DB::transaction(function() use ($request, $id) {

            $update = $this->barang->findOrFail($id);

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
            $this->barang->destroy($id);

            return response()->json([
                "status" => true,
                "pesan" => "Data Berhasil di Hapus"
            ]);

        });
    }
}
