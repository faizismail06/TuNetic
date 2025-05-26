<?php

use App\Http\Controllers\DBBackupController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JadiPetugasController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ArmadaController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\JadwalOperasionalController;
use App\Http\Controllers\JadwalPengambilanController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\JadwalTemplateController;
use App\Http\Controllers\KelolaArmadaController;
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
use App\Http\Controllers\JadwalRuteController;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ===================
// AUTH & LANDING PAGE
// ===================
Route::get('/', function () {
    return view('landing-page');
});

Auth::routes(['verify' => true]);

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// ===================
// DASHBOARD
// ===================
Route::resource('admin/home', DashboardController::class);

// ===================
// PROFILE
// ===================
// Halaman Profil untuk User Biasa
Route::prefix('user')->name('user.')->middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'userIndex'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'userUpdate'])->name('profile.update');
    Route::post('/profile/upload-photo', [ProfileController::class, 'uploadPhoto'])->name('profile.upload-photo');

    // Dropdown wilayah dinamis
    Route::get('/get-regencies/{province_id}', [ProfileController::class, 'getRegencies'])->name('get.regencies');
    Route::get('/get-districts/{regency_id}', [ProfileController::class, 'getDistricts'])->name('get.districts');
    Route::get('/get-villages/{district_id}', [ProfileController::class, 'getVillages'])->name('get.villages');
});

