<?php

namespace App\Http\Controllers;

use App\Models\JadwalOperasional;
use Illuminate\Http\Request;

class JadwalOperasionalController extends Controller
{
    /**
     * Menampilkan semua jadwal operasional
     */
    public function index()
    {
        $jadwal = JadwalOperasional::with(['penugasan', 'jadwal', 'rute'])->get();
        return response()->json($jadwal);
    }

    /**
     * Menyimpan jadwal operasional baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_penugasan' => 'required|exists:penugasan_armada,id',
            'id_jadwal' => 'required|exists:jadwal,id',
            'id_rute' => 'required|exists:rute,id',
            'tanggal' => 'required|date',
            'jam_aktif' => 'required|date_format:H:i',
            'status' => 'required|in:Belum Berjalan,Sedang Berjalan,Selesai',
        ]);

        $jadwal = JadwalOperasional::create($request->all());

        return response()->json([
            'message' => 'Jadwal operasional berhasil dibuat!',
            'data' => $jadwal
        ], 201);
    }

    /**
     * Menampilkan detail jadwal operasional
     */
    public function show($id)
    {
        $jadwal = JadwalOperasional::with(['penugasan', 'jadwal', 'rute'])->findOrFail($id);
        return response()->json($jadwal);
    }

    /**
     * Memperbarui data jadwal operasional
     */
    public function update(Request $request, $id)
    {
        $jadwal = JadwalOperasional::findOrFail($id);

        $request->validate([
            'id_penugasan' => 'exists:penugasan_armada,id',
            'id_jadwal' => 'exists:jadwal,id',
            'id_rute' => 'exists:rute,id',
            'tanggal' => 'date',
            'jam_aktif' => 'date_format:H:i',
            'status' => 'in:Belum Berjalan,Sedang Berjalan,Selesai',
        ]);

        $jadwal->update($request->all());

        return response()->json([
            'message' => 'Jadwal operasional berhasil diperbarui!',
            'data' => $jadwal
        ]);
    }

    /**
     * Menghapus data jadwal operasional
     */
    public function destroy($id)
    {
        $jadwal = JadwalOperasional::findOrFail($id);
        $jadwal->delete();

        return response()->json([
            'message' => 'Jadwal operasional berhasil dihapus!'
        ]);
    }
}
