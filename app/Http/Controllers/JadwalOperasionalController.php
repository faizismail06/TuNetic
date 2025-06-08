<?php

namespace App\Http\Controllers;

use App\Models\JadwalOperasional;
use App\Models\Armada;
use App\Models\Jadwal;
use App\Models\Rute;
use App\Models\Petugas;
use App\Models\PenugasanPetugas;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;
use App\Http\Resources\JadwalOperasionalResource;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class JadwalOperasionalController extends Controller
{
    /**
     * Menampilkan semua jadwal operasional.
     */
    public function index()
    {
        $jadwals = JadwalOperasional::with([
            'armada',
            'jadwal',
            'rute',
            'penugasanPetugas.petugas',
        ])->get();
        $semuaPetugas = Petugas::all(); // untuk dropdown plotting
        return view('adminpusat/jadwal-operasional.index', compact('jadwals', 'semuaPetugas'));
    }

    // public function simpanPlotting(Request $request, $id)
    // {
    //     $jadwal = JadwalOperasional::findOrFail($id);
    //     $jadwal->penugasanPetugas()->delete(); // reset

    //     foreach ($request->petugas_ids as $i => $pid) {
    //         $jadwal->penugasanPetugas()->create([
    //             'petugas_id' => $pid,
    //             'tugas' => $request->tugas[$i]
    //         ]);
    //     }

    //     return response()->json(['message' => 'Berhasil']);
    // }

    public function simpanPlotting(Request $request, $id)
    {
        $jadwal = JadwalOperasional::findOrFail($id);
        $jadwal->penugasanPetugas()->delete(); // Hapus dulu penugasan lama

        foreach ($request->petugas_ids as $index => $petugas_id) {
            $jadwal->penugasanPetugas()->create([
                'id_petugas' => $petugas_id,
                'tugas' => $request->tugas[$index],
            ]);
        }

        return redirect()->route('jadwal-operasional.index')->with('success', 'Plotting berhasil diperbarui.');
    }

    public function plotting($id)
    {
        $jadwal = JadwalOperasional::with('penugasanPetugas.petugas')->findOrFail($id);
        $semuaPetugas = Petugas::all();

        return view('jadwal-operasional.plotting', compact('jadwal', 'semuaPetugas'));
    }


    /**
     * Menyimpan jadwal operasional baru.
     */


    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $jadwal = JadwalOperasional::create($request->validate([
                'id_armada' => 'required|exists:armada,id',
                'id_jadwal' => 'required|exists:jadwal,id',
                'id_rute' => 'required|exists:rute,id',
                'tanggal' => 'required|date',
                'jam_aktif' => [
                    'required',
                    'date_format:H:i',
                    function ($attribute, $value, $fail) {
                        if ($value < '05:00' || $value > '22:00') {
                            $fail("Jam aktif harus antara 05:00 dan 22:00");
                        }
                    }
                ],
                'status' => 'required|integer|in:0,1,2',
            ]));



            // Simpan penugasan petugas
            foreach ($request->input('petugas', []) as $p) {
                PenugasanPetugas::create([
                    'id_jadwal_operasional' => $jadwal->id,
                    'id_petugas' => $p['id_petugas'],
                    'tugas' => $p['tugas'],
                ]);
            }

            DB::commit();

            return redirect()->route('jadwal-operasional.index')->with('success', 'Jadwal dan penugasan berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage())->withInput();
        }
    }


    public function create()
    {
        return view('adminpusat/jadwal-operasional.create', [
            'armadas' => Armada::all(),
            'jadwals' => Jadwal::all(),
            'rutes' => Rute::all(),
            'petugas' => Petugas::all(), // ✅ ini penting
        ]);
    }

        /**
     * Memperbarui jadwal operasional.
     */
    public function edit($id)
    {
        $jadwal = JadwalOperasional::with([
            'jadwal',
            'armada',
            'rute',
            'penugasanPetugas.petugas',
        ])->findOrFail($id);

        return view('adminpusat/jadwal-operasional.edit', [
            'jadwalOperasional' => $jadwal,
            'armadas' => Armada::all(),
            'jadwals' => Jadwal::all(),
            'rutes' => Rute::all(),
            'petugas' => Petugas::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $jadwal = JadwalOperasional::findOrFail($id);

            // Validasi
            $validatedData = $request->validate([
                'id_armada' => 'required|exists:armada,id',
                'id_jadwal' => 'required|exists:jadwal,id',
                'id_rute' => 'required|exists:rute,id',
                'tanggal' => 'required|date',
                'jam_aktif' => [
                    'required',
                    'date_format:H:i',
                    function ($attribute, $value, $fail) {
                        if ($value < '05:00' || $value > '22:00') {
                            $fail("Jam aktif harus antara 05:00 dan 22:00");
                        }
                    }
                ],
                'status' => 'required|integer|in:0,1,2',
            ]);

            // Update jadwal operasional
            $jadwal->update($validatedData);

            // Hapus semua penugasan lama
            $jadwal->penugasanPetugas()->delete();

            // Tambahkan ulang penugasan yang baru dari form
            foreach ($request->input('petugas', []) as $p) {
                PenugasanPetugas::create([
                    'id_jadwal_operasional' => $jadwal->id,
                    'id_petugas' => $p['id_petugas'],
                    'tugas' => $p['tugas'],
                ]);
            }

            DB::commit();

            return redirect()->route('jadwal-operasional.index')->with('success', 'Jadwal operasional berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menghapus jadwal operasional.
     */
    public function destroy($id)
    {
        try {
            $jadwal = JadwalOperasional::findOrFail($id);

            $jadwal->delete();


            // return response()->json(["message" => "Jadwal operasional berhasil dihapus"], 200);

            return redirect()->route('jadwal-operasional.index')
                            ->with('success', 'Jadwal operasional berhasil disimpan!');

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan jadwal operasional berdasarkan ID.
     */
    public function show($id)
    {
        // $jadwal = JadwalOperasional::with(['armada', 'jadwal', 'ruteTps'])->findOrFail($id);
        // return response()->json($jadwal, 200);
        try {
            $jadwal = JadwalOperasional::with(['armada', 'jadwal', 'ruteTps'])->findOrFail($id);
            return response()->json($jadwal, 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Data tidak ditemukan!',
                'error' => $e->getMessage()
            ], 404);
        }
    }
}
