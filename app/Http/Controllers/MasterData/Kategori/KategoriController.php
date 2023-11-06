<?php

namespace App\Http\Controllers\MasterData\Kategori;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategori = Kategori::all();

        return view('Master_Data.Kategori.index', compact('kategori'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request){

        $validatedData = Validator::make($request->all(),[
            'nama_kategori' => 'required',
        ]);

        if($validatedData->stopOnFirstFailure()->fails())

        return response()->json([
            'status' => false,
            'message' => $validatedData->errors()->first(),
        ], 200);

        $kategori = Kategori::create([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Kategori berhasil disimpan!',
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $result = Kategori::find($id);

        if ($result) {
            return response()->json(['data' => $result]);
        } else {
            return response()->json(['message' => 'Data not found.'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {

        $kategori = Kategori::find($id);

        $validatedData = Validator::make($request->all(),[
            'nama_kategori' => 'required',
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validatedData->errors()->first(),
            ], 200);
        }

        $kategori->nama_kategori= $request->nama_kategori;
        $kategori->save();

        return response()->json([
            'status' => true,
            'message' => 'Kategori berhasil dirubah'
        ]);

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        // dd($id);
        try {
            // Find the about by ID
            $kategori = Kategori::findOrFail($id);

            // Delete the about
            $kategori->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Berhasil Menghapus Data!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Gagal Menghapus Data. Error: ' . $e->getMessage(),
            ]);
        }
    }
}