// Route untuk pengaturan akun user
Route::prefix('user/akun')->name('user.akun.')->middleware('auth')->group(function () {
    Route::get('/', [ProfileController::class, 'akun'])->name('index');
    Route::put('/update', [ProfileController::class, 'updateAkun'])->name('update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
});

// Route untuk pengajuan jadi petugas
Route::prefix('user/jadipetugas')->name('user.jadipetugas.')->middleware('auth')->group(function () {
    Route::get('/', [jadipetugasController::class, 'JadiPetugasForm'])->name('form');
    Route::post('/', [jadipetugasController::class, 'submitPetugasRequest'])->name('submit');
});

// Route::resource('profile', ProfileController::class)->except('destroy');

// ===================
// USER, ROLE, MENU, PERMISSION MANAGEMENT
// ===================
Route::resource('manage-user', UserController::class);
Route::resource('manage-role', RoleController::class);
Route::resource('manage-menu', MenuController::class);
Route::resource('manage-petugas', PetugasController::class);
Route::resource('manage-rute', RuteController::class);
Route::resource('manage-permission', PermissionController::class)->only('store', 'destroy');
Route::get('petugas/{id}/edit', [PetugasController::class, 'edit'])->name('petugas.edit');
Route::put('petugas/{id}', [PetugasController::class, 'update'])->name('petugas.update');
Route::get('petugas/{id}/detail', [PetugasController::class, 'showDetail'])->name('petugas.detail');
Route::get('petugas/create', [PetugasController::class, 'create'])->name('petugas.create');
Route::post('petugas', [PetugasController::class, 'destroy'])->name('petugas.destroy');
Route::post('petugas', [PetugasController::class, 'index'])->name('petugas.index');
Route::get('/rute/{id}/detail', [RuteController::class, 'show'])->name('rute.detail');
Route::get('/rute/{id_rute}/detail', [RuteController::class, 'detail'])->name('rute.detail');
Route::get('/api/rute/{id}', [RuteController::class, 'getDetailJson'])->name('api.rute.detail');
Route::get('manage-rute/{id}/edit', [RuteController::class, 'edit'])->name('manage-rute.edit');
Route::get('manage-rute/create', [RuteController::class, 'create'])->name('manage-rute.create');
Route::delete('/manage-rute/{id}', [RuteController::class, 'destroy'])->name('manage-rute.destroy');
// ===================
// ARMADA
// ===================
Route::resource('armada', ArmadaController::class);
Route::resource('manage-armada', KelolaArmadaController::class);

// ===================
// PETUGAS
// ===================
// Route::resource('petugas', PetugasController::class);

Route::get('/petugas', [LaporanWargaController::class, 'index']);
Route::get('/petugas/{id}/detail', [PetugasController::class, 'showDetail'])->name('petugas.detail');

Route::prefix('jadwal-template')->group(function () {
    // Route::get('/', [JadwalTemplateController::class, 'index'])->name('jadwal-template.index');
    Route::get('/{hari}', [JadwalTemplateController::class, 'filterByDay'])->name('jadwal-template.filter');
    Route::post('/store', [JadwalTemplateController::class, 'store'])->name('jadwal-template.store');
    Route::get('/{id}/edit', [JadwalTemplateController::class, 'edit'])->name('jadwal-template.edit');
    Route::put('/{id}', [JadwalTemplateController::class, 'update'])->name('jadwal-template.update');
    Route::delete('/{id}', [JadwalTemplateController::class, 'destroy'])->name('jadwal-template.destroy');
    Route::get('/filter/{hari}', [JadwalTemplateController::class, 'filterByDay']);
});
Route::resource('jadwal-template', JadwalTemplateController::class)->except(['show']);


// Jadwal Routes
Route::get('/daftar-jadwal/generate', [JadwalController::class, 'generateForm'])->name('daftar-jadwal.generate.form');
Route::post('/daftar-jadwal/generate', [JadwalController::class, 'generateStore'])->name('daftar-jadwal.generate.store');
Route::resource('/daftar-jadwal', JadwalController::class);

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
// 1. Route dengan pola tetap (pastikan diletakkan sebelum route dinamis)
Route::get('/lokasi-tps', [LokasiTpsController::class, 'index'])->name('lokasi-tps.index');
Route::get('/lokasi-tps/create', [LokasiTpsController::class, 'create'])->name('lokasi-tps.create');
Route::post('/lokasi-tps', [LokasiTpsController::class, 'store'])->name('lokasi-tps.store');

Route::get('/lokasi-tps/get-regencies', [LokasiTpsController::class, 'getRegencies'])->name('lokasi-tps.getRegencies');
Route::get('/lokasi-tps/get-districts', [LokasiTpsController::class, 'getDistricts'])->name('lokasi-tps.getDistricts');
Route::get('/lokasi-tps/get-villages', [LokasiTpsController::class, 'getVillages'])->name('lokasi-tps.getVillages');

// ✅ Filter berdasarkan 'tipe' bertipe string (TPS, TPST, TPA)
Route::get('/lokasi-tps/filter/{tipe}', [LokasiTpsController::class, 'filterByTipe'])
    ->where('tipe', 'TPS|TPST|TPA') // ← optional: batasi hanya string valid
    ->name('lokasi-tps.filterByTipe');

Route::post('/lokasi-tps/find-nearest', [LokasiTpsController::class, 'findNearestTps'])->name('lokasi-tps.findNearest');
Route::get('/lokasi-tps-view', [LokasiTpsController::class, 'indexView'])->name('lokasi-tps.indexView');
Route::get('/rute-armada', [LokasiTpsController::class, 'ruteArmada'])->name('rute-armada.index');

// 2. Route dengan parameter dinamis
Route::get('/lokasi-tps/{lokasiTps}/edit', [LokasiTpsController::class, 'edit'])->name('lokasi-tps.edit');
Route::put('/lokasi-tps/{lokasiTps}', [LokasiTpsController::class, 'update'])->name('lokasi-tps.update');
Route::delete('/lokasi-tps/{lokasiTps}', [LokasiTpsController::class, 'destroy'])->name('lokasi-tps.destroy');
Route::get('/lokasi-tps/{id}', [LokasiTpsController::class, 'show'])->name('lokasi-tps.show');

// TRACKING ARMADA
// ===================
Route::resource('tracking-armada', TrackingArmadaController::class)->only(['index', 'store', 'destroy']);

// ===================
// ARTIKEL
// ===================
// Route::apiResource('artikel', ArtikelController::class);
Route::resource('artikel', ArtikelController::class);
Route::patch('/artikel/{id}/status', [ArtikelController::class, 'updateStatus'])->name('artikel.updateStatus');

// ===================
// BACKUP
// ===================
Route::get('dbbackup', [DBBackupController::class, 'DBDataBackup']);


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

//  Route Admin TPST
Route::prefix('tpst')->name('admin_tpst.')->group(function () {
    Route::get('/home', [DashboardController::class, 'indexTpst'])->name('admintpst.dashboard.index');
});

Route::get('/riwayat', [LaporanWargaController::class, 'riwayat'])->name('lapor.riwayat');
Route::get('/laporan/{id}', [LaporanWargaController::class, 'show'])->name('laporan.show');
Route::get('/lapor/{id}', [LaporanWargaController::class, 'show'])->name('lapor.detailRiwayat');

Route::get('/get-regencies/{province}', [ProfileController::class, 'getRegencies'])->name('get.regencies');
Route::get('/get-districts/{regency}', [ProfileController::class, 'getDistricts'])->name('get.districts');
Route::get('/get-villages/{district}', [ProfileController::class, 'getVillages'])->name('get.villages');


Route::prefix('petugas')->name('petugas.')->middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'petugasIndex'])->name('profile.index');
    Route::put('/profile/update', [ProfileController::class, 'petugasUpdate'])->name('profile.update');
    Route::post('/profile/upload-photo', [ProfileController::class, 'uploadPhoto'])->name('profile.upload-photo');
});

Route::get('/armada', function () {
    return view('armada');
})->name('armada');

Route::get('/lacak', function () {
    return view('masyarakat.lacak');
})->name('masyarakat.lacak');

