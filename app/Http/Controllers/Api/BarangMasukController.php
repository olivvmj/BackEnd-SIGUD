<?php

namespace App\Http\Controllers\API;


use App\Models\Barang;
use App\Models\StockIn;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\StockInDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\StockInRequest;
use App\Http\Resources\StockInResource;
use Illuminate\Database\QueryException;
use App\Http\Resources\StockInDetailResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BarangMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $stockIn;
    protected $detailStock;

    public function __construct(StockIn $stockIn, StockInDetail $detailStock)
    {
        $this->stockIn = $stockIn;
        $this->detailStock = $detailStock;
    }

    public function index()
    {
        try {
            $barangMasuk = StockInDetail::join('barang', 'stock_in_detail.barang_id', '=', 'barang.id')
                ->join('stock_in', 'stock_in_detail.stock_in_id', '=', 'stock_in.id')
                ->join('supplier', 'stock_in.supplier_id', '=', 'supplier.id')
                ->select(
                    'stock_in_detail.id as stock_in_detail_id',
                    'supplier.nama_supplier',
                    'stock_in.nama_stock_in',
                    'barang.nama_barang',
                    'barang.gambar_barang',
                    'stock_in.kuantiti',
                    'stock_in_detail.serial_number',
                    'stock_in_detail.serial_number_manufaktur',
                    'stock_in_detail.status'
                )
                ->get();

            if ($barangMasuk->isEmpty()) {
                return response()->json([
                    'kode' => 404,
                    'status' => false,
                    'message' => 'Data tidak ditemukan',
                ]);
            }

            $formattedData = [];

            foreach ($barangMasuk as $data) {
                $supplierName = $data->nama_supplier;
                $stockInName = $data->nama_stock_in;

                $formattedData[$supplierName][$stockInName][] = [
                    'stock_in_detail_id' => $data->stock_in_detail_id,
                    'nama_barang' => $data->nama_barang,
                    'gambar_barang' => $data->gambar_barang,
                    'kuantiti' => $data->kuantiti,
                    'serial_number' => $data->serial_number,
                    'serial_number_manufaktur' => $data->serial_number_manufaktur,
                    'status' => $data->status,
                ];
            }

            $responseData = [
                'kode' => 200,
                'status' => true,
                'message' => 'Menampilkan Data Barang Masuk',
                'data' => []
            ];

            foreach ($formattedData as $supplierName => $stockIns) {
                foreach ($stockIns as $stockInName => $details) {
                    $responseData['data'][] = [
                        'nama_supplier' => $supplierName,
                        'nama_stock_in' => $stockInName,
                        'detail_barang_masuk' => $details,
                    ];
                }
            }

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

    public function store(StockInRequest $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $stockIn = StockIn::create([
                    'supplier_id' => $request->supplier_id,
                    'nama_stock_in' => $request->nama_stock_in,
                    'kuantiti' => $request->kuantiti,
                ]);

                $detailStock = StockInDetail::create([
                    'barang_id' => $request->barang_id,
                    'stock_in_id' => $stockIn->id,
                    'serial_number' => $request->serial_number,
                    'serial_number_manufaktur' => $request->serial_number_manufaktur,
                    'status' => $request->status,
                ]);

                $result = $stockIn && $detailStock;

                if ($result) {
                    $stockInWithDetails = StockIn::with('details.barang')->find($stockIn->id);

                    return response()->json([
                        'kode' => 200,
                        'status' => true,
                        'message' => 'Data Berhasil di Tambahkan',
                        'data' => $stockInWithDetails
                    ]);
                } else {
                    DB::rollBack();
                    return response()->json([
                        'kode' => 500,
                        'status' => false,
                        'message' => 'Gagal menambahkan data'
                    ]);
                }
            });
        } catch (QueryException $e) {
            DB::rollBack();
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
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(StockInRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $details = StockInDetail::findOrFail($id);
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
            // Find the stockin by ID
            $stockIn = StockIn::findOrFail($id);

            // Find the detail by stockin ID and delete
            $stockInDetail = StockInDetail::where('stock_in_id', $id)->first();
            if ($stockInDetail) {
                $stockInDetail->delete();
            }

            // Delete the stockin
            $stockIn->delete();

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
