<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Models\JadwalOperasional;
use App\Models\Armada;
use App\Models\Rute;
use App\Models\Petugas;
use App\Models\JadwalTemplate;
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
        $jadwal = Jadwal::all();
        return view('adminpusat.daftar-jadwal.index', compact('jadwal'));
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

    public function generateStore(Request $request)
    {
        $validated = $request->validate([
            'bulan_mulai' => 'required|date_format:Y-m',
            'bulan_akhir' => 'required|date_format:Y-m|after_or_equal:bulan_mulai',
        ]);

        DB::beginTransaction();

        try {
            $startDate = Carbon::parse($validated['bulan_mulai'] . '-01')->startOfMonth();
            $endDate = Carbon::parse($validated['bulan_akhir'] . '-01')->endOfMonth();
            $period = CarbonPeriod::create($startDate, $endDate);

            foreach ($period as $tanggal) {
                $namaHari = $this->getNamaHari($tanggal->dayOfWeek);

                // Ambil semua template untuk hari itu
                $templates = JadwalTemplate::with('petugasTemplate')->where('hari', $namaHari)->get();

                foreach ($templates as $template) {
                    $jadwalOperasional = JadwalOperasional::create([
                        'id_jadwal' => Jadwal::where('hari', $namaHari)->first()->id,
                        'id_armada' => $template->id_armada,
                        'id_rute' => $template->id_rute, // rute_tps bisa diganti ke rute utama
                        'tanggal' => $tanggal->format('Y-m-d'),
                        'jam_aktif' => '07:00:00',
                        'status' => 1,
                    ]);

                    foreach ($template->petugasTemplate as $petugas) {
                        PenugasanPetugas::create([
                            'id_jadwal_operasional' => $jadwalOperasional->id,
                            'id_petugas' => $petugas->id_petugas,
                            'tugas' => $petugas->tugas,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('jadwal-operasional.index')->with('success', 'Jadwal berhasil digenerate!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal generate jadwal: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Helper fungsi: dapatkan nama hari berdasarkan dayOfWeek Carbon
     */
    private function getNamaHari($dayOfWeek)
    {
        return [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ][$dayOfWeek] ?? 'Tidak diketahui';
    }
}
