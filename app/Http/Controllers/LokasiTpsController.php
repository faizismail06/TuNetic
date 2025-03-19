<?php

namespace App\Http\Controllers;

use App\Models\LokasiTps;
use Illuminate\Http\Request;

class LokasiTpsController extends Controller
{
    /**
     * Menampilkan semua lokasi TPS.
     */
    public function index()
    {
        return response()->json(LokasiTps::all());
    }

    /**
     * Menyimpan lokasi TPS baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $lokasi = LokasiTps::create($request->all());

        return response()->json($lokasi, 201);
    }

    /**
     * Menampilkan detail lokasi TPS berdasarkan ID.
     */
    public function show($id)
    {
        $lokasi = LokasiTps::find($id);

        if (!$lokasi) {
            return response()->json(['message' => 'Lokasi tidak ditemukan'], 404);
        }

        return response()->json($lokasi);
    }

    /**
     * Memperbarui lokasi TPS.
     */
    public function update(Request $request, $id)
    {
        $lokasi = LokasiTps::find($id);

        if (!$lokasi) {
            return response()->json(['message' => 'Lokasi tidak ditemukan'], 404);
        }

        $request->validate([
            'nama_lokasi' => 'string|max:255',
            'latitude' => 'numeric|between:-90,90',
            'longitude' => 'numeric|between:-180,180',
        ]);

        $lokasi->update($request->all());

        return response()->json($lokasi);
    }

    /**
     * Menghapus lokasi TPS.
     */
    public function destroy($id)
    {
        $lokasi = LokasiTps::find($id);

        if (!$lokasi) {
            return response()->json(['message' => 'Lokasi tidak ditemukan'], 404);
        }

        $lokasi->delete();

        return response()->json(['message' => 'Lokasi berhasil dihapus']);
    }
}
