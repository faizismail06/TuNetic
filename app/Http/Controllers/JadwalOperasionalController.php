<?php

namespace App\Http\Controllers;

use App\Models\JadwalOperasional;
use Illuminate\Http\Request;

class JadwalOperasionalController extends Controller
{
    /**
     * Menampilkan semua jadwal operasional.
     */
    public function index()
    {
        $jadwal = JadwalOperasional::with(['armada', 'jadwal', 'ruteTps'])->get();
        return response()->json($jadwal, 200);
    }

    /**
     * Menyimpan jadwal operasional baru.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_armada' => 'required|exists:armada,id',
            'id_jadwal' => 'required|exists:jadwal,id',
            'id_rute_tps' => 'required|exists:rute_tps,id',
            'jam_aktif' => 'required|date_format:H:i:s',
            'status' => 'required|integer|in:0,1,2',
        ]);

        $jadwal = JadwalOperasional::create($validatedData);
        return response()->json($jadwal, 201);
    }

    /**
     * Menampilkan jadwal operasional berdasarkan ID.
     */
    public function show($id)
    {
        $jadwal = JadwalOperasional::with(['armada', 'jadwal', 'ruteTps'])->findOrFail($id);
        return response()->json($jadwal, 200);
    }

    /**
     * Memperbarui jadwal operasional.
     */
    public function update(Request $request, $id)
    {
        $jadwal = JadwalOperasional::findOrFail($id);

        $validatedData = $request->validate([
            'id_armada' => 'sometimes|exists:armada,id',
            'id_jadwal' => 'sometimes|exists:jadwal,id',
            'id_rute_tps' => 'sometimes|exists:rute_tps,id',
            'jam_aktif' => 'sometimes|date_format:H:i:s',
            'status' => 'sometimes|integer|in:0,1,2',
        ]);

        $jadwal->update($validatedData);
        return response()->json($jadwal, 200);
    }

    /**
     * Menghapus jadwal operasional.
     */
    public function destroy($id)
    {
        $jadwal = JadwalOperasional::findOrFail($id);
        $jadwal->delete();
        return response()->json(["message" => "Jadwal operasional berhasil dihapus"], 204);
    }
}
