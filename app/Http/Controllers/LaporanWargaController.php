<?php

namespace App\Http\Controllers;

use App\Models\LaporanWarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaporanWargaController extends Controller
{
    /**
     * Menampilkan semua laporan warga.
     */
    public function index()
    {
        $laporan = LaporanWarga::with('user')->get();
        return response()->json($laporan, 200);
    }

    /**
     * Menyimpan laporan warga baru.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_user' => 'required|exists:users,id',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'deskripsi' => 'required|string',
            'status' => 'integer|in:0,1,2', // 0: Pending, 1: Diproses, 2: Selesai
        ]);

        if ($request->hasFile('gambar')) {
            $validatedData['gambar'] = $request->file('gambar')->store('laporan_warga', 'public');
        }

        $laporan = LaporanWarga::create($validatedData);
        return response()->json($laporan, 201);
    }

    /**
     * Menampilkan laporan berdasarkan ID.
     */
    public function show($id)
    {
        $laporan = LaporanWarga::with('user')->findOrFail($id);
        return response()->json($laporan, 200);
    }

    /**
     * Memperbarui laporan warga.
     */
    public function update(Request $request, $id)
    {
        $laporan = LaporanWarga::findOrFail($id);

        $validatedData = $request->validate([
            'id_user' => 'sometimes|exists:users,id',
            'latitude' => 'sometimes|numeric|between:-90,90',
            'longitude' => 'sometimes|numeric|between:-180,180',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'deskripsi' => 'sometimes|string',
            'status' => 'integer|in:0,1,2',
        ]);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($laporan->gambar) {
                Storage::disk('public')->delete($laporan->gambar);
            }
            $validatedData['gambar'] = $request->file('gambar')->store('laporan_warga', 'public');
        }

        $laporan->update($validatedData);
        return response()->json($laporan, 200);
    }

    /**
     * Menghapus laporan warga (Soft Delete).
     */
    public function destroy($id)
    {
        $laporan = LaporanWarga::findOrFail($id);
        $laporan->delete();
        return response()->json(["message" => "Laporan berhasil dihapus"], 204);
    }
}
