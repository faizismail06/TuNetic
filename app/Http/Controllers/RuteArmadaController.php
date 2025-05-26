<?php

namespace App\Http\Controllers;

use App\Models\JadwalOperasional;
use App\Models\TrackingArmada;
use App\Models\LokasiTps;
use App\Models\Jadwal;
use App\Models\RuteTps;
use App\Models\Armada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RuteArmadaController extends Controller
{
    /**
     * Constructor untuk middleware auth
     * Untuk masyarakat, bisa tanpa auth atau dengan auth tapi tidak terbatas level
     */
    public function __construct()
    {
        // Uncomment jika ingin memerlukan autentikasi
        // $this->middleware('auth');
    }

    /**
     * Menampilkan halaman rute armada untuk masyarakat
     */
    /**
     * Menampilkan halaman rute armada untuk masyarakat
     */
    public function index(Request $request)
    {
        try {
            // Daftar hari yang valid
            $validDays = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];

            // Mendapatkan hari ini dalam bahasa Indonesia
            $today = Carbon::now()->locale('id');

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

            $selectedDay = $daysMapping[strtolower($today->format('l'))] ?? 'senin';

            // Ambil semua jadwal operasional untuk hari ini
            $jadwalOperasional = JadwalOperasional::with([
                'armada',
                'jadwal',
                'rute',
                'rute.ruteTps.lokasi_tps',
                'penugasanPetugas.petugas.user'
            ])
                ->whereHas('jadwal', function ($query) use ($selectedDay) {
                    $query->where('hari', $selectedDay);
                })
                ->get();

            // Ambil semua TPS yang terkait dengan jadwal operasional
            $allTps = [];
            $routeData = [];
            $armadaData = [];

            foreach ($jadwalOperasional as $jadwal) {
                // Dapatkan semua TPS dalam rute untuk jadwal ini
                $ruteId = $jadwal->rute->id ?? null;

                if ($ruteId) {
                    $tpsPoints = RuteTps::with('lokasi_tps')
                        ->where('id_rute', $ruteId)
                        ->get()
                        ->map(function ($ruteTps, $index) use ($jadwal) {
                            $lokasiTps = $ruteTps->lokasi_tps;

                            // Cek tracking untuk mengetahui status
                            $trackingData = TrackingArmada::where('id_jadwal_operasional', $jadwal->id)
                                ->latest('timestamp')
                                ->first();

                            $status = 'Belum';
                            $lastLocation = null;

                            if ($trackingData) {
                                $status = 'Progress';
                                $lastLocation = [
                                    'latitude' => $trackingData->latitude,
                                    'longitude' => $trackingData->longitude,
                                    'timestamp' => $trackingData->timestamp
                                ];

                                // Jika ada status 'selesai' di jadwal operasional
                                if ($jadwal->status == 2) {
                                    $status = 'Selesai';
                                }
                            }

                            return [
                                'id' => $lokasiTps->id,
                                'nama' => $lokasiTps->nama_lokasi ?? 'TPS',
                                'alamat' => $this->getFullAddress($lokasiTps),
                                'latitude' => $lokasiTps->latitude,
                                'longitude' => $lokasiTps->longitude,
                                'jadwal_id' => $jadwal->id,
                                'status' => $status,
                                'urutan' => $index + 1 // Menggunakan index array sebagai urutan
                            ];
                        })
                        ->toArray();

                    $allTps[$jadwal->id] = $tpsPoints;

                    // Simpan data rute
                    $routeData[$jadwal->id] = [
                        'nama_rute' => $jadwal->rute->nama_rute ?? 'Rute Tidak Tersedia',
                        'warna' => '#' . substr(md5($jadwal->id), 0, 6), // Generate warna unik per jadwal
                        'status' => $jadwal->status
                    ];

                    // Simpan data armada
                    $armadaData[$jadwal->id] = [
                        'no_polisi' => $jadwal->armada->no_polisi ?? 'N/A',
                        'jenis' => $jadwal->armada->jenis_kendaraan ?? $jadwal->armada->merk_kendaraan ?? 'N/A',
                        'kapasitas' => $jadwal->armada->kapasitas ?? 0,
                        'jam_aktif' => $jadwal->jam_aktif ?? 'N/A',
                        'status' => $jadwal->status, // 0=Belum, 1=Sedang, 2=Selesai
                        'petugas' => $jadwal->penugasanPetugas->map(function ($penugasan) {
                            return $penugasan->petugas->user->name ?? $penugasan->petugas->name ?? 'N/A';
                        })->join(', ')
                    ];

                    // Tambahkan lokasi terakhir armada jika ada
                    $lastTracking = TrackingArmada::where('id_jadwal_operasional', $jadwal->id)
                        ->latest('timestamp')
                        ->first();

                    if ($lastTracking) {
                        $armadaData[$jadwal->id]['last_location'] = [
                            'latitude' => $lastTracking->latitude,
                            'longitude' => $lastTracking->longitude,
                            'timestamp' => $lastTracking->timestamp
                        ];
                    }
                }
            }

            return view('masyarakat.rute-armada.index', [
                'jadwalOperasional' => $jadwalOperasional,
                'allTps' => $allTps,
                'routeData' => $routeData,
                'armadaData' => $armadaData,
                'selectedDay' => $selectedDay,
                'selectedDayInfo' => ucfirst($selectedDay)
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat menampilkan rute armada untuk masyarakat: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memproses request: ' . $e->getMessage());
        }
    }
    /**
     * Menampilkan semua lokasi TPS untuk masyarakat
     */
    public function showAllTps()
    {
        try {


            // Ambil semua lokasi TPS
            $allTps = LokasiTps::select('id', 'nama_lokasi', 'latitude', 'longitude', 'province_id', 'regency_id', 'district_id', 'village_id')
                ->with(['province', 'regency', 'district', 'village'])
                ->get()
                ->map(function ($tps) {
                    return [
                        'id' => $tps->id,
                        'nama' => $tps->nama_lokasi ?? 'TPS',
                        'alamat' => $this->getFullAddress($tps),
                        'latitude' => $tps->latitude,
                        'longitude' => $tps->longitude,
                        'jenis' => 'TPS', // Default jenis sebagai TPS
                        'icon_type' => 'trash' // Default icon
                    ];
                })
                ->toArray();

            return view('masyarakat.rute-armada.all-tps', [
                'allTps' => $allTps
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat menampilkan semua TPS untuk masyarakat: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memproses request: ' . $e->getMessage());
        }
    }

    /**
     * Mendapatkan detail jadwal operasional untuk AJAX
     */
    public function getJadwalDetail($id)
    {
        try {
            $jadwal = JadwalOperasional::with([
                'armada',
                'jadwal',
                'rute',
                'rute.ruteTps.lokasi_tps',
                'penugasanPetugas.petugas.user'
            ])->findOrFail($id);

            // Ambil tracking terbaru
            $lastTracking = TrackingArmada::where('id_jadwal_operasional', $id)
                ->latest('timestamp')
                ->first();

            $data = [
                'jadwal' => [
                    'id' => $jadwal->id,
                    'hari' => $jadwal->jadwal->hari,
                    'jam_aktif' => $jadwal->jam_aktif,
                    'status' => $jadwal->status
                ],
                'armada' => [
                    'no_polisi' => $jadwal->armada->no_polisi,
                    'jenis' => $jadwal->armada->jenis_kendaraan ?? $jadwal->armada->merk_kendaraan ?? 'N/A',
                    'kapasitas' => $jadwal->armada->kapasitas
                ],
                'rute' => [
                    'nama_rute' => $jadwal->rute->nama_rute
                ],
                'petugas' => $jadwal->penugasanPetugas->map(function ($penugasan) {
                    return [
                        'nama' => $penugasan->petugas->user->name ?? $penugasan->petugas->name ?? 'N/A',
                        'posisi' => $penugasan->tugas ?? 'N/A' // Menggunakan tugas dari atribut
                    ];
                }),
                'last_tracking' => $lastTracking ? [
                    'latitude' => $lastTracking->latitude,
                    'longitude' => $lastTracking->longitude,
                    'timestamp' => $lastTracking->timestamp
                ] : null
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat mengambil detail jadwal: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil detail jadwal.'
            ], 500);
        }
    }

    /**
     * Mendapatkan tracking realtime untuk jadwal tertentu
     */
    public function getRealtimeTracking($id)
    {
        try {
            $tracking = TrackingArmada::where('id_jadwal_operasional', $id)
                ->latest('timestamp')
                ->first();

            if (!$tracking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data tracking tersedia.'
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'latitude' => $tracking->latitude,
                    'longitude' => $tracking->longitude,
                    'timestamp' => $tracking->timestamp,
                    'jadwal_id' => $tracking->id_jadwal_operasional
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat mengambil realtime tracking: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data tracking.'
            ], 500);
        }
    }

    /**
     * Helper function untuk menentukan icon berdasarkan jenis TPS
     */
    private function getTpsIcon($jenis)
    {
        switch (strtoupper($jenis)) {
            case 'TPST':
                return 'recycle';
            case 'TPA':
                return 'industry';
            default:
                return 'trash';
        }
    }

    /**
     * Helper function untuk mengambil alamat lengkap
     */
    private function getFullAddress($lokasiTps)
    {
        $address = '';

        if ($lokasiTps->village) {
            $address .= $lokasiTps->village->name . ', ';
        }

        if ($lokasiTps->district) {
            $address .= $lokasiTps->district->name . ', ';
        }

        if ($lokasiTps->regency) {
            $address .= $lokasiTps->regency->name . ', ';
        }

        if ($lokasiTps->province) {
            $address .= $lokasiTps->province->name;
        }

        return rtrim($address, ', ');
    }
}
