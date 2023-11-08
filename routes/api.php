<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\API\MasterData\BrandController;
use App\Http\Controllers\Api\MasterData\KategoriController;

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
});

