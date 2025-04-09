<?php

namespace App\Http\Controllers;

use App\Models\LaporanTps;
use Illuminate\Http\Request;

class LaporanTpsController extends Controller
{
    /**
     * Menampilkan semua laporan TPS.
     */
    public function index()
    {
        $laporan = LaporanTps::with('petugas')->get();
        return response()->json($laporan, 200);
    }

    /**
     * Menyimpan laporan TPS baru.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_petugas' => 'required|exists:petugas,id',
            'total_sampah' => 'required|numeric|min:0',
            'deskripsi' => 'required|string',
            'tanggal_pengangkutan' => 'nullable|date',
            'status' => 'required|integer|in:0,1,2',
        ]);

        $laporan = LaporanTps::create($validatedData);
        return response()->json($laporan, 201);
    }

    /**
     * Menampilkan laporan TPS berdasarkan ID.
     */
    public function show($id)
    {
        $laporan = LaporanTps::with('petugas')->findOrFail($id);
        return response()->json($laporan, 200);
    }

    /**
     * Memperbarui laporan TPS.
     */
    public function update(Request $request, $id)
    {
        $laporan = LaporanTps::findOrFail($id);

        $validatedData = $request->validate([
            'id_petugas' => 'sometimes|exists:petugas,id',
            'total_sampah' => 'sometimes|numeric|min:0',
            'deskripsi' => 'sometimes|string',
            'tanggal_pengangkutan' => 'sometimes|date',
            'status' => 'sometimes|integer|in:0,1,2',
        ]);

        $laporan->update($validatedData);
        return response()->json($laporan, 200);
    }

    /**
     * Menghapus laporan TPS.
     */
    public function destroy($id)
    {
        $laporan = LaporanTps::findOrFail($id);
        $laporan->delete();
        return response()->json(["message" => "Laporan TPS berhasil dihapus"], 204);
    }
}
