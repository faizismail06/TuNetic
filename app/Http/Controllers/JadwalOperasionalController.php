<?php

namespace App\Http\Controllers;

use App\Models\JadwalOperasional;
use App\Models\Armada;
use App\Models\Jadwal;
use App\Models\RuteTps;
use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;
use App\Http\Resources\JadwalOperasionalResource;
use Illuminate\Support\Facades\DB;

class JadwalOperasionalController extends Controller
{
    /**
     * Menampilkan semua jadwal operasional.
     */
    public function index()
    {
        // $jadwal = JadwalOperasional::with(['armada', 'jadwal', 'ruteTps'])->get();
        // return response()->json($jadwal, 200);
        // $jadwal = JadwalOperasional::with(['armada', 'jadwal', 'ruteTps'])->get();
        // return JadwalOperasionalResource::collection($jadwal);
        // $jadwals = JadwalOperasional::with(['armada', 'jadwal', 'ruteTps'])->get();

        $jadwals = JadwalOperasional::with([
            'armada',
            'jadwal',
            'ruteTps.rute',
            'penugasanPetugas.petugas'
        ])->get();
        return view('jadwal-operasional.index', compact('jadwals'));
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
                'id_rute_tps' => 'required|exists:rute_tps,id',
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
                \App\Models\PenugasanPetugas::create([
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
        return view('jadwal-operasional.create', [
            'armadas' => Armada::all(),
            'jadwals' => Jadwal::all(),
            'ruteTps' => RuteTps::all(),
            'petugas' => Petugas::all(), // ✅ ini penting
        ]);
    }

    // public function store(Request $request)
    // {
    //     try {
    //         $validatedData = $request->validate([
    //             'id_armada' => 'required|exists:armada,id',
    //             'id_jadwal' => 'required|exists:jadwal,id',
    //             'id_rute_tps' => 'required|exists:rute_tps,id',
    //             'jam_aktif' => [
    //                 'required',
    //                 'date_format:H:i',
    //                 function ($attribute, $value, $fail) {
    //                     if ($value < '05:00' || $value > '22:00') {
    //                         $fail("Jam aktif harus antara 05:00 dan 22:00");
    //                     }
    //                 }
    //             ],
    //             'status' => 'required|boolean',
    //         ]);

    //         $jadwal = JadwalOperasional::create($validatedData);

    //         return redirect()->route('jadwal-operasional.index')
    //                         ->with('success', 'Jadwal operasional berhasil disimpan!');
    //     } catch (ValidationException $e) {
    //         return back()->withErrors($e->errors())->withInput();
    //     } catch (Exception $e) {
    //         return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
    //     }
    // }


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

    /**
     * Memperbarui jadwal operasional.
     */
    public function edit($id)
    {
        $jadwal = JadwalOperasional::with([
            'jadwal',
            'armada',
            'ruteTps.rute',
            'penugasanPetugas.petugas'
        ])->findOrFail($id);

        return view('jadwal-operasional.edit', [
            'jadwalOperasional' => $jadwal,
            'armadas' => Armada::all(),
            'jadwals' => Jadwal::all(),
            'ruteTps' => RuteTps::with('rute')->get(),
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
                'id_rute_tps' => 'required|exists:rute_tps,id',
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
                \App\Models\PenugasanPetugas::create([
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

    // public function update(Request $request, $id)
    // {
    //     $jadwal = JadwalOperasional::findOrFail($id);

    //     $validatedData = $request->validate([
    //         'id_armada' => 'required|exists:armada,id',
    //         'id_jadwal' => 'required|exists:jadwal,id',
    //         'id_rute_tps' => 'required|exists:rute_tps,id',
    //         'jam_aktif' => [
    //             'required',
    //             'date_format:H:i',
    //             function ($attribute, $value, $fail) {
    //                 if ($value < '05:00' || $value > '22:00') {
    //                     $fail("Jam aktif harus antara 05:00 dan 22:00");
    //                 }
    //             }
    //         ],
    //         'status' => 'required|integer'
    //     ]);

    //     $jadwal->update($validatedData);

    //     return redirect()->route('jadwal-operasional.index')->with('success', 'Jadwal berhasil diperbarui!');
    // }


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
}
