<?php

use App\Http\Controllers\DBBackupController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ArmadaController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\JadwalOperasionalController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\LokasiTpsController;
use App\Http\Controllers\PenugasanPetugasController;
use App\Http\Controllers\RuteController;
use App\Http\Controllers\RuteTpsController;
use App\Http\Controllers\SampahController;
use App\Http\Controllers\LaporanWargaController;
use App\Http\Controllers\LaporanTpsController;
use App\Http\Controllers\TrackingArmadaController;
use App\Http\Controllers\ArtikelController;

// Route untuk halaman utama
Route::get('/', function () {
    return view('welcome');
});

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::permanentRedirect('/', '/login');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('profil', ProfilController::class)->except('destroy');
Route::resource('manage-user', UserController::class);
Route::resource('manage-role', RoleController::class);
Route::resource('manage-menu', MenuController::class);
Route::resource('manage-permission', PermissionController::class)->only('store', 'destroy');

// Armada Routes
Route::resource('armada', ArmadaController::class);

// Driver Routes
Route::resource('petugas', PetugasController::class);

// Jadwal Routes
Route::resource('jadwal', JadwalController::class);

// Jadwal Operasional Routes
Route::resource('jadwal-operasional', JadwalOperasionalController::class);

// Lokasi TPS Routes
Route::resource('lokasi-tps', LokasiTpsController::class);

// Penugasan Armada Routes
Route::resource('penugasan-petugas', PenugasanPetugasController::class);

// Rute Routes
Route::resource('rute', RuteController::class);

// Rute Routes
Route::resource('rute-tps', RuteTpsController::class);

// Sampah Routes
Route::resource('sampah', SampahController::class);

// Laporan Warga Routes
Route::resource('laporan-warga', LaporanWargaController::class);

// Laporan TPS Routes
Route::resource('laporan-tps', LaporanTpsController::class);

Route::resource('tracking-armada', TrackingArmadaController::class)->only(['index', 'store', 'destroy']);

Route::apiResource('artikel', ArtikelController::class);

Route::get('dbbackup', [DBBackupController::class, 'DBDataBackup']);
