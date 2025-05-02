<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Models\JadwalOperasional;
use App\Models\Armada;
use App\Models\RuteTps;
use App\Models\Petugas;
use App\Models\PenugasanPetugas;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class JadwalController extends Controller
{
    /**
     * Menampilkan semua data jadwal.
     */
    public function index()
    {
        $jadwals = Jadwal::all();
        return view('adminpusat.daftar-jadwal.index', compact('jadwals'));
    }

    /**
     * Menyimpan jadwal baru.
     */

    public function create()
    {
        return view('adminpusat.daftar-jadwal.create',);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'hari' => 'required|string|max:20',
            'status' => 'required|integer|in:0,1',
        ]);

        Jadwal::create($validatedData);

        return redirect()->route('daftar-jadwal.index')->with('success', 'Jadwal berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail jadwal berdasarkan ID.
     */
    public function show($id)
    {
        try {
            $jadwal = Jadwal::findOrFail($id);
            return response()->json($jadwal, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Jadwal tidak ditemukan',
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Mengupdate data jadwal berdasarkan ID.
     */
    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);

        return view('adminpusat.daftar-jadwal.edit', compact('jadwal'));
    }

    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $validatedData = $request->validate([
            'hari' => 'required|string|max:20',
            'status' => 'required|integer|in:0,1',
        ]);

        $jadwal->update($validatedData);

        return redirect()->route('daftar-jadwal.index')->with('success', 'Jadwal berhasil diperbarui!');
    }

    /**
     * Menghapus jadwal berdasarkan ID.
     */

    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('daftar-jadwal.index')->with('success', 'Jadwal berhasil dihapus!');
    }

    public function generateForm()
    {
        return view('adminpusat.daftar-jadwal.generate');
    }

    /**
     * Helper fungsi: dapatkan nama hari berdasarkan dayOfWeek Carbon
     */
    private function getNamaHari($dayOfWeek)
    {
        $hari = [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];

        return $hari[$dayOfWeek] ?? 'Tidak diketahui';
    }

    public function generateStore(Request $request)
    {
        $validated = $request->validate([
            'bulan_mulai' => 'required|date_format:Y-m',
            'bulan_akhir' => 'required|date_format:Y-m|after_or_equal:bulan_mulai',
        ]);

        $template = [
            'Minggu' => [
                'armada_id' => 4,
                'rute_tps_id' => 4,
                'petugas' => [1, 2],
                // 'petugas' => [13, 14],
            ],
            'Senin' => [
                'armada_id' => 1,
                'rute_tps_id' => 1,
                'petugas' => [1, 2],
            ],
            'Selasa' => [
                'armada_id' => 1,
                'rute_tps_id' => 1,
                'petugas' => [1, 2],
                // 'petugas' => [3, 4],
            ],
            'Rabu' => [
                'armada_id' => 2,
                'rute_tps_id' => 2,
                'petugas' => [1, 2],
                // 'petugas' => [5, 6],
            ],
            'Kamis' => [
                'armada_id' => 2,
                'rute_tps_id' => 2,
                'petugas' => [1, 2],
                // 'petugas' => [7, 8],
            ],
            'Jumat' => [
                'armada_id' => 3,
                'rute_tps_id' => 3,
                'petugas' => [1, 2],
                // 'petugas' => [9, 10],
            ],
            'Sabtu' => [
                'armada_id' => 3,
                'rute_tps_id' => 3,
                'petugas' => [1, 2],
                // 'petugas' => [11, 12],
            ],
        ];


        DB::beginTransaction();

        try {
            $startDate = Carbon::parse($validated['bulan_mulai'] . '-01')->startOfMonth();
            $endDate = Carbon::parse($validated['bulan_akhir'] . '-01')->endOfMonth();

            // Ambil semua tanggal dari bulan mulai ke bulan akhir
            $period = CarbonPeriod::create($startDate, $endDate);

            foreach ($period as $date) {
                $hari = $this->getNamaHari($date->dayOfWeek);

                if (isset($template[$hari])) {
                    // Ambil data dari template
                    $dataTemplate = $template[$hari];

                    $jadwal = Jadwal::where('hari', $hari)->first();
                    if (!$jadwal) {
                        throw new \Exception("Jadwal untuk hari {$hari} tidak ditemukan.");
                    }

                    // Insert ke jadwal_operasional
                    $jadwalOperasional = JadwalOperasional::create([
                        'id_armada' => $dataTemplate['armada_id'],
                        'id_jadwal' => $jadwal->id, // pastikan sudah ada jadwal hari itu
                        'id_rute_tps' => $dataTemplate['rute_tps_id'],
                        'tanggal' => $date->toDateString(), // INI DITAMBAHKAN!
                        'jam_aktif' => '07:00:00', // misal default jam aktif
                        'status' => 1, // aktif
                    ]);

                    // Insert penugasan petugas
                    foreach ($dataTemplate['petugas'] as $petugasId) {
                        PenugasanPetugas::create([
                            'id_petugas' => $petugasId,
                            'id_jadwal_operasional' => $jadwalOperasional->id,
                            'tugas' => 2, // default tugas 1 (Driver) atau 2 (Pickup), bisa diatur nanti
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('daftar-jadwal.index')->with('success', 'Jadwal berhasil digenerate!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal generate jadwal: ' . $e->getMessage())->withInput();
        }
    }
}
