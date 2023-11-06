<?php

namespace App\Http\Controllers\MasterData\Brand;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brand = Brand::all();

        return view('Master_Data.Brand.index', compact('brand'));
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
            'nama_brand' => 'required',
        ]);

        if($validatedData->stopOnFirstFailure()->fails())

        return response()->json([
            'status' => false,
            'message' => $validatedData->errors()->first(),
        ], 200);

        $brand = Brand::create([
            'nama_brand' => $request->nama_brand,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data Brand berhasil disimpan!',
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
        $result = Brand::find($id);

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

        $brand = Brand::find($id);

        $validatedData = Validator::make($request->all(),[
            'nama_brand' => 'required',
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validatedData->errors()->first(),
            ], 200);
        }

        $brand->nama_brand= $request->nama_brand;
        $brand->save();

        return response()->json([
            'status' => true,
            'message' => 'Data Brand berhasil dirubah'
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
            $brand = Brand::findOrFail($id);

            // Delete the about
            $brand->delete();

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

