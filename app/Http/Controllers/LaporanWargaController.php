<?php

namespace App\Http\Controllers;

use App\Models\LaporanWarga;
use App\Models\LokasiTps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;


class LaporanWargaController extends Controller
{
    /**
     * Menampilkan semua laporan warga.
     */
    public function index()
    {
        $laporanTerbaru = LaporanWarga::where('id_user', auth()->id())
            ->latest()
            ->take(2)
            ->get();
        foreach ($laporanTerbaru as $laporan) {
            $laporan->lokasi = $this->getLocationName($laporan->latitude, $laporan->longitude);
        }

        return view('masyarakat.index', compact('laporanTerbaru'));
    }



    /**
     * Menyimpan laporan warga baru.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_user' => 'required|exists:users,id',
            'judul' => 'required|string|max:100',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'deskripsi' => 'required|string',
            // 'status' => 'integer|in:1,2,3', // 0: Pending, 1: Diproses, 2: Selesai
        ]);

        // Pembersihan teks untuk menghapus aksara Jawa (dan karakter lainnya jika perlu)
        $validatedData['judul'] = preg_replace('/[\x{A980}-\x{A9CD}]/u', '', $validatedData['judul']);
        $validatedData['deskripsi'] = preg_replace('/[\x{A980}-\x{A9CD}]/u', '', $validatedData['deskripsi']);

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('laporan_warga', 'public');
            $validatedData['gambar'] = asset('storage/' . $path);
        }

        $laporan = LaporanWarga::create($validatedData);
        return redirect()->route('masyarakat.detailRiwayat', ['id' => $laporan->id])
            ->with('success', 'Laporan berhasil dikirim!');

        // Cari TPS terdekat berdasarkan latitude & longitude laporan


    }


    /**
     * Menampilkan laporan berdasarkan ID.
     */
    public function show($id)
    {
        $laporan = LaporanWarga::findOrFail($id);

        // Cek apakah latitude dan longitude ada
        if ($laporan->latitude && $laporan->longitude) {
            $latitude = $laporan->latitude;
            $longitude = $laporan->longitude;

            // Panggil fungsi getLocationName untuk mendapatkan nama lokasi
            $laporan->lokasi = $this->getLocationName($latitude, $longitude);
        }

        return view('masyarakat.detailRiwayat', compact('laporan'));
    }


    /**
     * Memperbarui laporan warga.
     */
    public function update(Request $request, $id)
    {
        $laporan = LaporanWarga::findOrFail($id);

        $validatedData = $request->validate([
            'id_user' => 'sometimes|exists:users,id',
            'judul' => 'nullable|string|max:255',
            'latitude' => 'sometimes|numeric|between:-90,90',
            'longitude' => 'sometimes|numeric|between:-180,180',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'deskripsi' => 'sometimes|string',
            'status' => 'integer|in:0,1,2,3',
        ]);

        // Pembersihan teks untuk menghapus aksara Jawa (dan karakter lainnya jika perlu)
        if (isset($validatedData['judul'])) {
            $validatedData['judul'] = preg_replace('/[\x{A980}-\x{A9CD}]/u', '', $validatedData['judul']);
        }

        if (isset($validatedData['deskripsi'])) {
            $validatedData['deskripsi'] = preg_replace('/[\x{A980}-\x{A9CD}]/u', '', $validatedData['deskripsi']);
        }

        if ($request->hasFile('gambar')) {
            if ($laporan->gambar) {
                Storage::disk('public')->delete(str_replace(asset('storage/'), '', $laporan->gambar));
            }
            $path = $request->file('gambar')->store('laporan_warga', 'public');
            $validatedData['gambar'] = asset('storage/' . $path);
        }

        if (isset($validatedData['status']) && $validatedData['status'] == 3) {
            $validatedData['tanggal_diangkut'] = now(); // Isi otomatis waktu sekarang
        }


        $laporan->update($validatedData);
        return response()->json($laporan, 200);
    }
    public function riwayat()
    {
        $userId = auth()->id();
        $laporan = LaporanWarga::where('id_user', $userId)->latest()->get();

        foreach ($laporan as $lapor) {
            $lapor->lokasi = $this->getLocationName($lapor->latitude, $lapor->longitude);
        }

        return view('masyarakat.riwayat', compact('laporan'));
    }


    public function create()
    {
        return view('masyarakat.lapor');
    }

    public function getLocationName($latitude, $longitude)
    {
        try {
            $url = "https://nominatim.openstreetmap.org/reverse?lat={$latitude}&lon={$longitude}&format=json&addressdetails=1";

            $response = Http::timeout(5)->withHeaders([
                'User-Agent' => 'YourAppName/1.0 (your-email@example.com)'
            ])->get($url);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['address'])) {
                    $address = $data['address'];

                    $jalan = $address['road'] ?? ($address['neighbourhood'] ?? '');
                    $desa = $address['village'] ?? ($address['town'] ?? '');
                    $kabupaten = $address['city'] ?? ($address['county'] ?? '');
                    $provinsi = $address['state'] ?? '';

                    $lokasi = [];

                    if ($jalan)
                        $lokasi[] = preg_replace('/[\x{A980}-\x{A9CD}]/u', '', $jalan);  // Hapus aksara Jawa
                    if ($desa)
                        $lokasi[] = 'Desa/Kel. ' . preg_replace('/[\x{A980}-\x{A9CD}]/u', '', $desa);  // Hapus aksara Jawa
                    if ($kabupaten)
                        $lokasi[] = preg_replace('/[\x{A980}-\x{A9CD}]/u', '', $kabupaten);  // Hapus aksara Jawa
                    if ($provinsi)
                        $lokasi[] = preg_replace('/[\x{A980}-\x{A9CD}]/u', '', $provinsi);  // Hapus aksara Jawa

                    return implode(', ', $lokasi);
                }
            }
        } catch (\Exception $e) {
            // Kalau error apapun (timeout, DNS, dst) fallback default
            return "Lokasi tidak tersedia";
        }

        return "Lokasi tidak tersedia";
    }

    public function submit(Request $request)
    {
        $data = new Laporan();
        $data->id_user = $request->id_user;
        $data->judul = $request->judul;

        // Upload foto kalau ada
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('uploads', 'public');
            $data->gambar = $path;
        }

        $data->latitude = $request->latitude;
        $data->longitude = $request->longitude;

        // Kategori diambil dari dropdown atau input lain
        $data->kategori = $request->jenis_masalah === 'Lainnya'
            ? $request->masalah_lainnya
            : $request->jenis_masalah;

        $data->deskripsi = $request->deskripsi;
        $data->status = 0;

        $data->save();

        return redirect()->route('lapor.sukses');
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

    public function detailRiwayat($id)
    {
        $laporan = LaporanWarga::findOrFail($id);
        return view('masyarakat.detailRiwayat', compact('laporan'));
    }

}
