<?php

namespace App\Http\Controllers;

use App\Models\Rute;
use App\Models\LokasiTps;
use Illuminate\Http\Request;

class RuteController extends Controller
{
    /**
     * Menampilkan semua rute
     */
    public function index()
    {
        $rute = Rute::with('lokasi')->get();
        return response()->json($rute);
    }

    /**
     * Menyimpan rute baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_lokasi' => 'required|exists:lokasi_tps,id',
            'nama_rute' => 'required|string|max:255',
            'map' => 'required|json',
            'wilayah' => 'required|string|max:255',
        ]);

        $rute = Rute::create($request->all());

        return response()->json([
            'message' => 'Rute berhasil ditambahkan!',
            'data' => $rute
        ], 201);
    }

    /**
     * Menampilkan detail rute berdasarkan ID
     */
    public function show($id)
    {
        $rute = Rute::with('lokasi')->findOrFail($id);
        return response()->json($rute);
    }

    /**
     * Memperbarui data rute
     */
    public function update(Request $request, $id)
    {
        $rute = Rute::findOrFail($id);

        $request->validate([
            'id_lokasi' => 'sometimes|exists:lokasi_tps,id',
            'nama_rute' => 'sometimes|string|max:255',
            'map' => 'sometimes|json',
            'wilayah' => 'sometimes|string|max:255',
        ]);

        $rute->update($request->all());

        return response()->json([
            'message' => 'Rute berhasil diperbarui!',
            'data' => $rute
        ]);
    }

    /**
     * Menghapus rute
     */
    public function destroy($id)
    {
        $rute = Rute::findOrFail($id);
        $rute->delete();

        return response()->json([
            'message' => 'Rute berhasil dihapus!'
        ]);
    }
}