Route::get('/lapor', function () {
    return view('masyarakat.lapor');
})->name('lapor');

Route::post('/lapor', [LaporanWargaController::class, 'store'])->name('lapor.submit');
Route::get('/lapor/form', [LaporanWargaController::class, 'create'])->name('lapor.form');
Route::get('/dashboard', [LaporanWargaController::class, 'dashboardPreview'])->middleware('auth');

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


    Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
        // Halaman Profil Admin
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/upload-photo', [ProfileController::class, 'uploadPhoto'])->name('profile.upload-photo');
        Route::get('/register', [UserController::class, 'showRegistrationForm'])->name('register');
        Route::get('/get-regencies/{province_id}', [UserController::class, 'getRegencies']);
        Route::get('/get-districts/{regency_id}', [UserController::class, 'getDistricts']);
        Route::get('/get-villages/{district_id}', [UserController::class, 'getVillages']);

        // Tambahkan resource lain di sini jika perlu
    });



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




// Routes untuk pengguna biasa yang ingin menjadi petugas
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/jadi-petugas/form', [JadiPetugasController::class, 'showForm'])->name('jadi-petugas.form');
    Route::post('/jadi-petugas/submit', [JadiPetugasController::class, 'submit'])->name('jadi-petugas.submit');
    Route::get('/jadi-petugas/success', [JadiPetugasController::class, 'success'])->name('jadi-petugas.success');
});

// Routes untuk CRUD petugas (khusus admin)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/petugas', [JadiPetugasController::class, 'index'])->name('jadi-petugas.index');
    Route::get('/petugas/create', [JadiPetugasController::class, 'create'])->name('jadi-petugas.create');
    Route::post('/petugas', [JadiPetugasController::class, 'store'])->name('jadi-petugas.store');
    Route::get('/petugas/{id}', [JadiPetugasController::class, 'show'])->name('jadi-petugas.show');
    Route::get('/petugas/{id}/edit', [JadiPetugasController::class, 'edit'])->name('jadi-petugas.edit');
    Route::put('/petugas/{id}', [JadiPetugasController::class, 'update'])->name('jadi-petugas.update');
    Route::delete('/petugas/{id}', [JadiPetugasController::class, 'destroy'])->name('jadi-petugas.destroy');
});

// ===================
// JADWAL PENGAMBILAN - TRACKING PETUGAS
// ===================
Route::middleware(['auth'])->prefix('petugas')->group(function () {
    Route::get('/jadwal-pengambilan', [JadwalPengambilanController::class, 'index'])
        ->name('petugas.jadwal-pengambilan.index');

    // Put the specific route FIRST
    Route::get('/jadwal-pengambilan/auto-tracking', [JadwalPengambilanController::class, 'showAutoTrackingPage'])
        ->name('jadwal-pengambilan.auto-tracking');

    Route::post('/jadwal-pengambilan/start-tracking/{id}', [JadwalPengambilanController::class, 'startTracking'])
        ->name('jadwal-pengambilan.start-tracking');

    Route::post('/jadwal-pengambilan/finish-tracking/{id}', [JadwalPengambilanController::class, 'finishTracking'])
        ->name('jadwal-pengambilan.finish-tracking');

    Route::post('/jadwal-pengambilan/save-location', [JadwalPengambilanController::class, 'saveLocation'])
        ->name('jadwal-pengambilan.save-location');

    Route::get('/jadwal-pengambilan/detail', function () {
        return view('petugas.jadwal-pengambilan.details');
    })->name('jadwal.penjemputan');
});
// Route untuk Jadwal & Rute
// Route Group untuk Jadwal Rute (dengan middleware auth jika diperlukan)
Route::group(['prefix' => 'tpst/jadwal-rute'], function () {

    // Route utama untuk menampilkan halaman jadwal operasional dengan peta
    Route::get('/', [JadwalRuteController::class, 'index'])->name('jadwal-rute.index');

    // Route untuk menampilkan detail jadwal operasional
    Route::get('/show/{id}', [JadwalRuteController::class, 'show'])->name('jadwal-rute.show');

    // Route untuk export data jadwal operasional
    Route::get('/export', [JadwalRuteController::class, 'export'])->name('jadwal-rute.export');

    // API Routes untuk AJAX calls
    Route::group(['prefix' => 'api'], function () {

        // API untuk mendapatkan detail armada (digunakan di modal)
        Route::get('/armada-detail/{id}', [JadwalRuteController::class, 'getArmadaDetail'])->name('jadwal-rute.api.armada-detail');

        // API untuk mendapatkan tracking terbaru
        Route::get('/tracking/{id}', [JadwalRuteController::class, 'getTracking'])->name('jadwal-rute.api.tracking');
    });
});
