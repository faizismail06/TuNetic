<?php

namespace App\Http\Controllers;

use App\Models\TrackingArmada;
use App\Models\JadwalOperasional;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrackingArmadaController extends Controller
{
    /**
     * Tampilkan halaman tracking armada.
     */
    public function index()
    {
        $jadwalList = Jadwal::all();
        return view('user.rute-armada.index',   compact('jadwalList'));
    }

    /**
     * Tampilkan halaman detail tracking untuk jadwal operasional tertentu.
     */
    public function detail($id)
    {
        $jadwalOperasional = JadwalOperasional::with(['armada', 'jadwal', 'ruteTps.rute', 'penugasanPetugas.petugas'])
            ->findOrFail($id);

        // Ambil data tracking untuk jadwal operasional ini
        $trackingData = TrackingArmada::where('id_jadwal_operasional', $id)
            ->orderBy('timestamp', 'desc')
            ->get();

        return view('user.rute-armada.detail', compact('jadwalOperasional', 'trackingData'));
    }

    /**
     * Simpan data tracking armada baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_jadwal_operasional' => 'required|exists:jadwal_operasional,id',
            'timestamp' => 'required|date',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        TrackingArmada::create($request->all());

        return redirect()->route('tracking-armada.index')->with('success', 'Tracking Armada berhasil ditambahkan.');
    }

    /**
     * Hapus data tracking armada.
     */
    public function destroy(TrackingArmada $trackingArmada)
    {
        $trackingArmada->delete();

        return redirect()->route('tracking-armada.index')->with('success', 'Tracking Armada berhasil dihapus.');
    }

    /**
     * Hapus semua data tracking untuk jadwal operasional tertentu.
     */
    public function destroyAll($idJadwalOperasional)
    {
        DB::beginTransaction();

        try {
            // Memastikan jadwal operasional ada
            $jadwal = JadwalOperasional::findOrFail($idJadwalOperasional);

            // Hapus semua data tracking
            TrackingArmada::where('id_jadwal_operasional', $idJadwalOperasional)->delete();

            DB::commit();

            return redirect()->route('tracking-armada.index')
                ->with('success', 'Semua data tracking untuk jadwal operasional ini berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('tracking-armada.index')
                ->with('error', 'Gagal menghapus data tracking: ' . $e->getMessage());
        }
    }
}
