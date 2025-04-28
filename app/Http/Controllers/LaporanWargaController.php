<?php

namespace App\Http\Controllers;

use App\Models\LaporanWarga;
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
            'status' => 'integer|in:0,1,2', // 0: Pending, 1: Diproses, 2: Selesai
        ]);

        if ($request->hasFile('gambar')) {
            $validatedData['gambar'] = $request->file('gambar')->store('laporan_warga', 'public');
        }

        $laporan = LaporanWarga::create($validatedData);
        return redirect()->back()->with('success', 'Laporan berhasil dikirim!');
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
            'judul' => 'nullable|string|max:255',
            'latitude' => 'sometimes|numeric|between:-90,90',
            'longitude' => 'sometimes|numeric|between:-180,180',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'deskripsi' => 'sometimes|string',
            'status' => 'integer|in:0,1,2',
        ]);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($laporan->gambar) {
                Storage::disk('public')->delete($laporan->gambar);
            }
            $validatedData['gambar'] = $request->file('gambar')->store('laporan_warga', 'public');
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
                        $lokasi[] = $jalan;
                    if ($desa)
                        $lokasi[] = 'Desa/Kel. ' . $desa;
                    if ($kabupaten)
                        $lokasi[] = $kabupaten;
                    if ($provinsi)
                        $lokasi[] = $provinsi;

                    return implode(', ', $lokasi);
                }
            }
        } catch (\Exception $e) {
            // Kalau error apapun (timeout, DNS, dst) fallback default
            return "Lokasi tidak tersedia";
        }

        return "Lokasi tidak tersedia";
    }

    /**
     * Menghapus laporan warga (Soft Delete).
     */
    public function destroy($id)
    {
        $laporan = LaporanWarga::findOrFail($id);
        $laporan->delete();
        return response()->json(["message" => "Laporan berhasil dihapus"], 204);
    }
}
