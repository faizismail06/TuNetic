<?php

use App\Http\Controllers\Api\ApiTrackingArmadaController;
use App\Http\Controllers\OsrmProxyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::prefix('tracking-armada')->group(function () {
    Route::get('/', [ApiTrackingArmadaController::class, 'getTrackingData']);
    Route::post('/', [ApiTrackingArmadaController::class, 'storeTrackingData']);
});

// API untuk menyimpan lokasi
// Route::middleware(['auth:sanctum'])->group(function() {
//     Route::post('petugas/jadwal-pengambilan/location', [JadwalPengambilanController::class, 'saveLocation']);
// });

// Route::get('osrm-proxy/{path?}', [OsrmProxyController::class, 'proxyOsrmRequest'])
//     ->where('path', '.*');
