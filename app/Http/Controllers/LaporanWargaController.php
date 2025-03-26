<?php

namespace App\Http\Controllers;

use App\Models\LaporanWarga;
use App\Models\LokasiTps;
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
            $path = $request->file('gambar')->store('laporan_warga', 'public');
            $validatedData['gambar'] = asset('storage/' . $path);
        }

        $laporan = LaporanWarga::create($validatedData);

        // Cari TPS terdekat berdasarkan latitude & longitude laporan
        $nearestTps = $this->findNearestTps($request->latitude, $request->longitude);

        return response()->json([
            "message" => "Laporan warga berhasil disimpan",
            "data" => $laporan,
            "tps_terdekat" => $nearestTps
        ], 201);
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
            if ($laporan->gambar) {
                Storage::disk('public')->delete(str_replace(asset('storage/'), '', $laporan->gambar));
            }
            $path = $request->file('gambar')->store('laporan_warga', 'public');
            $validatedData['gambar'] = asset('storage/' . $path);
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

        if ($laporan->gambar) {
            Storage::disk('public')->delete(str_replace(asset('storage/'), '', $laporan->gambar));
        }

        $laporan->delete();
        return response()->json(["message" => "Laporan berhasil dihapus"], 204);
    }

    /**
     * Mencari TPS terdekat berdasarkan latitude & longitude.
     */
    private function findNearestTps($latitude, $longitude)
    {
        $nearestTps = LokasiTps::selectRaw("
            id, nama_lokasi, province_id, regency_id, district_id, village_id, latitude, longitude,
            (6371 * acos(cos(radians(?)) * cos(radians(latitude)) 
            * cos(radians(longitude) - radians(?)) 
            + sin(radians(?)) * sin(radians(latitude)))) AS distance
        ", [$latitude, $longitude, $latitude])
            ->orderByRaw("distance ASC")
            ->first();

        if (!$nearestTps) {
            return null;
        }

        return [
            "tps" => [
                "id" => $nearestTps->id,
                "nama_lokasi" => $nearestTps->nama_lokasi,
                "latitude" => $nearestTps->latitude,
                "longitude" => $nearestTps->longitude,
            ],
            "desa" => $nearestTps->village ? $nearestTps->village->name : null // Ambil nama desa
        ];
    }
}
