<?php

use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\StockController;
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Stock_OutController;
use App\Http\Controllers\API\HitungStokController;
use App\Http\Controllers\API\PengirimanController;
use App\Http\Controllers\API\PermintaanController;
use App\Http\Controllers\API\BarangMasukController;
// use App\Http\Controllers\API\StatusPermintaanController;
use App\Http\Controllers\API\Akun\AccountController;
use App\Http\Controllers\API\BarangKeluarController;
use App\Http\Controllers\API\MasterData\BrandController;
use App\Http\Controllers\API\StatusPengirimanController;
use App\Http\Controllers\API\StatusPermintaanController;
use App\Http\Controllers\API\Stock_out_DetailController;
use App\Http\Controllers\API\MasterData\BarangController;
use App\Http\Controllers\API\MasterData\KategoriController;
use App\Http\Controllers\API\MasterData\SupplierController;




/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function(Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function() {
    Route::post('/logout', [AuthController::class, 'logout']);

});

Route::middleware(['auth:sanctum'])->group(function() {
    Route::prefix('/hitung-stock')->group(function() {
        Route::get('/', [HitungStokController::class, 'hitungTotalStok']);
    });

    Route::prefix('/barang-masuk')->group(function() {
        Route::get('/', [BarangMasukController::class, 'index']);
        Route::post('/', [BarangMasukController::class, 'store']);
        Route::put('/{id}', [BarangMasukController::class, 'update']);
        Route::delete('/{id}', [BarangMasukController::class, 'destroy']);
    });

    Route::prefix('/barang-keluar')->group(function() {
        Route::get('/', [BarangKeluarController::class, 'index']);
        Route::post('/', [BarangKeluarController::class, 'store']);
    });

    Route::prefix('/manage-akun')->group(function() {
        Route::post('/createClient', [AccountController::class, 'createClient'])->name('createClient');
        Route::post('/createOperator', [AccountController::class, 'createOperator'])->name('createOperator');
        Route::get('/', [AccountController::class, 'index']);
        Route::delete('/{id}', [AccountController::class, 'destroy']);
    });

    Route::prefix("kategori")->group(function() {
        Route::get('/', [KategoriController::class, 'index'])->name('index');
        Route::post('/', [KategoriController::class, 'store'])->name('store');
        Route::get('/{id}', [KategoriController::class, 'show'])->name('show');
        Route::put('/{id}', [KategoriController::class, 'update'])->name('update');
        Route::delete('/{id}', [KategoriController::class, 'destroy'])->name('destroy');
    });

    Route::prefix("brand")->group(function() {
        Route::get('/', [BrandController::class, 'index'])->name('index');
        Route::post('/', [BrandController::class, 'store'])->name('store');
        Route::get('/{id}', [BrandController::class, 'show'])->name('show');
        Route::put('/{id}', [BrandController::class, 'update'])->name('update');
        Route::delete('/{id}', [BrandController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('/statuspermintaan')->group(function () {
        Route::get('/', [StatusPermintaanController::class, 'index'])->name('index');
        Route::post('/', [StatusPermintaanController::class, 'store'])->name('store');
        Route::get('/{id}', [StatusPermintaanController::class, 'show'])->name('show');
        Route::put('/{id}', [StatusPermintaanController::class, 'update'])->name('update');
        Route::delete('/{id}', [StatusPermintaanController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('/statuspengiriman')->group(function () {
        Route::get('/', [StatusPengirimanController::class, 'index'])->name('index');
        Route::post('/', [StatusPengirimanController::class, 'store'])->name('store');
        Route::get('/{id}', [StatusPengirimanController::class, 'show'])->name('show');
        Route::put('/{id}', [StatusPengirimanController::class, 'update'])->name('update');
        Route::delete('/{id}', [StatusPengirimanController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('/barang')->group(function () {
        Route::get('/', [BarangController::class, 'index'])->name('index');
        Route::post('/', [BarangController::class, 'store'])->name('store');
        Route::get('/{id}', [BarangController::class, 'show'])->name('show');
        Route::post('/{id}', [BarangController::class, 'update'])->name('update');
        Route::delete('/{id}', [BarangController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('/stock_out')->group(function () {
        Route::get('/', [Stock_OutController::class, 'index'])->name('index');
        Route::post('/', [Stock_OutController::class, 'store'])->name('store');
        Route::get('/{id}', [Stock_OutController::class, 'show'])->name('show');
        Route::put('/{id}', [Stock_OutController::class, 'update'])->name('update');
        Route::delete('/{id}', [Stock_OutController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('/stock_out_detail')->group(function () {
        Route::get('/', [Stock_out_DetailController::class, 'index'])->name('index');
        Route::post('/', [Stock_out_DetailController::class, 'store'])->name('store');
        Route::get('/{id}', [Stock_out_DetailController::class, 'show'])->name('show');
        Route::put('/{id}', [Stock_out_DetailController::class, 'update'])->name('update');
        Route::delete('/{id}', [Stock_out_DetailController::class, 'destroy'])->name('destroy');
    });


    Route::prefix('/supplier')->group(function () {
        Route::get('/', [SupplierController::class, 'index'])->name('index');
        Route::post('/', [SupplierController::class, 'store'])->name('store');
        Route::get('/{id}', [SupplierController::class, 'show'])->name('show');
        Route::put('/{id}', [SupplierController::class, 'update'])->name('update');
        Route::delete('/{id}', [SupplierController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('/permintaan')->group(function () {
        Route::get('/', [PermintaanController::class, 'index'])->name('index');
        Route::post('/', [PermintaanController::class, 'store'])->name('store');
        Route::get('/{id}', [PermintaanController::class, 'show'])->name('show');
        Route::put('/{id}', [PermintaanController::class, 'update'])->name('update');
        Route::delete('/{id}', [PermintaanController::class, 'destroy'])->name('destroy');
        Route::put('/{id}/{status}', [PermintaanController::class, 'validasiPermintaan']);
    });

    Route::prefix('/pengiriman')->group(function () {
        Route::get('/', [PengirimanController::class, 'index'])->name('index');
        Route::post('/', [PengirimanController::class, 'store'])->name('store');
        Route::get('/{id}', [PengirimanController::class, 'show'])->name('show');
        Route::put('/{id}', [PengirimanController::class, 'update']);
        Route::delete('/{id}', [PengirimanController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('/stock_out')->group(function () {
        Route::get('/', [Stock_OutController::class, 'index'])->name('index');
        Route::post('/', [Stock_OutController::class, 'store'])->name('store');
        Route::get('/{id}', [Stock_OutController::class, 'show'])->name('show');
        Route::put('/{id}', [Stock_OutController::class, 'update'])->name('update');
        Route::delete('/{id}', [Stock_OutController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('/stock_out_detail')->group(function () {
        Route::get('/', [Stock_out_DetailController::class, 'index'])->name('index');
        Route::post('/', [Stock_out_DetailController::class, 'store'])->name('store');
        Route::get('/{id}', [Stock_out_DetailController::class, 'show'])->name('show');
        Route::put('/{id}', [Stock_out_DetailController::class, 'update'])->name('update');
        Route::delete('/{id}', [Stock_out_DetailController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('/manage-akun')->group(function() {
        Route::get('/', [AccountController::class, 'index']);
        Route::post('/createClient', [AccountController::class, 'createClient']);
        Route::post('/createOperator', [AccountController::class, 'createOperator']);
    });
});
