<?php

namespace App\Http\Controllers;

use App\Models\RuteTps;
use Illuminate\Http\Request;

class RuteTpsController extends Controller
{
    /**
     * Menampilkan semua data rute TPS.
     */
    public function index()
    {
        $ruteTps = RuteTps::with(['rute', 'lokasi_tps'])->get();
        return response()->json($ruteTps, 200);
    }

    /**
     * Menyimpan data rute TPS baru.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_rute' => 'required|exists:rute,id',
            'id_lokasi_tps' => 'required|exists:lokasi_tps,id',
        ]);

        $ruteTps = RuteTps::create($validatedData);

        return response()->json($ruteTps, 201);
    }

    /**
     * Menampilkan detail rute TPS berdasarkan ID.
     */
    public function show($id)
    {
        $ruteTps = RuteTps::with(['rute', 'lokasi_tps'])->findOrFail($id);
        return response()->json($ruteTps, 200);
    }

    /**
     * Mengupdate data rute TPS berdasarkan ID.
     */
    public function update(Request $request, $id)
    {
        $ruteTps = RuteTps::findOrFail($id);

        $validatedData = $request->validate([
            'id_rute' => 'sometimes|exists:rute,id',
            'id_lokasi_tps' => 'sometimes|exists:lokasi_tps,id',
        ]);

        $ruteTps->update($validatedData);

        return response()->json($ruteTps, 200);
    }

    /**
     * Menghapus data rute TPS berdasarkan ID.
     */
    public function destroy($id)
    {
        $ruteTps = RuteTps::findOrFail($id);
        $ruteTps->delete();

        return response()->json(["message" => "Rute TPS berhasil dihapus"], 204);
    }
}
