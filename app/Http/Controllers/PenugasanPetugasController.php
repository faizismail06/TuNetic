<?php

namespace App\Http\Controllers;

use App\Models\PenugasanPetugas;
use Illuminate\Http\Request;

class PenugasanPetugasController extends Controller
{
    /**
     * Menampilkan semua data penugasan armada
     */
    public function index()
    {
        $penugasan = PenugasanPetugas::with(['petugas', 'armada'])->get();
        return response()->json($penugasan);
    }

    /**
     * Menyimpan data penugasan armada baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_petugas' => 'required|exists:petugas,id',
            'id_armada' => 'required|exists:armada,id',
        ]);

        $penugasan = PenugasanPetugas::create($request->all());

        return response()->json([
            'message' => 'Penugasan armada berhasil ditambahkan!',
            'data' => $penugasan
        ], 201);
    }

    /**
     * Menampilkan detail penugasan armada
     */
    public function show($id)
    {
        $penugasan = PenugasanPetugas::with(['petugas', 'armada'])->findOrFail($id);
        return response()->json($penugasan);
    }

    /**
     * Memperbarui data penugasan armada
     */
    public function update(Request $request, $id)
    {
        $penugasan = PenugasanPetugas::findOrFail($id);

        $request->validate([
            'id_petugas' => 'exists:petugas,id',
            'id_armada' => 'exists:armada,id',
        ]);

        $penugasan->update($request->all());

        return response()->json([
            'message' => 'Penugasan armada berhasil diperbarui!',
            'data' => $penugasan
        ]);
    }

    /**
     * Menghapus data penugasan armada
     */
    public function destroy($id)
    {
        $penugasan = PenugasanPetugas::findOrFail($id);
        $penugasan->delete();

        return response()->json([
            'message' => 'Penugasan armada berhasil dihapus!'
        ]);
    }
}
