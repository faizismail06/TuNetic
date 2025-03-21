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
        $laporan = LaporanWarga::with(['user', 'village'])->get();
        return response()->json($laporan);
    }

    /**
     * Menyimpan laporan warga baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id',
            'id_villages' => 'required|exists:reg_villages,id',
            'gambar' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'deskripsi' => 'required|string',
            'status' => 'required|integer|in:0,1,2',
        ]);

        // Proses upload gambar jika ada
        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('laporan_warga', 'public');
        }

        $laporan = LaporanWarga::create([
            'id_user' => $request->id_user,
            'id_villages' => $request->id_villages,
            'gambar' => $gambarPath,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
        ]);

        return response()->json(['message' => 'Laporan berhasil ditambahkan', 'data' => $laporan], 201);
    }

    /**
     * Menampilkan detail laporan warga berdasarkan ID.
     */
    public function show($id)
    {
        $laporan = LaporanWarga::with(['user', 'village'])->findOrFail($id);
        return response()->json($laporan);
    }

    /**
     * Memperbarui laporan warga berdasarkan ID.
     */
    public function update(Request $request, $id)
    {
        $laporan = LaporanWarga::findOrFail($id);

        $request->validate([
            'id_user' => 'required|exists:users,id',
            'id_villages' => 'required|exists:reg_villages,id',
            'gambar' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'deskripsi' => 'required|string',
            'status' => 'required|integer|in:0,1,2',
        ]);

        // Proses upload gambar jika ada
        if ($request->hasFile('gambar')) {
            if ($laporan->gambar) {
                Storage::disk('public')->delete($laporan->gambar);
            }
            $laporan->gambar = $request->file('gambar')->store('laporan_warga', 'public');
        }

        $laporan->update([
            'id_user' => $request->id_user,
            'id_villages' => $request->id_villages,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
        ]);

        return response()->json(['message' => 'Laporan berhasil diperbarui', 'data' => $laporan]);
    }

    /**
     * Menghapus laporan warga berdasarkan ID.
     */
    public function destroy($id)
    {
        $laporan = LaporanWarga::findOrFail($id);

        // Hapus gambar jika ada
        if ($laporan->gambar) {
            Storage::disk('public')->delete($laporan->gambar);
        }

        $laporan->delete();

        return response()->json(['message' => 'Laporan berhasil dihapus']);
    }
}
