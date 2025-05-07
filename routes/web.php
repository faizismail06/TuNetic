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
use App\Http\Controllers\JadwalTemplateController;
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

// Route::permanentRedirect('/', '/login');

Route::get('/', function () {
    return view('landing-page');
});

// Auth::routes();
Auth::routes(['verify' => true]);


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('admin/home', DashboardController::class);

Route::resource('profil', ProfilController::class)->except('destroy');
Route::resource('manage-user', UserController::class);
Route::resource('manage-role', RoleController::class);
Route::resource('manage-menu', MenuController::class);
Route::resource('manage-petugas', PetugasController::class);
Route::resource('manage-permission', PermissionController::class)->only('store', 'destroy');
Route::get('petugas/{id}/edit', [PetugasController::class, 'edit'])->name('petugas.edit');
Route::put('petugas/{id}', [PetugasController::class, 'update'])->name('petugas.update');
Route::get('petugas/{id}/detail', [PetugasController::class, 'showDetail'])->name('petugas.detail');
Route::get('petugas/create', [PetugasController::class, 'create'])->name('petugas.create');
Route::post('petugas', [PetugasController::class, 'store'])->name('petugas.store');

// Armada Routes
Route::resource('armada', ArmadaController::class);

// Driver Routes
Route::resource('petugas', PetugasController::class);

Route::prefix('jadwal-template')->group(function () {
    Route::get('/', [JadwalTemplateController::class, 'index'])->name('jadwal-template.index');
    Route::get('/{hari}', [JadwalTemplateController::class, 'filterByDay'])->name('jadwal-template.filter');
    Route::post('/store', [JadwalTemplateController::class, 'store'])->name('jadwal-template.store');
    Route::put('/{id}', [JadwalTemplateController::class, 'update'])->name('jadwal-template.update');
    Route::delete('/{id}', [JadwalTemplateController::class, 'destroy'])->name('jadwal-template.destroy');
    Route::get('/filter/{hari}', [JadwalTemplateController::class, 'filterByDay']);
});
Route::resource('jadwal-template', JadwalTemplateController::class)->except(['show']);


// Jadwal Routes
Route::get('/daftar-jadwal/generate', [JadwalController::class, 'generateForm'])->name('daftar-jadwal.generate.form');
Route::post('/daftar-jadwal/generate', [JadwalController::class, 'generateStore'])->name('daftar-jadwal.generate.store');
Route::resource('daftar-jadwal', JadwalController::class);

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

Route::get('user/rute-armada', [LokasiTpsController::class, 'ruteArmada'])->name('rute-armada.index');

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

// Route::get('/masyarakat', [LaporanWargaController::class, 'index'])->middleware('auth');
Route::get('/masyarakat', [LaporanWargaController::class, 'index'])->middleware('auth');



Route::get('/lapor', function () {
    return view('masyarakat.lapor');
})->name('lapor');

Route::get('/riwayat', [LaporanWargaController::class, 'riwayat'])->name('lapor.riwayat');
Route::get('/laporan/{id}', [LaporanWargaController::class, 'show'])->name('laporan.show');

Route::get('/get-regencies/{province}', [ProfilController::class, 'getRegencies'])->name('get.regencies');
Route::get('/get-districts/{regency}', [ProfilController::class, 'getDistricts'])->name('get.districts');
Route::get('/get-villages/{district}', [ProfilController::class, 'getVillages'])->name('get.villages');

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


// Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
//     // Halaman Profil Admin
//     Route::get('/profile', [ProfilController::class, 'index'])->name('profile.index');
//     Route::put('/profile', [ProfilController::class, 'update'])->name('profile.update');
//     Route::post('/profile/upload-photo', [ProfilController::class, 'uploadPhoto'])->name('profile.upload-photo');
//     Route::get('/index', [UserController::class, 'showRegistrationForm'])->name('index');
//     Route::get('/get-regencies/{province_id}', [ProfilController::class, 'getRegencies']);
//     Route::get('/get-districts/{regency_id}', [ProfilController::class, 'getDistricts']);
//     Route::get('/get-villages/{district_id}', [ProfilController::class, 'getVillages']);

//     // Tambahkan resource lain di sini jika perlu
// });

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    // Halaman Profil Admin
    Route::get('/profile', [ProfilController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfilController::class, 'update'])->name('profile.update');
    Route::post('/profile/upload-photo', [ProfilController::class, 'uploadPhoto'])->name('profile.upload-photo');
    Route::get('/index', [UserController::class, 'showRegistrationForm'])->name('index');
    
    // Route untuk data wilayah
    Route::get('/get-regencies/{province_id}', [ProfilController::class, 'getRegencies'])->name('get.regencies');
    Route::get('/get-districts/{regency_id}', [ProfilController::class, 'getDistricts'])->name('get.districts');
    Route::get('/get-villages/{district_id}', [ProfilController::class, 'getVillages'])->name('get.villages');
});

// Halaman Profil untuk User Biasa
Route::prefix('user')->name('user.')->middleware('auth')->group(function () {
    Route::get('/profile', [ProfilController::class, 'userIndex'])->name('profile.index');
    Route::put('/profile', [ProfilController::class, 'userUpdate'])->name('profile.update');
    Route::post('/profile/upload-photo', [ProfilController::class, 'uploadPhoto'])->name('profile.upload-photo');

    // Dropdown wilayah dinamis
    Route::get('/get-regencies/{province_id}', [ProfilController::class, 'getRegencies'])->name('get.regencies');
    Route::get('/get-districts/{regency_id}', [ProfilController::class, 'getDistricts'])->name('get.districts');
    Route::get('/get-villages/{district_id}', [ProfilController::class, 'getVillages'])->name('get.villages');
});

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
