<?php

namespace App\Http\Controllers;

use App\Models\JadwalOperasional;
use App\Models\TrackingArmada;
use App\Models\LokasiTps;
use App\Models\Jadwal;
use App\Models\Petugas;
use App\Models\RuteTps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema; // Tambahkan import untuk Schema
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class JadwalPengambilanController extends Controller
{
    /**
     * Constructor untuk middleware auth
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Halaman untuk petugas melakukan auto tracking
     */
    /**
     * Menampilkan halaman auto tracking untuk hari ini
     * Method ini akan menavigasikan ke halaman index.blade.php
     */
    /**
     * Menampilkan halaman auto tracking berdasarkan hari yang dipilih atau hari ini
     */
    public function showAutoTrackingPage(Request $request, $jadwalId = null)
    {
        try {
            // Check if the logged-in user is a petugas (level 3)
            $user = Auth::user();

            if (!$user || $user->level != 3) {
                Log::error('User tidak terdaftar sebagai petugas operasional (level 3).');
                return redirect()->route('home')
                    ->with('error', 'Anda tidak terdaftar sebagai petugas operasional.');
            }

            // Get the petugas record associated with this user
            $petugas = \App\Models\Petugas::where('user_id', $user->id)->first();

            if (!$petugas) {
                Log::error('User level 3 tidak memiliki data petugas terkait.');
                return redirect()->route('home')
                    ->with('error', 'Data petugas tidak ditemukan.');
            }

            $petugasId = $petugas->id;

            // Jika jadwalId diberikan, langsung ambil jadwal operasional tersebut
            if ($jadwalId) {
                $jadwalOperasional = JadwalOperasional::with([
                    'armada',
                    'jadwal',
                    'rute',
                    'rute.ruteTps.lokasi_tps' // Memuat lokasi TPS
                ])
                    ->whereIn('status', [0, 1]) // Status 0=Belum Berjalan, 1=Sedang Berjalan
                    ->whereHas('penugasanPetugas', function ($query) use ($petugasId) {
                        $query->where('id_petugas', $petugasId);
                    })
                    ->where('id', $jadwalId)
                    ->first();

                if (!$jadwalOperasional) {
                    Log::warning('Jadwal operasional dengan ID ' . $jadwalId . ' tidak ditemukan atau tidak diotorisasi.');
                    return redirect()->route('petugas.jadwal-pengambilan.index')
                        ->with('error', 'Jadwal operasional tidak ditemukan atau Anda tidak memiliki akses.');
                }

                $selectedDay = $jadwalOperasional->jadwal->hari;
            } else {
                // Mendapatkan parameter hari dari URL jika ada
                $selectedDay = strtolower($request->query('day', ''));

                // Daftar hari yang valid
                $validDays = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];

                // Menggunakan hari ini jika parameter hari tidak valid atau tidak ada
                if (!in_array($selectedDay, $validDays)) {
                    // Mendapatkan jadwal untuk hari ini
                    $today = Carbon::now()->locale('id');
                    $todayName = strtolower($today->translatedFormat('l')); // Mendapatkan nama hari dalam bahasa Indonesia

                    // Mapping nama hari dalam bahasa Inggris ke Indonesia
                    $daysMapping = [
                        'monday' => 'senin',
                        'tuesday' => 'selasa',
                        'wednesday' => 'rabu',
                        'thursday' => 'kamis',
                        'friday' => 'jumat',
                        'saturday' => 'sabtu',
                        'sunday' => 'minggu',
                    ];

                    // Jika ada pemetaan untuk nama hari dalam bahasa Inggris, gunakan itu
                    if (array_key_exists(strtolower($today->format('l')), $daysMapping)) {
                        $todayName = $daysMapping[strtolower($today->format('l'))];
                    }

                    $selectedDay = $todayName;
                }

                // Ambil jadwal operasional yang belum selesai (status 0 atau 1)
                $jadwalOperasionalQuery = JadwalOperasional::with([
                    'armada',
                    'jadwal',
                    'rute',
                    'rute.ruteTps.lokasi_tps'
                ])
                    ->whereIn('status', [0, 1]) // Status 0=Belum Berjalan, 1=Sedang Berjalan
                    ->whereHas('penugasanPetugas', function ($query) use ($petugasId) {
                        $query->where('id_petugas', $petugasId);
                    })
                    ->whereHas('jadwal', function ($query) use ($selectedDay) {
                        $query->where('hari', $selectedDay);
                    });

                // Tambahkan log untuk debugging
                Log::info('Query jadwal status 0/1: ' . $jadwalOperasionalQuery->toSql());
                Log::info('Parameter query: ' . json_encode($jadwalOperasionalQuery->getBindings()));

                $jadwalOperasional = $jadwalOperasionalQuery->get();
            }

            // Ambil semua TPS yang terkait dengan jadwal operasional
            $allTps = [];

            // TAMBAHAN: Data untuk info petugas
            $tpsLocations = [];
            $jumlahLokasi = 0;
            $hariInfo = ucfirst($selectedDay);

            // Cek apakah $jadwalOperasional adalah collection atau single instance
            if (is_a($jadwalOperasional, 'Illuminate\Database\Eloquent\Collection')) {
                // Menggunakan jadwal pertama untuk informasi
                $firstJadwal = $jadwalOperasional->first();

                if ($firstJadwal) {
                    foreach ($jadwalOperasional as $jadwal) {
                        // Dapatkan semua TPS dalam rute untuk jadwal ini
                        $ruteId = $jadwal->rute->id;

                        $tpsPoints = \App\Models\RuteTps::with('lokasi_tps')
                            ->where('id_rute', $ruteId)
                            ->orderBy('id') // Asumsikan urutan sesuai dengan ID
                            ->get()
                            ->map(function ($ruteTps) use ($jadwal) {
                                $lokasiTps = $ruteTps->lokasi_tps;

                                // PERBAIKAN: Hanya cek tracking berdasarkan id_jadwal_operasional
                                // karena tabel tracking_armada tidak memiliki kolom id_tps
                                $trackingData = TrackingArmada::where('id_jadwal_operasional', $jadwal->id)
                                    ->first();

                                $status = 'Belum';
                                if ($trackingData) {
                                    // Jika ada tracking untuk jadwal ini, anggap semua TPS dalam progress
                                    $status = 'Progress';

                                    // Tambahkan logika tambahan jika diperlukan untuk menentukan status 'Selesai'
                                    // Contoh: jika ada field status di tracking_armada
                                    if (Schema::hasColumn('tracking_armada', 'status')) {
                                        if ($trackingData->status == 2) {
                                            $status = 'Selesai';
                                        }
                                    }
                                }

                                return [
                                    'id' => $lokasiTps->id,
                                    'nama' => $lokasiTps->nama_lokasi,
                                    'latitude' => $lokasiTps->latitude,
                                    'longitude' => $lokasiTps->longitude,
                                    'jadwal_id' => $jadwal->id,
                                    'status' => $status,
                                    'urutan' => $ruteTps->urutan // Tambahkan informasi urutan ke data yang dikirim ke view
                                ];
                            })
                            ->toArray();

                        $allTps[$jadwal->id] = $tpsPoints;

                        // Tambahkan ke tpsLocations untuk info petugas
                        $tpsLocations = array_merge($tpsLocations, $tpsPoints);
                        $jumlahLokasi += count($tpsPoints);
                    }
                }
            } else {
                // Single jadwal operasional
                if ($jadwalOperasional) {
                    $ruteId = $jadwalOperasional->rute->id;

                    $tpsPoints = \App\Models\RuteTps::with('lokasi_tps')
                        ->where('id_rute', $ruteId)
                        ->orderBy('urutan') // Use the explicit ordering field instead of ID
                        ->get()
                        ->map(function ($ruteTps) use ($jadwalOperasional) {

                            $lokasiTps = $ruteTps->lokasi_tps;

                            // PERBAIKAN: Hanya cek tracking berdasarkan id_jadwal_operasional
                            // karena tabel tracking_armada tidak memiliki kolom id_tps
                            $trackingData = TrackingArmada::where('id_jadwal_operasional', $jadwalOperasional->id)
                                ->first();

                            $status = 'Belum';
                            if ($trackingData) {
                                // Jika ada tracking untuk jadwal ini, anggap semua TPS dalam progress
                                $status = 'Progress';

                                // Tambahkan logika tambahan jika diperlukan untuk menentukan status 'Selesai'
                                // Contoh: jika ada field status di tracking_armada
                                if (Schema::hasColumn('tracking_armada', 'status')) {
                                    if ($trackingData->status == 2) {
                                        $status = 'Selesai';
                                    }
                                }
                            }

                            return [
                                'id' => $lokasiTps->id,
                                'nama' => $lokasiTps->nama_tps,
                                'latitude' => $lokasiTps->latitude,
                                'longitude' => $lokasiTps->longitude,
                                'jadwal_id' => $jadwalOperasional->id,
                                'status' => $status,
                                'urutan' => $ruteTps->urutan // Tambahkan informasi urutan ke data yang dikirim ke view
                            ];
                        })
                        ->toArray();

                    $allTps[$jadwalOperasional->id] = $tpsPoints;

                    // Tambahkan ke tpsLocations untuk info petugas
                    $tpsLocations = $tpsPoints;
                    $jumlahLokasi = count($tpsPoints);
                }
            }

            // Tambahkan informasi hari yang dipilih
            $selectedDayInfo = ucfirst($selectedDay);

            return view('petugas.jadwal-pengambilan.index', [
                'jadwalOperasional' => $jadwalOperasional,
                'allTps' => $allTps,
                'selectedDayInfo' => $selectedDayInfo,
                // Tambahkan data untuk info petugas
                'hari' => $hariInfo,
                'petugas' => $petugas,
                'tps_locations' => $tpsLocations,
                'jumlah_lokasi' => $jumlahLokasi
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat menampilkan jadwal operasional: ' . $e->getMessage());
            return redirect()->route('petugas.jadwal-pengambilan.index')
                ->with('error', 'Terjadi kesalahan saat memproses request: ' . $e->getMessage());
        }
    }


    /**
     * Mulai tracking untuk jadwal operasional tertentu
     */
    public function startTracking(Request $request, $id)
    {
        try {
            $jadwalOperasional = JadwalOperasional::findOrFail($id);

            // Verifikasi petugas
            $petugasId = Auth::user()->petugas->id ?? null;
            $authorized = $jadwalOperasional->penugasanPetugas()
                ->where('id_petugas', $petugasId)
                ->exists();

            if (!$authorized) {
                Log::warning('Petugas tidak terotorisasi untuk jadwal operasional ID: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk jadwal operasional ini'
                ], 403);
            }

            // Ubah status jika masih "Belum Berjalan"
            if ($jadwalOperasional->status == 0) {
                $jadwalOperasional->status = 1; // Sedang Berjalan
                $jadwalOperasional->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Tracking dimulai',
                'jadwal' => $jadwalOperasional
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat memulai tracking: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memulai tracking.'
            ], 500);
        }
    }



    /**
     * Selesaikan tracking untuk jadwal operasional tertentu
     */
    public function finishTracking(Request $request, $id)
    {
        try {
            $jadwalOperasional = JadwalOperasional::findOrFail($id);

            // Verifikasi petugas
            $petugasId = Auth::user()->petugas->id ?? null;
            $authorized = $jadwalOperasional->penugasanPetugas()
                ->where('id_petugas', $petugasId)
                ->exists();

            if (!$authorized) {
                Log::warning('Petugas tidak terotorisasi untuk jadwal operasional ID: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk jadwal operasional ini'
                ], 403);
            }

            // Ubah status jika "Sedang Berjalan"
            if ($jadwalOperasional->status == 1) {
                $jadwalOperasional->status = 2; // Selesai
                $jadwalOperasional->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Tracking selesai',
                'jadwal' => $jadwalOperasional
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat menyelesaikan tracking: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyelesaikan tracking.'
            ], 500);
        }
    }

    /**
     * Simpan lokasi dari perangkat petugas
     */
    /**
     * Simpan lokasi dari perangkat petugas
     */
    public function saveLocation(Request $request)
    {
        try {
            $request->validate([
                'id_jadwal_operasional' => 'required|exists:jadwal_operasional,id',
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
            ]);

            // Verifikasi petugas
            $petugasId = Auth::user()->petugas->id ?? null;
            $jadwalOperasional = JadwalOperasional::findOrFail($request->id_jadwal_operasional);

            $authorized = $jadwalOperasional->penugasanPetugas()
                ->where('id_petugas', $petugasId)
                ->exists();

            if (!$authorized) {
                Log::warning('Petugas tidak terotorisasi untuk menyimpan lokasi di jadwal operasional ID: ' . $request->id_jadwal_operasional);
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk jadwal operasional ini'
                ], 403);
            }

            // Verifikasi status jadwal
            if (!in_array($jadwalOperasional->status, [0, 1])) {
                Log::warning('Jadwal operasional tidak valid untuk tracking status: ' . $jadwalOperasional->status);
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal operasional tidak dalam status yang valid untuk tracking'
                ], 400);
            }

            // PERUBAHAN: Cari tracking yang sudah ada untuk jadwal ini
            $tracking = TrackingArmada::where('id_jadwal_operasional', $request->id_jadwal_operasional)
                ->first();

            if ($tracking) {
                // Update tracking yang sudah ada
                $tracking->update([
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'timestamp' => now(),
                ]);
            } else {
                // Buat tracking baru jika belum ada
                $tracking = TrackingArmada::create([
                    'id_jadwal_operasional' => $request->id_jadwal_operasional,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'timestamp' => now(),
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $tracking
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat menyimpan lokasi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan lokasi.'
            ], 500);
        }
    }

    /**
     * Menampilkan halaman pemilihan hari jadwal pengambilan
     * Untuk tampilan pada Gambar 1
     */
    public function index()
    {
        try {
            // Mendapatkan hari ini
            $today = Carbon::now()->locale('id');
            $todayName = strtolower($today->translatedFormat('l')); // Mendapatkan nama hari dalam bahasa Indonesia

            // Mapping nama hari dalam bahasa Inggris ke Indonesia
            $daysMapping = [
                'monday' => 'senin',
                'tuesday' => 'selasa',
                'wednesday' => 'rabu',
                'thursday' => 'kamis',
                'friday' => 'jumat',
                'saturday' => 'sabtu',
                'sunday' => 'minggu',
            ];

            // Jika ada pemetaan untuk nama hari dalam bahasa Inggris, gunakan itu
            if (array_key_exists(strtolower($today->format('l')), $daysMapping)) {
                $todayName = $daysMapping[strtolower($today->format('l'))];
            }

            // Mendapatkan user saat ini
            $user = Auth::user();

            if (!$user || $user->level != 3) {
                Log::error('User tidak terdaftar sebagai petugas operasional (level 3).');
                return redirect()->route('home')
                    ->with('error', 'Anda tidak terdaftar sebagai petugas operasional.');
            }

            $petugas = Petugas::where('user_id', $user->id)->first();

            if (!$petugas) {
                return redirect()->route('home')
                    ->with('error', 'Data petugas tidak ditemukan.');
            }

            // Dapatkan jadwal operasional untuk petugas ini
            $jadwalOperasional = JadwalOperasional::with(['jadwal'])
                ->whereHas('penugasanPetugas', function ($query) use ($petugas) {
                    $query->where('id_petugas', $petugas->id);
                })
                ->get();

            // Buat array untuk menyimpan id jadwal operasional berdasarkan hari
            $jadwalHariIds = [];
            foreach ($jadwalOperasional as $jadwal) {
                $hari = strtolower($jadwal->jadwal->hari);
                if (!isset($jadwalHariIds[$hari])) {
                    $jadwalHariIds[$hari] = $jadwal->id;
                }
            }

            // Mendapatkan semua hari yang ada jadwalnya
            $jadwalHari = array_keys($jadwalHariIds);

            return view('petugas.jadwal-pengambilan.days', compact('jadwalHari', 'jadwalHariIds', 'todayName'));
        } catch (\Exception $e) {
            Log::error('Error saat menampilkan jadwal pengambilan berdasarkan hari: ' . $e->getMessage());
            return redirect()->route('home')
                ->with('error', 'Terjadi kesalahan saat memuat jadwal pengambilan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail jadwal pengambilan sampah per hari
     * Untuk tampilan pada Gambar 2
     */
    public function detail($day)
    {
        try {
            // Validasi nama hari yang diterima
            $daysIndo = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];

            if (!in_array(strtolower($day), $daysIndo)) {
                return redirect()->route('petugas.jadwal-pengambilan.index')
                    ->with('error', 'Hari tidak valid.');
            }

            // Get user petugas
            $user = Auth::user();

            if (!$user || $user->level != 3) {
                Log::error('User tidak terdaftar sebagai petugas operasional (level 3).');
                return redirect()->route('home')
                    ->with('error', 'Anda tidak terdaftar sebagai petugas operasional.');
            }

            $petugas = \App\Models\Petugas::where('user_id', $user->id)->first();

            if (!$petugas) {
                return redirect()->route('home')
                    ->with('error', 'Data petugas tidak ditemukan.');
            }

            // Ambil jadwal operasional berdasarkan hari
            $jadwalOperasional = JadwalOperasional::with([
                'armada',
                'jadwal',
                'rute.ruteTps.lokasi_tps',
                'rute'
            ])
                ->whereHas('jadwal', function ($query) use ($day) {
                    $query->where('hari', strtolower($day));
                })
                ->whereHas('penugasanPetugas', function ($query) use ($petugas) {
                    $query->where('id_petugas', $petugas->id);
                })
                ->first();

            if (!$jadwalOperasional) {
                return redirect()->route('petugas.jadwal-pengambilan.index')
                    ->with('info', 'Tidak ada jadwal pengambilan untuk hari ' . ucfirst($day) . '.');
            }

            // Ambil semua TPS di rute
            $ruteTps = $jadwalOperasional->rute->ruteTps;
            $rute = $jadwalOperasional->rute;

            // Ambil semua TPS dalam rute
            $tpsLocations = RuteTps::with('lokasi_tps')
                ->where('id_rute', $rute->id)
                ->orderBy('urutan') // Gunakan kolom urutan untuk pengurutan
                ->get()
                ->map(function ($ruteTps) use ($jadwalOperasional) {
                    $lokasiTps = $ruteTps->lokasi_tps;
                    $trackingData = TrackingArmada::where('id_jadwal_operasional', $jadwalOperasional->id)
                        ->where('id_tps', $lokasiTps->id)
                        ->first();

                    $status = 'Belum';
                    $waktuMulai = null;
                    $waktuSelesai = null;

                    if ($trackingData) {
                        if ($trackingData->status == 1) {
                            $status = 'Progress';
                            $waktuMulai = Carbon::parse($trackingData->waktu_mulai)->format('H:i');
                        } elseif ($trackingData->status == 2) {
                            $status = 'Selesai';
                            $waktuMulai = Carbon::parse($trackingData->waktu_mulai)->format('H:i');
                            $waktuSelesai = Carbon::parse($trackingData->waktu_selesai)->format('H:i');
                        }
                    }

                    return [
                        'id' => $lokasiTps->id,
                        'nama' => $lokasiTps->nama_tps,
                        'alamat' => $lokasiTps->alamat,
                        'latitude' => $lokasiTps->latitude,
                        'longitude' => $lokasiTps->longitude,
                        'status' => $status,
                        'waktu_mulai' => $waktuMulai,
                        'waktu_selesai' => $waktuSelesai
                    ];
                })
                ->toArray();

            // Data untuk tampilan
            $data = [
                'hari' => ucfirst($day),
                'jadwal_operasional' => $jadwalOperasional,
                'petugas' => $petugas,
                'tps_locations' => $tpsLocations,
                'jumlah_lokasi' => count($tpsLocations)
            ];

            // Arahkan ke halaman index.blade.php untuk hari yang dipilih
            return view('petugas.jadwal-pengambilan.index', $data);
        } catch (\Exception $e) {
            Log::error('Error saat menampilkan detail jadwal pengambilan: ' . $e->getMessage());
            return redirect()->route('petugas.jadwal-pengambilan.index')
                ->with('error', 'Terjadi kesalahan saat memuat detail jadwal: ' . $e->getMessage());
        }
    }
}
