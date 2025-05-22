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

    public function ruteArmada()
    {
        try {
            $ruteArmada = LokasiTps::with(['province', 'regency', 'district', 'village'])->get();
            return view('user.rute-armada.index', compact('ruteArmada'));
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

            // Menambahkan array tipes untuk dropdown pilihan kategori
            $tipes = [
                'TPS' => 'TPS (Tempat Pembuangan Sampah)',
                'TPST' => 'TPST (Tempat Pembuangan Sampah Terpadu)',
                'TPA' => 'TPA (Tempat Pembuangan Akhir)'
            ];

            return view('adminpusat.lokasi-tps.create', compact('provinces', 'regencies', 'districts', 'villages', 'tipes'));
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
                'tipe' => 'required|string|in:TPS,TPST,TPA',
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
            // Menambahkan informasi kategori
            $lokasi->kategori = $this->getKategoriFromTipe($lokasi->tipe);

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
            'tipe' => 'nullable|string|in:TPS,TPST,TPA', // Menambahkan filter berdasarkan tipe
        ]);

        $latitude = $validatedData['latitude'];
        $longitude = $validatedData['longitude'];

        // Query dasar
        $query = LokasiTps::selectRaw("
            id, nama_lokasi, tipe, province_id, regency_id, district_id, village_id, latitude, longitude,
            (6371 * acos(cos(radians(?)) * cos(radians(latitude))
            * cos(radians(longitude) - radians(?))
            + sin(radians(?)) * sin(radians(latitude)))) AS distance
        ", [$latitude, $longitude, $latitude]);

        // Filter berdasarkan tipe jika parameter tipe disediakan
        if (isset($validatedData['tipe'])) {
            $query->where('tipe', $validatedData['tipe']);
        }

        $nearestTps = $query->orderByRaw("distance ASC")->first();

        if (!$nearestTps) {
            return response()->json(["message" => "Tidak ada TPS terdekat ditemukan"], 404);
        }

        return response()->json([
            "message" => "TPS terdekat ditemukan",
            "data" => [
                "tps" => $nearestTps,
                "desa" => $nearestTps->village_id,
                "kategori" => $this->getKategoriFromTipe($nearestTps->tipe)
            ]
        ], 200);
    }

    /**
     * Menampilkan form edit lokasi TPS.
     */
    public function edit($id)
    {
        try {
            $lokasiTps = LokasiTps::findOrFail($id);

            $provinces = Province::all();
            $regencies = Regency::where('province_id', $lokasiTps->province_id)->get();
            $districts = District::where('regency_id', $lokasiTps->regency_id)->get();
            $villages = Village::where('district_id', $lokasiTps->district_id)->get();

            // Menambahkan array tipes untuk dropdown pilihan kategori
            $tipes = [
                'TPS' => 'TPS (Tempat Pembuangan Sampah)',
                'TPST' => 'TPST (Tempat Pembuangan Sampah Terpadu)',
                'TPA' => 'TPA (Tempat Pembuangan Akhir)'
            ];

            return view('adminpusat.lokasi-tps.edit', compact(
                'lokasiTps',
                'provinces',
                'regencies',
                'districts',
                'villages',
                'tipes'
            ));
        } catch (Exception $e) {
            return back()->with('error', 'Gagal memuat data untuk diedit: ' . $e->getMessage());
        }
    }

    /**
     * Memperbarui lokasi TPS berdasarkan ID.
     */
    public function update(Request $request, $id)
    {
        try {
            $lokasi = LokasiTps::findOrFail($id);

            $validatedData = $request->validate([
                'nama_lokasi' => 'required|string|max:255',
                'tipe' => 'required|string|in:TPS,TPST,TPA',
                'province_id' => 'required|exists:reg_provinces,id',
                'regency_id' => 'required|exists:reg_regencies,id',
                'district_id' => 'required|exists:reg_districts,id',
                'village_id' => 'required|exists:reg_villages,id',
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
            ]);

            $lokasi->update($validatedData);

            return redirect()->route('lokasi-tps.index')->with('success', 'Lokasi TPS berhasil diperbarui.');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui lokasi TPS: ' . $e->getMessage());
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
            return redirect()
                ->route('lokasi-tps.index')
                ->with('success', 'Lokasi TPS berhasil dihapus.');
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    /**
     * Mendapatkan daftar kabupaten berdasarkan provinsi
     */
    public function getRegencies(Request $request)
    {
        $provinceId = $request->province_id;
        $regencies = Regency::where('province_id', $provinceId)->get();
        return response()->json($regencies);
    }

    /**
     * Mendapatkan daftar kecamatan berdasarkan kabupaten
     */
    public function getDistricts(Request $request)
    {
        $regencyId = $request->regency_id;
        $districts = District::where('regency_id', $regencyId)->get();
        return response()->json($districts);
    }

    /**
     * Mendapatkan daftar desa berdasarkan kecamatan
     */
    public function getVillages(Request $request)
    {
        $districtId = $request->district_id;
        $villages = Village::where('district_id', $districtId)->get();
        return response()->json($villages);
    }

    /**
     * Filter lokasi TPS berdasarkan kategori (tipe)
     */
    public function filterByTipe($tipe)
    {
        try {
            $lokasiTps = LokasiTps::with(['province', 'regency', 'district', 'village'])
                ->where('tipe', $tipe)
                ->get();

            $kategori = $this->getKategoriFromTipe($tipe);

            return view('adminpusat.lokasi-tps.index', compact('lokasiTps', 'kategori'));
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Fungsi helper untuk mendapatkan nama kategori dari nilai tipe
     */
    private function getKategoriFromTipe($tipe)
    {
        switch ($tipe) {
            case 'TPS':
                return 'TPS (Tempat Pembuangan Sampah)';
            case 'TPST':
                return 'TPST (Tempat Pembuangan Sampah Terpadu)';
            case 'TPA':
                return 'TPA (Tempat Pembuangan Akhir)';
            default:
                return 'Tidak Diketahui';
        }
    }

    public function indexView()
    {
        // Mengelompokkan lokasi berdasarkan kategori
        $tps = LokasiTps::with(['province', 'regency', 'district', 'village'])
            ->where('tipe', 'TPS')
            ->get();

        $tpst = LokasiTps::with(['province', 'regency', 'district', 'village'])
            ->where('tipe', 'TPST')
            ->get();

        $tpa = LokasiTps::with(['province', 'regency', 'district', 'village'])
            ->where('tipe', 'TPA')
            ->get();

        // Menampilkan view dengan data TPS yang sudah dikategorikan
        return view('adminpusat.lokasi-tps.index', compact('tps', 'tpst', 'tpa'));
    }
}
