<?php

namespace App\Http\Controllers;

use App\Models\JadwalOperasional;
use App\Models\TrackingArmada;
use App\Models\LokasiTps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
    public function showAutoTrackingPage()
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

            // Rest of the code remains the same
            $jadwalOperasional = JadwalOperasional::with([
                'armada',
                'jadwal',
                'ruteTps.rute',
                'ruteTps.lokasi_tps' // Memuat lokasi TPS
            ])
                ->whereIn('status', [0, 1]) // Status 0=Belum Berjalan, 1=Sedang Berjalan
                ->whereHas('penugasanPetugas', function ($query) use ($petugasId) {
                    $query->where('id_petugas', $petugasId);
                })
                ->get();

            if ($jadwalOperasional->isEmpty()) {
                Log::warning('Tidak ada jadwal operasional yang ditemukan untuk petugas ID: ' . $petugasId);
                return redirect()->route('home')
                    ->with('info', 'Tidak ada jadwal operasional yang tersedia untuk Anda.');
            }

            // Ambil semua TPS yang terkait dengan jadwal operasional
            $allTps = [];

            foreach ($jadwalOperasional as $jadwal) {
                // Dapatkan semua TPS dalam rute untuk jadwal ini
                $ruteId = $jadwal->ruteTps->rute->id;

                $tpsPoints = \App\Models\RuteTps::with('lokasi_tps')
                    ->where('id_rute', $ruteId)
                    ->orderBy('id') // Asumsikan urutan sesuai dengan ID
                    ->get()
                    ->map(function ($ruteTps) use ($jadwal) {
                        $lokasiTps = $ruteTps->lokasi_tps;
                        return [
                            'id' => $lokasiTps->id,
                            'nama' => $lokasiTps->nama_tps,
                            'latitude' => $lokasiTps->latitude,
                            'longitude' => $lokasiTps->longitude,
                            'jadwal_id' => $jadwal->id
                        ];
                    })
                    ->toArray();

                $allTps[$jadwal->id] = $tpsPoints;
            }

            return view('petugas.jadwal-pengambilan.index', compact('jadwalOperasional', 'allTps'));
        } catch (\Exception $e) {
            Log::error('Error saat menampilkan jadwal operasional: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat memproses request.'], 500);
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

            // Simpan data tracking
            $tracking = TrackingArmada::create([
                'id_jadwal_operasional' => $request->id_jadwal_operasional,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'timestamp' => now(),
            ]);

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
}
