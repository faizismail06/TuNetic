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
        $lokasi = LokasiTps::all();
        return response()->json($lokasi, 200);
    }

    /**
     * Menyimpan lokasi TPS baru.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'province_id' => 'required|exists:reg_provinces,id',
            'regency_id' => 'required|exists:reg_regencies,id',
            'district_id' => 'required|exists:reg_districts,id',
            'village_id' => 'required|exists:reg_villages,id',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $lokasi = LokasiTps::create($validatedData);

        return response()->json($lokasi, 201);
    }

    /**
     * Menampilkan lokasi TPS berdasarkan ID.
     */
    public function show($id)
    {
        $lokasi = LokasiTps::findOrFail($id);
        return response()->json($lokasi, 200);
    }

    /**
     * Memperbarui lokasi TPS berdasarkan ID.
     */
    public function update(Request $request, $id)
    {
        $lokasi = LokasiTps::findOrFail($id);

        $validatedData = $request->validate([
            'nama_lokasi' => 'sometimes|string|max:255',
            'province_id' => 'sometimes|exists:reg_provinces,id',
            'regency_id' => 'sometimes|exists:reg_regencies,id',
            'district_id' => 'sometimes|exists:reg_districts,id',
            'village_id' => 'sometimes|exists:reg_villages,id',
            'latitude' => 'sometimes|numeric|between:-90,90',
            'longitude' => 'sometimes|numeric|between:-180,180',
        ]);

        $lokasi->update($validatedData);

        return response()->json($lokasi, 200);
    }

    /**
     * Menghapus lokasi TPS berdasarkan ID.
     */
    public function destroy($id)
    {
        $lokasi = LokasiTps::findOrFail($id);
        $lokasi->delete();
        return response()->json(["message" => "Lokasi TPS berhasil dihapus"], 204);
    }
}
