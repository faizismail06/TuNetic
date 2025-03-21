<?php

namespace App\Http\Controllers;

use App\Models\PenugasanPetugas;
use Illuminate\Http\Request;

class PenugasanPetugasController extends Controller
{
    public function index()
    {
        return response()->json(PenugasanPetugas::all(), 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_petugas' => 'required|exists:petugas,id',
            'id_jadwal_operasional' => 'required|exists:jadwal_operasional,id',
            'tugas' => 'required|in:1,2', // Hanya menerima 1 (driver) atau 2 (picker)
        ]);

        $penugasan = PenugasanPetugas::create($validatedData);

        return response()->json($penugasan, 201);
    }

    public function show($id)
    {
        $penugasan = PenugasanPetugas::findOrFail($id);
        return response()->json($penugasan, 200);
    }

    public function update(Request $request, $id)
    {
        $penugasan = PenugasanPetugas::findOrFail($id);

        $validatedData = $request->validate([
            'id_petugas' => 'sometimes|exists:petugas,id',
            'id_jadwal_operasional' => 'sometimes|exists:jadwal_operasional,id',
            'tugas' => 'sometimes|in:1,2', // Hanya menerima 1 (driver) atau 2 (picker)
        ]);

        $penugasan->update($validatedData);

        return response()->json($penugasan, 200);
    }

    public function destroy($id)
    {
        $penugasan = PenugasanPetugas::findOrFail($id);
        $penugasan->delete();
        return response()->json(["message" => "Penugasan berhasil dihapus"], 204);
    }
}
