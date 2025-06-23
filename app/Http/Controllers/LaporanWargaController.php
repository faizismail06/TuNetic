<?php

namespace App\Http\Controllers;

use App\Models\LaporanWarga;
use App\Models\LokasiTps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class LaporanWargaController extends Controller
{
    // Tampilkan 2 laporan terbaru user
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

    // Tampilkan form tambah laporan
    public function create()
    {
        // Sesuaikan dengan enum migrasi
        $kategoriList = collect([
            'Tumpukan Sampah',
            'TPS Penuh',
            'Bau Menyengat',
            'Pembuangan Liar',
            'Lainnya'
        ]);
        return view('masyarakat.lapor', compact('kategoriList'));
    }

    // Simpan laporan baru
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_user' => 'required|exists:users,id',
            'judul' => 'required|string|max:100',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'deskripsi' => 'required|string',
            'jenis_masalah' => 'required|string',
            'masalah_lainnya' => 'nullable|string|max:100',
        ]);

        // Hapus karakter jawa (atau lainnya jika perlu)
        $validatedData['judul'] = preg_replace('/[\x{A980}-\x{A9CD}]/u', '', $validatedData['judul']);
        $validatedData['deskripsi'] = preg_replace('/[\x{A980}-\x{A9CD}]/u', '', $validatedData['deskripsi']);

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('laporan_warga', 'public');
            $validatedData['gambar'] = basename($path);
        }

        // Mapping ke kolom kategori di DB
        $validatedData['kategori'] = $request->jenis_masalah === 'Lainnya'
            ? $request->masalah_lainnya
            : $request->jenis_masalah;

        // Pastikan yang dikirim hanya field di DB
        unset($validatedData['jenis_masalah'], $validatedData['masalah_lainnya']);

        $laporan = LaporanWarga::create($validatedData);

        return redirect()->route('masyarakat.detailRiwayat', ['id' => $laporan->id])
            ->with('success', 'Laporan berhasil dikirim!');
    }

    // Tampilkan detail laporan
    public function show($id)
    {
        $laporan = LaporanWarga::findOrFail($id);
        if ($laporan->latitude && $laporan->longitude) {
            $laporan->lokasi = $this->getLocationName($laporan->latitude, $laporan->longitude);
        }
        return view('masyarakat.detailRiwayat', compact('laporan'));
    }

    // Update laporan warga
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

        if (isset($validatedData['judul'])) {
            $validatedData['judul'] = preg_replace('/[\x{A980}-\x{A9CD}]/u', '', $validatedData['judul']);
        }
        if (isset($validatedData['deskripsi'])) {
            $validatedData['deskripsi'] = preg_replace('/[\x{A980}-\x{A9CD}]/u', '', $validatedData['deskripsi']);
        }
        if ($request->hasFile('gambar')) {
            if ($laporan->gambar) {
                Storage::disk('public')->delete('laporan_warga/' . $laporan->gambar);
            }
            $path = $request->file('gambar')->store('laporan_warga', 'public');
            $validatedData['gambar'] = basename($path);
        }
        if (isset($validatedData['status']) && $validatedData['status'] == 3) {
            $validatedData['tanggal_diangkut'] = now();
        }

        $laporan->update($validatedData);
        return response()->json($laporan, 200);
    }

    // Riwayat laporan user
    public function allhistory()
    {
        $userId = auth()->id();
        $laporan = LaporanWarga::where('id_user', $userId)->latest()->get();
        foreach ($laporan as $lapor) {
            $lapor->lokasi = $this->getLocationName($lapor->latitude, $lapor->longitude);
        }
        return view('masyarakat.riwayat', compact('laporan'));
    }

    // Helper: Ambil nama lokasi berdasarkan lat,lon
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
                        $lokasi[] = preg_replace('/[\x{A980}-\x{A9CD}]/u', '', $jalan);
                    if ($desa)
                        $lokasi[] = 'Desa/Kel. ' . preg_replace('/[\x{A980}-\x{A9CD}]/u', '', $desa);
                    if ($kabupaten)
                        $lokasi[] = preg_replace('/[\x{A980}-\x{A9CD}]/u', '', $kabupaten);
                    if ($provinsi)
                        $lokasi[] = preg_replace('/[\x{A980}-\x{A9CD}]/u', '', $provinsi);
                    return implode(', ', $lokasi);
                }
            }
        } catch (\Exception $e) {
            return "Lokasi tidak tersedia";
        }
        return "Lokasi tidak tersedia";
    }

    // Hapus laporan (soft delete)
    public function destroy($id)
    {
        $laporan = LaporanWarga::findOrFail($id);
        if ($laporan->gambar) {
            Storage::disk('public')->delete(str_replace(asset('storage/'), '', $laporan->gambar));
        }
        $laporan->delete();
        return response()->json(["message" => "Laporan berhasil dihapus"], 204);
    }

    // Tampilkan detail riwayat
    public function detailRiwayat($id)
    {
        $laporan = LaporanWarga::findOrFail($id);

        // Tambahkan ini untuk mengambil lokasi
        if ($laporan->latitude && $laporan->longitude) {
            $laporan->lokasi = $this->getLocationName($laporan->latitude, $laporan->longitude);
        }

        return view('masyarakat.detailRiwayat', compact('laporan'));
    }
}
