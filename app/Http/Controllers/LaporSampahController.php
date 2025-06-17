<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LaporanWarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class LaporSampahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) 
    {
        // Query dasar
        $query = LaporanWarga::query();

        // Filter pencarian
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', '%'.$search.'%')
                ->orWhere('deskripsi', 'like', '%'.$search.'%');
            });
        }

        // Filter status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Ambil data + pagination
        $laporSampah = $query->latest()->paginate(10);

        // Tambahkan alamat hasil reverse geocoding ke setiap item
        $laporSampah->getCollection()->transform(function ($laporan) {
            $laporan->alamat = $this->getLocationName($laporan->latitude, $laporan->longitude);
            return $laporan;
        });

        return view('petugas.lapor-sampah.index', compact('laporSampah'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('petugas.lapor-sampah.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $lat = $request->latitude;
        $lon = $request->longitude;

        // Panggil fungsi getLocationName untuk mendapatkan alamat yang lebih terstruktur
        $alamat = null;
        if ($lat && $lon) {
            $alamat = $this->getLocationName($lat, $lon);
        }

        LaporanWarga::create([
            'judul' => $request->judul,
            'latitude' => $lat,
            'longitude' => $lon,
            'deskripsi' => $request->deskripsi,
            'gambar' => $request->gambar,
            'status' => 0,
            'alamat' => $alamat, // ← disimpan di database
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil dikirim!');
    }

    /**
     * Display the specified resource.
     */
    public function show(LaporanSampah $lapor)
    {
        return view('petugas.lapor.show', compact('lapor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LaporanSampah $lapor)
    {
        return view('petugas.lapor.edit', compact('lapor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LaporanSampah $lapor)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'alamat' => 'required|string|max:500',
            'deskripsi' => 'required|string|max:1000',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle upload foto baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($lapor->foto && Storage::disk('public')->exists($lapor->foto)) {
                Storage::disk('public')->delete($lapor->foto);
            }
            $validated['foto'] = $request->file('foto')->store('laporan-sampah', 'public');
        }

        $lapor->update($validated);

        return redirect()->route('petugas.lapor.index')->with('success', 'Laporan sampah berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LaporanSampah $lapor)
    {
        // Hapus foto laporan jika ada
        if ($lapor->foto && Storage::disk('public')->exists($lapor->foto)) {
            Storage::disk('public')->delete($lapor->foto);
        }
        
        // Hapus bukti foto jika ada
        if ($lapor->bukti_foto && Storage::disk('public')->exists($lapor->bukti_foto)) {
            Storage::disk('public')->delete($lapor->bukti_foto);
        }
        
        $lapor->delete();

        return redirect()->route('petugas.lapor.index')->with('success', 'Laporan sampah berhasil dihapus');
    }

    /**
     * Show the form for submitting proof of waste collection.
     */
    public function buktiForm(LaporanSampah $lapor)
    {
        // Pastikan laporan belum diangkut
        if ($laporan->status == 1) { // sudah diangkut
            return redirect()->back()->with('error', 'Laporan sudah diangkut');
        }

        return view('petugas.lapor.bukti', compact('lapor'));
    }

    /**
     * Submit proof of waste collection.
     */
    public function submitBukti(Request $request, $id)
    {
        // Cari laporan berdasarkan ID
        $laporan = LaporanWarga::findOrFail($id);
        
        // Validasi bahwa laporan belum diangkut
        if ($laporan->status == 1) {
            return redirect()->route('petugas.lapor.index')->with('error', 'Laporan sudah diangkut');
        }

        $validated = $request->validate([
            'bukti_foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'keterangan_bukti' => 'required|string|max:500',
        ]);

        // Upload bukti foto
        if ($request->hasFile('bukti_foto')) {
            $validated['bukti_foto'] = $request->file('bukti_foto')->store('bukti-sampah', 'public');
        }

        // Update status menjadi sudah diangkut dan tambahkan tanggal penyelesaian
        $validated['status'] = 1; // 1 = sudah diangkut
        $validated['tanggal_selesai'] = now();

        $laporan->update($validated);

        return redirect()->route('petugas.lapor.index')->with('success', 'Bukti pengangkutan berhasil dikirim');
    }
    /**
     * Mengubah latitude dan longitude menjadi alamat jalan menggunakan API OpenStreetMap (Nominatim)
     * dengan format yang lebih terstruktur dan mudah dibaca
     */
    private function getLocationName($latitude, $longitude)
    {
        try {
            // Validasi input koordinat
            if (!$latitude || !$longitude) {
                return "Koordinat tidak tersedia";
            }

            $url = "https://nominatim.openstreetmap.org/reverse?lat={$latitude}&lon={$longitude}&format=json&addressdetails=1";

            $response = Http::timeout(10)->withHeaders([
                'User-Agent' => 'LaporSampahApp/1.0 (admin@example.com)',
                'Accept-Language' => 'id' // Bahasa Indonesia
            ])->get($url);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['address'])) {
                    $address = $data['address'];

                    // Ambil komponen alamat dengan prioritas
                    $jalan = $address['road'] ?? ($address['footway'] ?? ($address['path'] ?? ''));
                    $nomor = $address['house_number'] ?? '';
                    $rt_rw = $address['neighbourhood'] ?? '';
                    $desa = $address['village'] ?? ($address['suburb'] ?? ($address['town'] ?? ''));
                    $kecamatan = $address['suburb'] ?? ($address['city_district'] ?? '');
                    $kabupaten = $address['city'] ?? ($address['county'] ?? '');
                    $provinsi = $address['state'] ?? '';

                    $lokasi = [];

                    // Format alamat dengan prioritas yang lebih baik
                    if ($jalan) {
                        if ($nomor) {
                            $lokasi[] = $jalan . ' No. ' . $nomor;
                        } else {
                            $lokasi[] = $jalan;
                        }
                    }

                    if ($rt_rw && $rt_rw !== $jalan) {
                        $lokasi[] = $rt_rw;
                    }

                    if ($desa) {
                        $lokasi[] = 'Desa/Kel. ' . $desa;
                    }

                    if ($kecamatan && $kecamatan !== $desa) {
                        $lokasi[] = 'Kec. ' . $kecamatan;
                    }

                    if ($kabupaten) {
                        $lokasi[] = $kabupaten;
                    }

                    if ($provinsi) {
                        $lokasi[] = $provinsi;
                    }

                    // Jika ada komponen alamat, gabungkan dengan koma
                    if (!empty($lokasi)) {
                        return implode(', ', $lokasi);
                    }

                    // Fallback ke display_name jika parsing gagal
                    if (isset($data['display_name'])) {
                        return $data['display_name'];
                    }
                }
            }
        } catch (\Exception $e) {
            // Log error jika diperlukan
            \Log::error('Error getting location name: ' . $e->getMessage());
            return "Lokasi tidak dapat diakses";
        }

        // Fallback terakhir menampilkan koordinat
        return "Lat: {$latitude}, Lon: {$longitude}";
    }

    /**
     * Legacy function - kept for backward compatibility
     * @deprecated Use getLocationName instead
     */
    public function getAddressFromCoordinates($lat, $lon)
    {
        return $this->getLocationName($lat, $lon);
    }

    public function laporanSampah(Request $request)
    {
        // Query dasar (bisa filter kalau mau, contoh by status atau search)
        $query = LaporanWarga::query();

        // Contoh filter pencarian
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', '%'.$search.'%')
                ->orWhere('deskripsi', 'like', '%'.$search.'%');
            });
        }

        // Contoh filter status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Ambil data laporan, misal paginate 10
        $laporSampah = $query->latest()->paginate(10);

        // Tambahkan reverse geocoding alamat (kalau memang ada di controller, kalau tidak, skip)
        if (method_exists($this, 'getLocationName')) {
            $laporSampah->getCollection()->transform(function ($laporan) {
                $laporan->alamat = $this->getLocationName($laporan->latitude, $laporan->longitude);
                return $laporan;
            });
        }

        // Kirim data ke view
        return view('petugas.dashboard.home-petugas', compact('laporSampah'));
    }
}