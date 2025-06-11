<?php

namespace App\Http\Controllers;

use App\Models\JadwalOperasional;
use App\Models\TrackingArmada;
use App\Models\LokasiTps;
use App\Models\RuteTps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class JadwalRuteController extends Controller
{
    /**
     * Constructor untuk middleware auth (optional)
     */
    public function __construct()
    {
        // Uncomment jika ingin memerlukan autentikasi
        // $this->middleware('auth');
    }

    /**
     * Menampilkan halaman jadwal operasional dengan peta
     */
    public function index(Request $request)
    {
        try {
            // Parameter untuk filtering dan pagination
            $perPage = $request->get('per_page', 10);
            $search = $request->get('search');
            $statusFilter = $request->get('status');
            $dateFilter = $request->get('date');

            // Query dasar dengan relasi yang benar
            $query = JadwalOperasional::with([
                'armada',
                'jadwal',
                'rute',
                'rute.ruteTps.lokasi_tps'
            ]);

            // Filter berdasarkan pencarian
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('armada', function ($subQ) use ($search) {
                        $subQ->where('no_polisi', 'like', '%' . $search . '%')
                            ->orWhere('merk_kendaraan', 'like', '%' . $search . '%')
                            ->orWhere('jenis_kendaraan', 'like', '%' . $search . '%');
                    })
                        ->orWhereHas('rute', function ($subQ) use ($search) {
                            $subQ->where('nama_rute', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('jadwal', function ($subQ) use ($search) {
                            $subQ->where('hari', 'like', '%' . $search . '%');
                        });
                });
            }

            // Filter berdasarkan status
            if ($statusFilter !== null && $statusFilter !== '') {
                $query->where('status', $statusFilter);
            }

            // Filter berdasarkan tanggal
            if ($dateFilter) {
                $query->whereDate('tanggal', $dateFilter);
            }

            // Urutkan berdasarkan tanggal terbaru
            $query->orderBy('tanggal', 'desc')
                ->orderBy('jam_aktif', 'asc');

            // Ambil data dengan pagination
            $jadwalOperasional = $query->paginate($perPage);

            // Siapkan data untuk view
            $tableData = [];
            $mapData = [];
            $allTps = [];
            $routeColors = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7', '#DDA0DD', '#98D8C8', '#F7DC6F'];

            foreach ($jadwalOperasional as $index => $jadwal) {
                // Data untuk tabel
                $tableData[] = [
                    'id' => $jadwal->id,
                    'tanggal' => Carbon::parse($jadwal->tanggal)->format('d-m-Y'),
                    'hari' => $this->formatHari($jadwal->jadwal->hari ?? 'N/A'),
                    'armada' => $jadwal->armada->no_polisi ?? 'N/A',
                    'rute' => $jadwal->rute->nama_rute ?? 'N/A',
                    'jam_aktif' => $jadwal->jam_aktif ? Carbon::parse($jadwal->jam_aktif)->format('H:i') : 'N/A',
                    'status' => $jadwal->status,
                    'status_text' => $this->getStatusText($jadwal->status),
                    'status_class' => $this->getStatusClass($jadwal->status),
                    'created_at' => $jadwal->created_at,
                    'updated_at' => $jadwal->updated_at
                ];

                // Data untuk peta
                $color = $routeColors[$index % count($routeColors)];

                // Ambil TPS untuk rute ini dengan urutan yang benar
                $tpsData = [];
                if ($jadwal->rute) {
                    // PERUBAHAN: Mengambil data TPS berdasarkan urutan seperti di JadwalPengambilanController
                    $tpsPoints = RuteTps::with('lokasi_tps')
                        ->where('id_rute', $jadwal->rute->id)
                        ->orderBy('urutan') // Menggunakan kolom urutan untuk pengurutan
                        ->get()
                        ->map(function ($ruteTps) {
                            $lokasiTps = $ruteTps->lokasi_tps;
                            if ($lokasiTps) {
                                return [
                                    'id' => $lokasiTps->id,
                                    'nama_lokasi' => $lokasiTps->nama_lokasi,
                                    'latitude' => (float) $lokasiTps->latitude,
                                    'longitude' => (float) $lokasiTps->longitude,
                                    'tipe' => $lokasiTps->tipe ?? 'TPS',
                                    'urutan' => $ruteTps->urutan // Menambahkan urutan ke data
                                ];
                            }
                            return null;
                        })
                        ->filter() // Hapus nilai null
                        ->toArray();

                    $tpsData = $tpsPoints;
                }

                $allTps[$jadwal->id] = $tpsData;

                // Ambil tracking terakhir
                $lastTracking = TrackingArmada::where('id_jadwal_operasional', $jadwal->id)
                    ->latest('timestamp')
                    ->first();

                $mapData[] = [
                    'id' => $jadwal->id,
                    'armada' => [
                        'no_polisi' => $jadwal->armada->no_polisi ?? 'N/A',
                        'jenis' => $jadwal->armada->jenis_kendaraan ?? $jadwal->armada->merk_kendaraan ?? 'N/A',
                        'kapasitas' => $jadwal->armada->kapasitas ?? 0
                    ],
                    'rute' => [
                        'nama' => $jadwal->rute->nama_rute ?? 'N/A',
                        'color' => $color
                    ],
                    'status' => $jadwal->status,
                    'status_text' => $this->getStatusText($jadwal->status),
                    'jam_aktif' => $jadwal->jam_aktif ? Carbon::parse($jadwal->jam_aktif)->format('H:i') : 'N/A',
                    'last_tracking' => $lastTracking ? [
                        'latitude' => (float) $lastTracking->latitude,
                        'longitude' => (float) $lastTracking->longitude,
                        'timestamp' => $lastTracking->timestamp
                    ] : null,
                    'tps_data' => $tpsData
                ];
            }

            // Ambil semua TPS untuk ditampilkan di peta (perbaikan query)
            $allTpsForMap = LokasiTps::select('id', 'nama_lokasi', 'latitude', 'longitude', 'tipe')
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->get()
                ->map(function ($tps) {
                    return [
                        'id' => $tps->id,
                        'nama_lokasi' => $tps->nama_lokasi,
                        'latitude' => (float) $tps->latitude,
                        'longitude' => (float) $tps->longitude,
                        'tipe' => $tps->tipe ?? 'TPS',
                    ];
                })->toArray();

            // Data untuk dropdown filter - menggunakan static method dari model
            $statusOptions = [
                '' => 'Semua Status'
            ] + JadwalOperasional::getStatusLabels();

            return view('admintpst.jadwal-rute.index', [
                'jadwalOperasional' => $jadwalOperasional,
                'tableData' => $tableData,
                'mapData' => $mapData,
                'allTps' => $allTps,
                'allTpsForMap' => $allTpsForMap,
                'statusOptions' => $statusOptions,
                'currentSearch' => $search,
                'currentStatus' => $statusFilter,
                'currentDate' => $dateFilter,
                'currentPerPage' => $perPage
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat menampilkan jadwal operasional:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memuat data jadwal operasional. Pesan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail jadwal operasional
     */
    public function show($id)
    {
        try {
            $jadwal = JadwalOperasional::with([
                'armada',
                'jadwal',
                'rute',
                'rute.ruteTps.lokasi_tps',
                'penugasanPetugas.petugas.user'
            ])->findOrFail($id);

            // Ambil tracking terbaru jika ada
            $lastTracking = TrackingArmada::where('id_jadwal_operasional', $id)
                ->latest('timestamp')
                ->first();

            // PERUBAHAN: Ambil data TPS berdasarkan urutan
            $tpsData = RuteTps::with('lokasi_tps')
                ->where('id_rute', $jadwal->rute->id)
                ->orderBy('urutan') // Menggunakan kolom urutan untuk pengurutan
                ->get();

            // Siapkan data detail
            $detailData = [
                'id' => $jadwal->id,
                'tanggal' => Carbon::parse($jadwal->tanggal)->format('d F Y'),
                'hari' => $this->formatHari($jadwal->jadwal->hari ?? 'N/A'),
                'jam_aktif' => $jadwal->jam_aktif ? Carbon::parse($jadwal->jam_aktif)->format('H:i') . ' WIB' : 'N/A',
                'status' => $jadwal->status,
                'status_text' => $this->getStatusText($jadwal->status),
                'status_class' => $this->getStatusClass($jadwal->status),
                'armada' => [
                    'no_polisi' => $jadwal->armada->no_polisi ?? 'N/A',
                    'jenis' => $jadwal->armada->jenis_kendaraan ?? $jadwal->armada->merk_kendaraan ?? 'N/A',
                    'kapasitas' => $jadwal->armada->kapasitas ?? 0
                ],
                'rute' => [
                    'nama_rute' => $jadwal->rute->nama_rute ?? 'N/A',
                    'jumlah_tps' => $tpsData->count() ?? 0
                ],
                'petugas' => $jadwal->penugasanPetugas->map(function ($penugasan) {
                    return [
                        'nama' => $penugasan->petugas->user->name ?? $penugasan->petugas->name ?? 'N/A',
                        'tugas' => $penugasan->tugas
                    ];
                }) ?? collect(),
                'last_tracking' => $lastTracking ? [
                    'latitude' => $lastTracking->latitude,
                    'longitude' => $lastTracking->longitude,
                    'timestamp' => Carbon::parse($lastTracking->timestamp)->format('d/m/Y H:i:s')
                ] : null,
                'created_at' => $jadwal->created_at->format('d F Y H:i:s'),
                'updated_at' => $jadwal->updated_at->format('d F Y H:i:s')
            ];

            return view('jadwal-rute.show', [
                'jadwal' => $jadwal,
                'detailData' => $detailData,
                'tpsData' => $tpsData // PERUBAHAN: Mengirim data TPS terurut ke view
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat menampilkan detail jadwal operasional: ' . $e->getMessage());
            return redirect()->route('jadwal-rute.index')
                ->with('error', 'Jadwal operasional tidak ditemukan.');
        }
    }

    /**
     * API untuk mendapatkan detail armada
     */
    public function getArmadaDetail($id)
    {
        try {
            $jadwal = JadwalOperasional::with([
                'armada',
                'jadwal',
                'rute',
                'rute.ruteTps.lokasi_tps',
                'penugasanPetugas.petugas.user'
            ])->findOrFail($id);

            $lastTracking = TrackingArmada::where('id_jadwal_operasional', $id)
                ->latest('timestamp')
                ->first();

            // PERUBAHAN: Ambil data TPS berdasarkan urutan
            $tpsPoints = RuteTps::with('lokasi_tps')
                ->where('id_rute', $jadwal->rute->id)
                ->orderBy('urutan') // Menggunakan kolom urutan untuk pengurutan
                ->get()
                ->map(function ($ruteTps) {
                    $lokasiTps = $ruteTps->lokasi_tps;
                    if ($lokasiTps) {
                        return [
                            'id' => $lokasiTps->id,
                            'nama_lokasi' => $lokasiTps->nama_lokasi,
                            'latitude' => (float) $lokasiTps->latitude,
                            'longitude' => (float) $lokasiTps->longitude,
                            'tipe' => $lokasiTps->tipe ?? 'TPS',
                            'urutan' => $ruteTps->urutan
                        ];
                    }
                    return null;
                })
                ->filter()
                ->values()
                ->toArray();

            $data = [
                'id' => $jadwal->id,
                'armada' => [
                    'no_polisi' => $jadwal->armada->no_polisi ?? 'N/A',
                    'jenis' => $jadwal->armada->jenis_kendaraan ?? $jadwal->armada->merk_kendaraan ?? 'N/A',
                    'kapasitas' => $jadwal->armada->kapasitas ?? 0
                ],
                'rute' => [
                    'nama' => $jadwal->rute->nama_rute ?? 'N/A',
                    'tps_points' => $tpsPoints // PERUBAHAN: Menyertakan data TPS terurut
                ],
                'jadwal' => [
                    'hari' => $this->formatHari($jadwal->jadwal->hari ?? 'N/A'),
                    'jam_aktif' => $jadwal->jam_aktif ? Carbon::parse($jadwal->jam_aktif)->format('H:i') . ' WIB' : 'N/A'
                ],
                'petugas' => $jadwal->penugasanPetugas->map(function ($penugasan) {
                    return [
                        'nama' => $penugasan->petugas->user->name ?? $penugasan->petugas->name ?? 'N/A',
                        'tugas' => $penugasan->tugas
                    ];
                }),
                'status' => $jadwal->status,
                'status_text' => $this->getStatusText($jadwal->status),
                'last_tracking' => $lastTracking ? [
                    'latitude' => $lastTracking->latitude,
                    'longitude' => $lastTracking->longitude,
                    'timestamp' => Carbon::parse($lastTracking->timestamp)->format('d/m/Y H:i:s')
                ] : null
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat mengambil detail armada: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail armada'
            ], 500);
        }
    }

    /**
     * API untuk mendapatkan tracking terbaru
     */
    public function getTracking($id)
    {
        try {
            $tracking = TrackingArmada::where('id_jadwal_operasional', $id)
                ->latest('timestamp')
                ->first();

            if ($tracking) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'latitude' => (float) $tracking->latitude,
                        'longitude' => (float) $tracking->longitude,
                        'timestamp' => $tracking->timestamp
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Tracking tidak ditemukan'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error saat mengambil tracking: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data tracking'
            ], 500);
        }
    }

    /**
     * Export data jadwal operasional
     */
    public function export(Request $request)
    {
        try {
            $search = $request->get('search');
            $statusFilter = $request->get('status');
            $dateFilter = $request->get('date');

            // Query untuk export (tanpa pagination)
            $query = JadwalOperasional::with([
                'armada',
                'jadwal',
                'rute'
            ]);

            // Terapkan filter yang sama seperti di index
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('armada', function ($subQ) use ($search) {
                        $subQ->where('no_polisi', 'like', '%' . $search . '%')
                            ->orWhere('merk_kendaraan', 'like', '%' . $search . '%')
                            ->orWhere('jenis_kendaraan', 'like', '%' . $search . '%');
                    })
                        ->orWhereHas('rute', function ($subQ) use ($search) {
                            $subQ->where('nama_rute', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('jadwal', function ($subQ) use ($search) {
                            $subQ->where('hari', 'like', '%' . $search . '%');
                        });
                });
            }

            if ($statusFilter !== null && $statusFilter !== '') {
                $query->where('status', $statusFilter);
            }

            if ($dateFilter) {
                $query->whereDate('tanggal', $dateFilter);
            }

            $jadwalOperasional = $query->orderBy('tanggal', 'desc')->get();

            // Siapkan data untuk export
            $exportData = [];
            foreach ($jadwalOperasional as $jadwal) {
                $exportData[] = [
                    'ID Jadwal' => $jadwal->id,
                    'Tanggal' => Carbon::parse($jadwal->tanggal)->format('d-m-Y'),
                    'Hari' => $this->formatHari($jadwal->jadwal->hari ?? 'N/A'),
                    'Armada' => $jadwal->armada->no_polisi ?? 'N/A',
                    'Rute' => $jadwal->rute->nama_rute ?? 'N/A',
                    'Jam Aktif' => $jadwal->jam_aktif ? Carbon::parse($jadwal->jam_aktif)->format('H:i') : 'N/A',
                    'Status' => $this->getStatusText($jadwal->status)
                ];
            }

            // Return data untuk diproses oleh export handler (Excel/PDF)
            return response()->json([
                'success' => true,
                'data' => $exportData,
                'filename' => 'jadwal_operasional_' . date('Y-m-d_H-i-s')
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat export jadwal operasional: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat export data.'
            ], 500);
        }
    }

    /**
     * Helper function untuk format hari
     */
    private function formatHari($hari)
    {
        return ucfirst(strtolower($hari));
    }

    /**
     * Helper function untuk mendapatkan text status - menggunakan model method
     */
    private function getStatusText($status)
    {
        $statusLabels = JadwalOperasional::getStatusLabels();
        return $statusLabels[$status] ?? 'Unknown';
    }

    /**
     * Helper function untuk mendapatkan CSS class status
     */
    private function getStatusClass($status)
    {
        switch ($status) {
            case 0:
                return 'badge-danger'; // Merah untuk belum berjalan
            case 1:
                return 'badge-warning'; // Kuning untuk sedang berjalan
            case 2:
                return 'badge-success'; // Hijau untuk selesai
            default:
                return 'badge-secondary';
        }
    }
}
