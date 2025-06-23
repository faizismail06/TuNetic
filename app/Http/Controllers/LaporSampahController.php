<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LaporanWarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LaporSampahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $petugas = auth()->user()->petugas;

        if (!$petugas) {
            return redirect()->back()->with('error', 'Akun Anda tidak terdaftar sebagai petugas!');
        }

        // Ambil laporan milik petugas yang sedang login dan status sedang diproses (1)
        $query = LaporanWarga::where('id_petugas', $petugas->id)
                            ->where('status', 1);

        // Filter pencarian (judul & deskripsi)
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', '%' . $search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $search . '%');
            });
        }

        // Filter status tambahan (jika dibutuhkan)
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Ambil data paginasi
        $laporSampah = $query->latest()->paginate(10);

        // Tambahkan alamat hasil reverse geocoding
        $laporSampah->getCollection()->transform(function ($laporan) {
            $laporan->alamat = $this->getLocationName($laporan->latitude, $laporan->longitude);
            return $laporan;
        });

        return view('petugas.lapor-sampah.index', compact('laporSampah'));
    }

        /**
     * Submit proof of waste collection.
     */
    public function submitBukti(Request $request, $id)
    {
        $petugas = auth()->user()->petugas;

        if (!$petugas) {
            return redirect()->back()->with('error', 'Akun Anda tidak terdaftar sebagai petugas!');
        }

        $laporan = LaporanWarga::where('id', $id)
            ->where('id_petugas', $petugas->id)
            ->firstOrFail();

        $validatedData = $request->validate([
            'bukti_gambar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'keterangan_bukti' => 'required|string|max:500',
        ]);

        // Hapus gambar lama jika ada
        if ($laporan->gambar) {
            Storage::disk('public')->delete(Str::after($laporan->gambar, 'storage/'));
        }

        // Simpan gambar baru
        $path = $request->file('bukti_gambar')->store('laporan_warga', 'public');
        $laporan->gambar = 'storage/' . $path;

        // Ubah status dan catat waktu selesai
        $laporan->status = 3; // Sudah Diangkut
        $laporan->waktu_selesai = now();

        if ($laporan->status == 3){
            $laporan->keterangan_bukti = $validatedData['keterangan_bukti'] ?? null;
        } else {
            $laporan->keterangan_bukti = null;
        }

        $laporan->save();

        return redirect()->route('petugas.lapor.index')->with('success', 'Laporan berhasil diselesaikan dan bukti diunggah.');
    }

    /**
     * Show the form for submitting proof of waste collection.
     */
    // public function buktiForm(LaporanSampah $lapor)
    // {
    //     // Pastikan laporan belum diangkut
    //     if ($laporan->status == 1) { // sudah diangkut
    //         return redirect()->back()->with('error', 'Laporan sudah diangkut');
    //     }

    //     return view('petugas.lapor.bukti', compact('lapor'));
    // }

    /**
     * Submit proof of waste collection.
     */
    // public function submitBukti(Request $request, $id)
    // {
    //     // Cari laporan berdasarkan ID
    //     $laporan = LaporanWarga::findOrFail($id);

    //     // Validasi bahwa laporan belum diangkut
    //     if ($laporan->status == 1) {
    //         return redirect()->route('petugas.lapor.index')->with('error', 'Laporan sudah diangkut');
    //     }

    //     $validated = $request->validate([
    //         'bukti_foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    //         'keterangan_bukti' => 'required|string|max:500',
    //     ]);

    //     // Upload bukti foto
    //     if ($request->hasFile('bukti_foto')) {
    //         $validated['bukti_foto'] = $request->file('bukti_foto')->store('bukti-sampah', 'public');
    //     }

    //     // Update status menjadi sudah diangkut dan tambahkan tanggal penyelesaian
    //     $validated['status'] = 1; // 1 = sudah diangkut
    //     $validated['tanggal_selesai'] = now();

    //     $laporan->update($validated);

    //     return redirect()->route('petugas.lapor.index')->with('success', 'Bukti pengangkutan berhasil dikirim');
    // }
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
            Log::error('Error getting location name: ' . $e->getMessage());
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
        $petugas = auth()->user()->petugas;

        if (!$petugas) {
            return back()->with('error', 'Akun Anda tidak terdaftar sebagai petugas.');
        }
        // Query dasar (bisa filter kalau mau, contoh by status atau search)
        $query = LaporanWarga::where('id_petugas', $petugas->id);

        // Contoh filter pencarian
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', '%' . $search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $search . '%');
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
