<?php

use Illuminate\Http\Request;
//use App\Models\StatusPermintaan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\StockController;
use App\Http\Controllers\API\BarangController;
use App\Http\Controllers\API\Stock_inController;
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\ManufakturController;
use App\Http\Controllers\API\MasterData\BrandController;
use App\Http\Controllers\API\StatusPermintaanController;
use App\Http\Controllers\API\Akun\AccountController;
use App\Http\Controllers\API\MasterData\KategoriController;

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

    Route::prefix('/barang')->group(function () {
        Route::get('/', [BarangController::class, 'index'])->name('index');
        Route::post('/', [BarangController::class, 'store'])->name('store');
        Route::get('/{id}', [BarangController::class, 'show'])->name('show');
        Route::put('/{id}', [BarangController::class, 'update'])->name('update');
        Route::delete('/{id}', [BarangController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('/stock')->group(function () {
        Route::get('/', [StockController::class, 'index'])->name('index');
        Route::post('/', [StockController::class, 'store'])->name('store');
        Route::get('/{id}', [StockController::class, 'show'])->name('show');
        Route::put('/{id}', [StockController::class, 'update'])->name('update');
        Route::delete('/{id}', [StockController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('/manufaktur')->group(function () {
        Route::get('/', [ManufakturController::class, 'index'])->name('index');
        Route::post('/', [ManufakturController::class, 'store'])->name('store');
        Route::get('/{id}', [ManufakturController::class, 'show'])->name('show');
        Route::put('/{id}', [ManufakturController::class, 'update'])->name('update');
        Route::delete('/{id}', [ManufakturController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('/stock_in')->group(function () {
        Route::get('/', [Stock_inController::class, 'index'])->name('index');
        Route::post('/', [Stock_inController::class, 'store'])->name('store');
        Route::get('/{id}', [Stock_inController::class, 'show'])->name('show');
        Route::put('/{id}', [Stock_inController::class, 'update'])->name('update');
        Route::delete('/{id}', [Stock_inController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('/manage-akun')->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('index');
        Route::post('/createOperator', [AccountController::class, 'createOperator'])->name('createOperator');
        Route::post('/createClient', [AccountController::class, 'createClient'])->name('createClient');
        Route::delete('/{id}', [AccountController::class, 'destroy'])->middleware('auth', 'can:superAdmin');
    });
});

