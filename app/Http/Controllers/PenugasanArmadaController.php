<?php

namespace App\Http\Controllers;

use App\Models\PenugasanArmada;
use Illuminate\Http\Request;

class PenugasanArmadaController extends Controller
{
    /**
     * Menampilkan semua data penugasan armada
     */
    public function index()
    {
        $penugasan = PenugasanArmada::with(['driver', 'armada'])->get();
        return response()->json($penugasan);
    }

    /**
     * Menyimpan data penugasan armada baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_driver' => 'required|exists:driver,id',
            'id_armada' => 'required|exists:armada,id',
        ]);

        $penugasan = PenugasanArmada::create($request->all());

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
        $penugasan = PenugasanArmada::with(['driver', 'armada'])->findOrFail($id);
        return response()->json($penugasan);
    }

    /**
     * Memperbarui data penugasan armada
     */
    public function update(Request $request, $id)
    {
        $penugasan = PenugasanArmada::findOrFail($id);

        $request->validate([
            'id_driver' => 'exists:driver,id',
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
        $penugasan = PenugasanArmada::findOrFail($id);
        $penugasan->delete();

        return response()->json([
            'message' => 'Penugasan armada berhasil dihapus!'
        ]);
    }
}
