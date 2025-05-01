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
use App\Http\Controllers\JadwalPengambilanController;
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
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

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
// Route untuk halaman utama
Route::get('/', function () {
    return view('welcome');
});

Route::permanentRedirect('/', '/login');

// Auth::routes();
Auth::routes(['verify' => true]);


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('admin/home', DashboardController::class);

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

// Route untuk menampilkan halaman peta dan daftar lokasi TPS
Route::get('/lokasi-tps', [LokasiTpsController::class, 'index'])->name('lokasi-tps.index');

// Route berikut dapat diaktifkan jika Anda ingin menggunakan fitur CRUD
Route::get('/lokasi-tps/create', [LokasiTpsController::class, 'create'])->name('lokasi-tps.create');
Route::post('/lokasi-tps', [LokasiTpsController::class, 'store'])->name('lokasi-tps.store');
Route::get('/lokasi-tps/{lokasiTps}/edit', [LokasiTpsController::class, 'edit'])->name('lokasi-tps.edit');
Route::put('/lokasi-tps/{lokasiTps}', [LokasiTpsController::class, 'update'])->name('lokasi-tps.update');
Route::delete('/lokasi-tps/{lokasiTps}', [LokasiTpsController::class, 'destroy'])->name('lokasi-tps.destroy');
Route::get('lokasi-tps/get-regencies', [LokasiTpsController::class, 'getRegencies'])->name('lokasi-tps.getRegencies');
Route::get('lokasi-tps/get-districts', [LokasiTpsController::class, 'getDistricts'])->name('lokasi-tps.getDistricts');
Route::get('lokasi-tps/get-villages', [LokasiTpsController::class, 'getVillages'])->name('lokasi-tps.getVillages');

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

Route::get('/masyarakat', [LaporanWargaController::class, 'index'])->middleware('auth');

// Route::get('/masyarakat/lapor', function () {
//     return view('masyarakat.lapor');
// })->name('lapor');

Route::get('/riwayat', [LaporanWargaController::class, 'riwayat'])->name('lapor.riwayat');


Route::get('/armada', function () {
    return view('armada');
})->name('armada');


// Route::get('/masyarakat/lacak', [LokasiTpsController::class, 'ruteArmada'])->name('rute-armada.index');

Route::get('/lacak', function () {
    return view('masyarakat.lacak');
})->name('masyarakat.lacak');

Route::get('/masyarakat/lapor', function () {
    return view('masyarakat.lapor');
})->name('lapor');

Route::post('/masyarakat/lapor', [LaporanWargaController::class, 'store'])->name('lapor.submit');
Route::get('/masyarakat/lapor/form', [LaporanWargaController::class, 'create'])->name('lapor.form');
Route::get('/dashboard', [LaporanWargaController::class, 'dashboardPreview'])->middleware('auth');

Route::get('/email/verify', function () {
    return view('auth.verify'); // atau view lain sesuai struktur kamu
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); // Verifikasi email user
    return redirect('/home'); // Atau arahkan ke halaman sukses lainnya
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('resent', true);
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');

Route::middleware(['auth'])->group(function () {
    // Route untuk halaman auto tracking
    Route::get('/petugas/jadwal-pengambilan/auto-tracking', [JadwalPengambilanController::class, 'showAutoTrackingPage'])->name('jadwal-pengambilan.auto-tracking');
    // Route untuk mulai tracking
    Route::post('/petugas/jadwal-pengambilan/start-tracking/{id}', [JadwalPengambilanController::class, 'startTracking'])->name('jadwal-pengambilan.start-tracking');
    // Route untuk selesai tracking
    Route::post('/petugas/jadwal-pengambilan/finish-tracking/{id}', [JadwalPengambilanController::class, 'finishTracking'])->name('jadwal-pengambilan.finish-tracking');
    // Route untuk menyimpan lokasi tracking
    Route::post('/petugas/jadwal-pengambilan/save-location', [JadwalPengambilanController::class, 'saveLocation'])->name('jadwal-pengambilan.save-location');
});
