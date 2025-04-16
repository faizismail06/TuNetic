<?php

namespace App\Http\Controllers;

use App\Models\LokasiTps;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;
use Illuminate\Http\Request;
use Exception;

class LokasiTpsController extends Controller
{
    /**
     * Menampilkan semua lokasi TPS ke tampilan index.
     */
    public function index()
    {
        try {
            $lokasiTps = LokasiTps::with(['province', 'regency', 'district', 'village'])->get();
            return view('adminpusat.lokasi-tps.index', compact('lokasiTps'));
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Menampilkan form create lokasi TPS.
     */
    public function create()
    {
        try {
            $provinces = Province::all();
            $regencies = Regency::where('province_id', 33)->get(); // Jawa Tengah
            $districts = District::where('regency_id', 3374)->get(); // Kota Semarang
            $villages = Village::where('district_id', 3374090)->get(); // Tembalang

            return view('adminpusat.lokasi-tps.create', compact('provinces', 'regencies', 'districts', 'villages'));
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
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

            LokasiTps::create($validatedData);
            return redirect()->route('lokasi-tps.index')->with('success', 'Lokasi TPS berhasil ditambahkan.');
        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
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
                "desa" => $nearestTps->village_id,
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

    public function indexView()
    {
        // Ambil data TPS dari database (opsional jika ingin menampilkan data di peta)
        $lokasi = LokasiTps::all();

        // Menampilkan view dengan data TPS
        return view('adminpusat.lokasi-tps.index', compact('lokasi'));
    }

}
