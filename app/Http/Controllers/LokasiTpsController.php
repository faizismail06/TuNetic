<?php

namespace App\Http\Controllers;

use App\Models\LokasiTps;
use Illuminate\Http\Request;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;

class LokasiTpsController extends Controller
{
    /**
     * Menampilkan semua lokasi TPS.
     */
    // public function index()
    // {
    //     $lokasi = LokasiTps::all();
    //     return response()->json($lokasi, 200);
    // }
    public function index()
{
    $lokasi = LokasiTps::with(['province', 'regency', 'district', 'village'])->get();
    return view('lokasi_tps.index', compact('lokasi'));
}


    public function create()
{
    $provinces = Province::all();
    $regencies = Regency::all();
    $districts = District::all();
    $villages = Village::all();

    return view('lokasi_tps.create', compact('provinces', 'regencies', 'districts', 'villages'));
}

public function edit($id)
{
    $lokasi = LokasiTps::findOrFail($id);

    $provinces = Province::all();

    // Hanya ambil regency yang sesuai dengan provinsi yang dipilih
    $regencies = Regency::where('province_id', $lokasi->province_id)->get();

    // Ambil district yang sesuai dengan regency yang dipilih
    $districts = District::where('regency_id', $lokasi->regency_id)->get();

    // Ambil village yang sesuai dengan district yang dipilih
    $villages = Village::where('district_id', $lokasi->district_id)->get();

    return view('lokasi_tps.edit', compact('lokasi','provinces','regencies', 'districts', 'villages'));
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
    $lokasi = LokasiTps::with(['province', 'regency', 'district', 'village'])->findOrFail($id);

    return response()->json([
        'id' => $lokasi->id,
        'nama_lokasi' => $lokasi->nama_lokasi,
        'provinsi' => $lokasi->province->name ?? null,
        'kabupaten' => $lokasi->regency->name ?? null,
        'kecamatan' => $lokasi->district->name ?? null,
        'desa' => $lokasi->village->name ?? null,
        'latitude' => $lokasi->latitude,
        'longitude' => $lokasi->longitude,
    ], 200);
    
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
