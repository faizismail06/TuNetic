<?php

namespace App\Http\Controllers;

use App\Models\LaporanWarga; // Pastikan model ini ada
use App\Models\User;        // Import model User untuk relasi
use App\Models\LokasiTps;    // Jika ini digunakan, pastikan LokasiTps ada dan benar
use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str; // Tambahkan ini untuk Str::after
use Carbon\Carbon; // Tambahkan ini untuk Carbon
use Illuminate\Support\Facades\Log;

class LaporanWargaAdminController extends Controller
{
    /**
     * Konstruktor: Tambahkan middleware untuk memastikan hanya admin yang bisa mengakses.
     * Anda dapat mengaktifkannya jika sudah memiliki middleware yang sesuai.
     */
    // public function __construct()
    // {
    //     $this->middleware('auth'); // Contoh: hanya user terautentikasi
    //     $this->middleware('can:admin-access'); // Contoh: jika Anda punya gate/policy untuk admin
    // }

    /**
     * Menampilkan semua laporan warga untuk Admin.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Mengambil semua laporan, urutkan berdasarkan created_at terbaru, dan muat relasi user.
        $laporan = LaporanWarga::with('user')->latest()->get();

        // Loop untuk menambahkan nama lokasi jika koordinat tersedia.
        foreach ($laporan as $item) {
            if ($item->latitude && $item->longitude) {
                $item->lokasi = $this->getLocationName($item->latitude, $item->longitude);
            } else {
                $item->lokasi = 'Lokasi tidak tersedia';
            }
        }

        // Melewatkan variabel 'pengaduans' ke view.
        return view('adminpusat.laporan-pengaduan.index', compact('laporan'));
    }


    /**
     * Menampilkan laporan berdasarkan ID (untuk admin).
     *
     * @param int $id
     * @return \Illuminate\View\View
     */

    public function show($id)
    {
        // PERBAIKAN PENTING: Ubah $lapor menjadi $laporan agar konsisten dengan view
        $laporan = LaporanWarga::with('user', 'petugas')->findOrFail($id);

        // Menambahkan nama lokasi jika koordinat tersedia.
        if ($laporan->latitude && $laporan->longitude) {
            $laporan->lokasi = $this->getLocationName($laporan->latitude, $laporan->longitude);
        } else {
            $laporan->lokasi = 'Lokasi tidak tersedia';
        }

        $petugas = Petugas::all();

        // PERBAIKAN PENTING: Lewatkan variabel 'laporan' ke view detail.
        return view('adminpusat.laporan-pengaduan.detail', compact('laporan', 'petugas')); // Pastikan ini mengarah ke detail.blade.php
    }


