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
use App\Http\Controllers\RuteArmadaController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ===================
// AUTH & LANDING PAGE
// ===================
Route::permanentRedirect('/', '/login');
Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// ===================
// DASHBOARD
// ===================
Route::resource('admin/home', DashboardController::class);

// ===================
// PROFILE
// ===================
Route::resource('profil', ProfilController::class)->except('destroy');

// ===================
// USER, ROLE, MENU, PERMISSION MANAGEMENT
// ===================
Route::resource('manage-user', UserController::class);
Route::resource('manage-role', RoleController::class);
Route::resource('manage-menu', MenuController::class);
Route::resource('manage-permission', PermissionController::class)->only('store', 'destroy');

// ===================
// ARMADA
// ===================
Route::resource('armada', ArmadaController::class);

// ===================
// PETUGAS
// ===================
Route::resource('petugas', PetugasController::class);

// ===================
// JADWAL
// ===================
Route::resource('jadwal', JadwalController::class);
Route::resource('jadwal-operasional', JadwalOperasionalController::class);

// ===================
// PENUGASAN
// ===================
Route::resource('penugasan-petugas', PenugasanPetugasController::class);

// ===================
// RUTE & RUTE TPS
// ===================
Route::resource('rute', RuteController::class);
Route::resource('rute-tps', RuteTpsController::class);

// ===================
// SAMPAH
// ===================
Route::resource('sampah', SampahController::class);

// ===================
// LOKASI TPS
// ===================
Route::get('/lokasi-tps', [LokasiTpsController::class, 'index'])->name('lokasi-tps.index');
Route::get('/lokasi-tps/create', [LokasiTpsController::class, 'create'])->name('lokasi-tps.create');
Route::post('/lokasi-tps', [LokasiTpsController::class, 'store'])->name('lokasi-tps.store');
Route::get('/lokasi-tps/{lokasiTps}/edit', [LokasiTpsController::class, 'edit'])->name('lokasi-tps.edit');
Route::put('/lokasi-tps/{lokasiTps}', [LokasiTpsController::class, 'update'])->name('lokasi-tps.update');
Route::delete('/lokasi-tps/{lokasiTps}', [LokasiTpsController::class, 'destroy'])->name('lokasi-tps.destroy');
Route::get('lokasi-tps/get-regencies', [LokasiTpsController::class, 'getRegencies'])->name('lokasi-tps.getRegencies');
Route::get('lokasi-tps/get-districts', [LokasiTpsController::class, 'getDistricts'])->name('lokasi-tps.getDistricts');
Route::get('lokasi-tps/get-villages', [LokasiTpsController::class, 'getVillages'])->name('lokasi-tps.getVillages');

// ===================
// TRACKING ARMADA
// ===================
Route::resource('tracking-armada', TrackingArmadaController::class)->only(['index', 'store', 'destroy']);

// ===================
// ARTIKEL
// ===================
Route::apiResource('artikel', ArtikelController::class);

// ===================
// BACKUP
// ===================
Route::get('dbbackup', [DBBackupController::class, 'DBDataBackup']);

// ===================
// MASYARAKAT (WARGA)
// ===================
Route::middleware('auth')->group(function () {
    Route::get('/masyarakat', [LaporanWargaController::class, 'index']);
    Route::get('/riwayat', [LaporanWargaController::class, 'riwayat'])->name('lapor.riwayat');
    Route::get('/dashboard', [LaporanWargaController::class, 'dashboardPreview']);
});

// Route publik untuk masyarakat (tidak perlu login)
Route::prefix('masyarakat')->name('masyarakat.')->group(function () {
    // Route untuk lapor sampah (publik)
    Route::get('/lapor', function () {
        return view('masyarakat.lapor');
    })->name('lapor');

    Route::post('/lapor', [LaporanWargaController::class, 'store'])->name('lapor.submit');
    Route::get('/lapor/form', [LaporanWargaController::class, 'create'])->name('lapor.form');

    // Route untuk rute armada (publik)
    Route::get('/rute-armada', [RuteArmadaController::class, 'index'])->name('rute-armada.index');
    Route::get('/rute-armada/all-tps', [RuteArmadaController::class, 'showAllTps'])->name('rute-armada.all-tps');
    Route::get('/rute-armada/jadwal/{id}', [RuteArmadaController::class, 'getJadwalDetail'])->name('rute-armada.jadwal-detail');
    Route::get('/rute-armada/tracking/{id}', [RuteArmadaController::class, 'getRealtimeTracking'])->name('rute-armada.realtime-tracking');
});

// ===================
// LAPORAN
// ===================
Route::resource('laporan-warga', LaporanWargaController::class);
Route::resource('laporan-tps', LaporanTpsController::class);

// ===================
// EMAIL VERIFIKASI
// ===================
Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('resent', true);
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');

// ===================
// JADWAL PENGAMBILAN - TRACKING PETUGAS
// ===================
Route::middleware(['auth'])->group(function () {
    Route::get('/jadwal-pengambilan', [JadwalPengambilanController::class, 'index'])
        ->name('petugas.jadwal-pengambilan.index');

    // Put the specific route FIRST
    Route::get('/jadwal-pengambilan/auto-tracking', [JadwalPengambilanController::class, 'showAutoTrackingPage'])
        ->name('jadwal-pengambilan.auto-tracking');
    Route::post('/petugas/jadwal-pengambilan/start-tracking/{id}', [JadwalPengambilanController::class, 'startTracking'])
        ->name('jadwal-pengambilan.start-tracking');
    Route::post('/petugas/jadwal-pengambilan/finish-tracking/{id}', [JadwalPengambilanController::class, 'finishTracking'])
        ->name('jadwal-pengambilan.finish-tracking');
    Route::post('/petugas/jadwal-pengambilan/save-location', [JadwalPengambilanController::class, 'saveLocation'])
        ->name('jadwal-pengambilan.save-location');
});

Route::get('/jadwal-pengambilan/detail', function () {
    return view('petugas.jadwal-pengambilan.details');
})->name('jadwal.penjemputan');
