<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\StatusPengirimanController;


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
    Route::get('/kategori', [KategoriController::class,'index']);
    Route::post('/kategori', [KategoriController::class,'store']);
});

Route::middleware(['auth:sanctum'])->prefix('/kategori')->group(function () {
    Route::get('/', [KategoriController::class, 'index'])->name('index');
    Route::post('/', [KategoriController::class, 'store'])->name('store');
    Route::get('/{id}', [KategoriController::class, 'show'])->name('show');
    Route::put('/{id}', [KategoriController::class, 'update'])->name('update');
    Route::delete('/{id}', [KategoriController::class, 'destroy'])->name('destroy');
});

Route::resource('/brand', \App\Http\Controllers\BrandController::class);

Route::middleware(['auth:sanctum'])->prefix('/statuspengiriman')->group(function () {
    Route::get('/', [StatusPengirimanController::class, 'index'])->name('index');
    Route::post('/', [StatusPengirimanController::class, 'store'])->name('store');
    Route::get('/{id}', [StatusPengirimanController::class, 'show'])->name('show');
    Route::put('/{id}', [StatusPengirimanController::class, 'update'])->name('update');
    Route::delete('/{id}', [StatusPengirimanController::class, 'destroy'])->name('destroy');
});

