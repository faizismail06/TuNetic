<?php

namespace App\Http\Controllers;

use App\Models\LokasiTps;
use Illuminate\Http\Request;
use Exception;

class LokasiTpsController extends Controller
{
    /**
     * Menampilkan semua lokasi TPS.
     */
    public function index()
    {
        try {
            $lokasi = LokasiTps::all();
            return response()->json($lokasi, 200);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    /**
     * Menyimpan lokasi TPS baru.
     */
    public function store(Request $request)
    {
        try {
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
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    /**
     * Menampilkan lokasi TPS berdasarkan ID.
     */
    public function show($id)
    {
        try {
            $lokasi = LokasiTps::findOrFail($id);
            return response()->json($lokasi, 200);
        } catch (Exception $e) {
            return response()->json(["error" => "Lokasi tidak ditemukan"], 404);
        }
    }

    public function findNearestTps(Request $request)
    {
        $validatedData = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $latitude = $validatedData['latitude'];
        $longitude = $validatedData['longitude'];

        $nearestTps = LokasiTps::selectRaw("
        id, nama_lokasi, province_id, regency_id, district_id, village_id, latitude, longitude,
        (6371 * acos(cos(radians(?)) * cos(radians(latitude)) 
        * cos(radians(longitude) - radians(?)) 
        + sin(radians(?)) * sin(radians(latitude)))) AS distance
    ", [$latitude, $longitude, $latitude])
            ->orderByRaw("distance ASC")
            ->first();

        if (!$nearestTps) {
            return response()->json(["message" => "Tidak ada TPS terdekat ditemukan"], 404);
        }

        return response()->json([
            "message" => "TPS terdekat ditemukan",
            "data" => [
                "tps" => $nearestTps,
                "desa" => $nearestTps->village_id, // Ambil ID Desa
            ]
        ], 200);
    }

    /**
     * Memperbarui lokasi TPS berdasarkan ID.
     */
    public function update(Request $request, $id)
    {
        try {
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
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    /**
     * Menghapus lokasi TPS berdasarkan ID.
     */
    public function destroy($id)
    {
        try {
            $lokasi = LokasiTps::findOrFail($id);
            $lokasi->delete();
            return response()->json(["message" => "Lokasi TPS berhasil dihapus"], 200);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }
}
