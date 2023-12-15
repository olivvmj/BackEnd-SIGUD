<?php

namespace App\Http\Controllers\API;


use App\Models\Barang;
use App\Models\StockOut;
use App\Models\StockOutDetail;
use App\Models\Permintaan;
use App\Http\Requests\Stock_OutRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $stockOut;
    protected $detailOut;

    public function __construct(StockOut $stockOut, StockOutDetail $detailOut)
    {
        $this->stockOut = $stockOut;
        $this->detailOut = $detailOut;
    }

    public function index()
    {
        try {
            $barangKeluar = StockOutDetail::join('barang', 'stock_out_detail.barang_id', '=', 'barang.id')
                ->join('stock_out', 'stock_out_detail.stock_out_id', '=', 'stock_out.id')
                ->join('permintaan', 'stock_out.permintaan_id', '=', 'permintaan.id')
                ->select(
                    'barang.nama_barang',
                    'barang.gambar_barang',
                    'stock_out.tanggal_selesai',
                    'stock_out_detail.serial_number',
                )
                ->get();

            if ($barangKeluar->isEmpty()) {
                return response()->json([
                    'kode' => 404,
                    'status' => false,
                    'message' => 'Data tidak ditemukan',
                ]);
            }

            $formattedData = [];

            foreach ($barangKeluar as $data) {

                $formattedData[] = [
                    'stock_out_detail_id' => $data->stock_out_detail_id,
                    'nama_barang' => $data->nama_barang,
                    'gambar_barang' => $data->gambar_barang,
                    'kuantiti' => $data->kuantiti,
                    'serial_number' => $data->serial_number,
                    'serial_number_manufaktur' => $data->serial_number_manufaktur,
                    'status' => $data->status,
                    'url' => "/barang/" . $data->gambar_barang,
                ];
            }

            $responseData = [
                'kode' => 200,
                'status' => true,
                'message' => 'Menampilkan Data Barang Keluar',
                'data' => $formattedData
            ];

            return response()->json($responseData);
        } catch (QueryException $e) {
            return response()->json([
                'kode' => 500,
                'status' => false,
                'message' => 'Terjadi kesalahan saat mengambil data',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */

    // public function store(Stock_OutRequest $request, $id)
    // {
    //     try {
    //         return DB::transaction(function () use ($request) {

    //             $stockOut = StockOut::create([
    //                 'permintaan_id' => $request->permintaan_id,
    //                 'kode_do' => $request->kode_do,
    //                 'nama_do' => $request->nama_do,
    //                 'kuantiti' => count($request->barang),
    //                 'tanggal_selesai' => null,
    //                 'tanggal_pembatalan' => null,
    //             ]);

    //             $barang = $request->barang;
    //             $array = [];
    //             foreach($barang as $item){
    //                 array_push($array, [
    //                     'stock_out_id' => $stockOut->id,
    //                     'barang_id' => $item['barang_id'],
    //                     'serial_number' => $item['serial_number'],
    //                     'serial_number_manufaktur' => $item['serial_number_manufaktur'],
    //                     'status' => $item['status'],

    //                 ]);
    //             }

    //             $detailOut = StockOutDetail::insert($array);

    //             return response()->json([
    //                 'kode' => 200,
    //                 'status' => true,
    //                 'message' => 'Data Berhasil ditambahkan',
    //                 'data' => [
    //                     'stockOut' => $stockOut,
    //                     'detailOut' => $detailOut,
    //                 ]
    //             ]);
    //         });
    //     } catch (QueryException $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'kode' => 500,
    //             'status' => false,
    //             'message' => 'Terjadi kesalahan saat menambahkan data',
    //             'error' => $e->getMessage()
    //         ]);
    //     }
    // }

    public function store(Stock_OutRequest $request, $id)
    {
        $stockOut = StockOut::create([
            'permintaan_id' => $request->permintaan_id,
            'kode_do' => $request->kode_do,
            'nama_do' => $request->nama_do,
            'tanggal_selesai' => null,
            'tanggal_pembatalan' => null,
        ]);

        $barang = $request->barang;
        $array = [];
        foreach ($barang as $item) {
            array_push($array, [
                'stock_in_id' => $stockOut->id,
                'barang_id' => $item['barang_id'],
                'serial_number' => $item['serial_number'],
                'serial_number_manufaktur' => $item['serial_number_manufaktur'],
                'status' => $item['status'],

            ]);
        }

        return response()->json([
            'kode' => 200,
            'status' => true,
            'message' => 'Data Berhasil ditambahkan',
            'data' => [
                'stockOut' => $stockOut,
                'detailOut' => StockOutDetail::where('stock_out_id', $stockOut->id)->get(),
            ],
        ]);
    }






    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Stock_OutRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $details = StockOutDetail::findOrFail($id);
            $details->fill($request->validated());

            $details->save();
            DB::commit();

            return response()->json([
                'kode' => 200,
                'status' => true,
                'message' => 'Data berhasil diubah',
                'data' => $details
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'kode' => 500,
                'status' => false,
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
            // Find the StockOut by ID
            $stockOut = StockOut::findOrFail($id);

            // Find the detail by StockOut ID and delete
            $stockOutDetail = StockOutDetail::where('stock_in_id', $id)->first();
            if ($stockOutDetail) {
                $stockOutDetail->delete();
            }

            // Delete the StockOut
            $stockOut->delete();

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