    /**
     * Memperbarui laporan warga (oleh admin).
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
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
            'status' => 'required|in:0,1,2',
            'kategori' => 'required|in:Tumpukan sampah,TPS Penuh,Bau Menyengat,Pembuangan Liar,Lainnya',
            'id_petugas' => 'nullable|exists:petugas,id',
            'alasan_ditolak' => 'nullable|string|max:255',
        ]);

        // Sanitasi karakter (jika diperlukan)
        if (isset($validatedData['judul'])) {
            $validatedData['judul'] = preg_replace('/[\x{A980}-\x{A9CD}]/u', '', $validatedData['judul']);
        }
        if (isset($validatedData['deskripsi'])) {
            $validatedData['deskripsi'] = preg_replace('/[\x{A980}-\x{A9CD}]/u', '', $validatedData['deskripsi']);
        }

        // Penanganan upload dan penghapusan gambar
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($laporan->gambar) {
                // Pastikan path yang dihapus sesuai dengan yang disimpan di DB
                // Jika DB menyimpan 'storage/laporan_warga/nama_file.jpg'
                Storage::disk('public')->delete(Str::after($laporan->gambar, 'storage/'));
            }
            // Simpan gambar baru
            $path = $request->file('gambar')->store('laporan_warga', 'public');
            $validatedData['gambar'] = 'storage/' . $path; // Simpan path relatif
        } elseif ($request->input('clear_gambar')) { // Tambahkan input tersembunyi/checkbox di form untuk menghapus gambar
            if ($laporan->gambar) {
                Storage::disk('public')->delete(Str::after($laporan->gambar, 'storage/'));
            }
            $validatedData['gambar'] = null;
        } else {
            // Jika tidak ada file baru diupload dan tidak ada instruksi untuk menghapus,
            // biarkan gambar lama tetap ada dengan menghapus 'gambar' dari $validatedData
            unset($validatedData['gambar']);
        }

        $laporan->update($validatedData);

        // Update waktu berdasarkan status
        if ($validatedData['status'] == 1 && !$laporan->waktu_diambil) {
            $laporan->waktu_diambil = now();
        } elseif ($validatedData['status'] == 2 && !$laporan->waktu_selesai) {
            $laporan->waktu_selesai = now();
        } elseif ($validatedData['status'] == 0) {
            $laporan->waktu_diambil = null;
            $laporan->waktu_selesai = null;
        }
        $laporan->save();

        return redirect()->route('laporan.index', ['id' => $laporan->id])->with('success', 'Laporan berhasil diperbarui.');
    }

    /**
     * Menghapus laporan warga.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $laporan = LaporanWarga::findOrFail($id);

        if ($laporan->gambar) {
            // Pastikan path gambar yang dihapus sesuai dengan yang disimpan di DB
            Storage::disk('public')->delete(Str::after($laporan->gambar, 'storage/'));
        }

        $laporan->delete();

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dihapus!');
    }

    /**
     * Fungsi helper untuk mendapatkan nama lokasi dari koordinat.
     * Menggunakan Nominatim (OpenStreetMap) API.
     *
     * @param float $latitude
     * @param float $longitude
     * @return string
     */
    private function getLocationName($latitude, $longitude)
    {
        try {
            $url = "https://nominatim.openstreetmap.org/reverse?lat={$latitude}&lon={$longitude}&format=json&addressdetails=1";

            $response = Http::timeout(5)->withHeaders([
                'User-Agent' => 'ResQApp/1.0 (admin@resq.com)' // Ubah User-Agent Anda ke email/domain Anda
            ])->get($url);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['address'])) {
                    $address = $data['address'];
                    $jalan = $address['road'] ?? ($address['pedestrian'] ?? ($address['neighbourhood'] ?? ''));
                    $desa = $address['village'] ?? ($address['suburb'] ?? ($address['town'] ?? ($address['hamlet'] ?? '')));
                    $kecamatan = $address['district'] ?? '';
                    $kabupaten = $address['city'] ?? ($address['county'] ?? '');
                    $provinsi = $address['state'] ?? '';

                    $lokasi = [];
                    if ($jalan) $lokasi[] = preg_replace('/[\x{A980}-\x{A9CD}]/u', '', $jalan);
                    if ($desa) $lokasi[] = 'Desa/Kel. ' . preg_replace('/[\x{A980}-\x{A9CD}]/u', '', $desa);
                    if ($kecamatan) $lokasi[] = 'Kec. ' . preg_replace('/[\x{A980}-\x{A9CD}]/u', '', $kecamatan);
                    if ($kabupaten) $lokasi[] = preg_replace('/[\x{A980}-\x{A9CD}]/u', '', $kabupaten);
                    if ($provinsi) $lokasi[] = preg_replace('/[\x{A980}-\x{A9CD}]/u', '', $provinsi);

                    return implode(', ', $lokasi);
                }
            }
        } catch (\Exception $e) {
            Log::error("Error fetching location for {$latitude}, {$longitude}: " . $e->getMessage());
            return "Lokasi tidak tersedia";
        }

        return "Lokasi tidak tersedia";
    }

    public function tugaskan(Request $request, $id)
    {
        $laporan = LaporanWarga::findOrFail($id);

        $request->validate([
            'id_petugas' => 'required|exists:petugas,id',
        ]);

        $laporan->id_petugas = $request->id_petugas;
        $laporan->status = 1; // diproses
        $laporan->waktu_diambil = now();
        $laporan->save();

        return redirect()->route('admin.laporan.show', $laporan->id)->with('success', 'Laporan berhasil ditugaskan.');
    }


    /**
     * Fungsi yang mungkin tidak relevan untuk admin (misalnya untuk user biasa).
     * Sebaiknya pindahkan ini ke controller khusus untuk user biasa.
     *
     * public function riwayat() { ... }
     * public function create() { ... }
     * public function store(Request $request) { ... } // Pindahkan ini jika hanya user biasa yang membuat laporan awal
     * private function findNearestTps($latitude, $longitude) { ... }
     */
}
