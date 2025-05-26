<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Models\Rute;
use App\Models\LokasiTps;
use App\Models\RuteTps;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Exception;

class RuteController extends Controller
{
    /**
     * Menampilkan semua rute.
     */
    public function index()
    {
        $rute = Rute::all();

        // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -- - - - - - - - - - -- - - - - - -
        // PENGAMBILAN TANGGAL PADA TABEL JADWAL (Dibatalkan karena setiap TPS memiliki Tanggal yang berbeda")
        // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -- - - - - - - - - - -- - - - - - -

        // foreach ($rute as $item) {
            // if ($item->latitude && $item->longitude) {
            //     $item->alamat = $this->getLocationName($item->latitude, $item->longitude);
            // } else {
            //     $item->alamat = '-';
            // }

            // Ambil tanggal dari relasi jadwal paling awal atau terakhir (tergantung kebutuhan)
            // $jadwalDates = [];

            // foreach ($item->ruteTps as $ruteTps) {
            //     foreach ($ruteTps->jadwalOperasional as $jadwalOp) {
            //         if ($jadwalOp->jadwal) {
            //             $jadwalDates[] = $jadwalOp->jadwal->tanggal ?? null;
            //         }
            //     }
            // }

            // Ambil tanggal terbaru, jika ada
            // $item->tanggal_jadwal = collect($jadwalDates)->filter()->sortDesc()->first() ?? '-';
        // }

        return view('adminpusat.manage-rute.index', compact('rute'));
    }

    /**
     * Menyimpan rute baru.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nama_rute' => 'required|string|max:255',
                // 'latitude' => 'required|numeric|between:-90,90',
                // 'longitude' => 'required|numeric|between:-180,180',
                'wilayah' => 'required|string',
                'tps' => 'required|array|min:1',
                'tps.*' => 'nullable|exists:lokasi_tps,id', // validasi dropdown TPS
                'tpst_id' => ['nullable', Rule::exists('lokasi_tps', 'id')->where('tipe', 'TPST')],
                'tpa_id' => ['nullable', Rule::exists('lokasi_tps', 'id')->where('tipe', 'TPA')],
            ]);

            $rute = Rute::create([
                'nama_rute' => $request->nama_rute,
                'wilayah' => $request->wilayah,
                'tpst_id' => $request->tpst_id,
                'tpa_id' => $request->tpa_id,
            ]);

            // foreach ($request->tps as $id_lokasi_tps) {
            //     RuteTps::create([
            //         'id_rute' => $rute->id,
            //         'id_lokasi_tps' => $id_lokasi_tps,
            //     ]);
            // }

            $rute->tps()->attach(array_filter($request->tps));
            // return response()->json($rute, 201);
            return redirect()->route('manage-rute.index')->with('success', 'Rute berhasil ditambahkan');
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal menyimpan rute',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function create()
    {
        $lokasiTps = LokasiTps::all();
        return view('adminpusat.manage-rute.create', compact('lokasiTps')); // Pastikan ini mengarah ke view yang benar
    }

    /**
     * Menampilkan detail rute berdasarkan ID.
     */
    public function show($id)
    {
        try {
            $rute = Rute::findOrFail($id);

            return view('adminpusat.manage-rute.detail', compact('id'));
        } catch (Exception $e) {
            return response()->json(["error" => "Rute tidak ditemukan", "message" => $e->getMessage()], 404);
        }
    }

    public function getDetailJson($id)
    {
        $rute = Rute::with(['tps' => function ($query) {
            $query->select('lokasi_tps.id', 'nama_lokasi', 'tipe');
        }, 'tpst', 'tpa'])->findOrFail($id);

        $tpsItems = $rute->tps->where('tipe', 'TPS')->values();
        $tpstItem = $rute->tps->firstWhere('tipe', 'TPST');
        $tpaItem  = $rute->tps->firstWhere('tipe', 'TPA');

        return response()->json([
            'TPS'  => $tpsItems->map(fn ($item) => $item->nama_lokasi),
            'TPST' => $tpstItem->nama_lokasi ?? '-',
            'TPA'  => $tpaItem->nama_lokasi ?? '-',
        ]);
    }

    public function edit($id)
    {
        $rute = Rute::findOrFail($id);
        $lokasiTps = LokasiTps::all();
        return view('adminpusat.manage-rute.edit', compact('rute', 'lokasiTps'));
    }

    /**
     * Mengupdate rute berdasarkan ID.
     */
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'nama_rute' => 'required|string|max:255',
                'wilayah' => 'required|string',
                'tps' => 'required|array|min:1',
                'tps.*' => 'nullable|exists:lokasi_tps,id',
                'tpst_id' => ['nullable', Rule::exists('lokasi_tps', 'id')->where('tipe', 'TPST')],
                'tpa_id' => ['nullable', Rule::exists('lokasi_tps', 'id')->where('tipe', 'TPA')],
            ]);

            $rute = Rute::findOrFail($id);

            // Update data utama rute
            $rute->update([
                'nama_rute' => $request->nama_rute,
                'wilayah' => $request->wilayah,
                'tpst_id' => $request->tpst_id,
                'tpa_id' => $request->tpa_id,
            ]);

            // Update relasi TPS
            $rute->tps()->sync(array_filter($request->tps));

            return redirect()->route('manage-rute.index')->with('success', 'Rute berhasil diperbarui');
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal memperbarui rute',
                'message' => $e->getMessage(),
            ], 400);
        }
    }
    // public function update(Request $request, $id)
    // {
    //     try {
    //         $rute = Rute::findOrFail($id);

    //         $validatedData = $request->validate([
    //             'nama_rute' => 'sometimes|string|max:255',
    //             // 'latitude' => 'sometimes|numeric|between:-90,90',
    //             // 'longitude' => 'sometimes|numeric|between:-180,180',
    //             'wilayah' => 'sometimes|string',
    //         ]);

    //         $rute->update($validatedData);

    //         return response()->json([
    //             'message' => 'Rute berhasil diperbarui',
    //             'data' => $rute
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'error' => 'Gagal mengupdate rute',
    //             'message' => $e->getMessage(),
    //         ], 400);
    //     }
    // }

    /**
     * Menghapus rute berdasarkan ID.
     */
    public function destroy($id)
    {
        try {
            $rute = Rute::findOrFail($id);
            $rute->ruteTps()->delete();
            $rute->delete();

            return redirect()->route('manage-rute.index')->with('success', 'Rute berhasil dihapus');
        } catch (Exception $e) {
            return redirect()->route('manage-rute.index')->with('error', 'Gagal menghapus rute: ' . $e->getMessage());
        }
    }

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -- - - - - - - - - - -- - - - - - -
    //  Mengubah latitude dan longitude menjadi alamat jalan menggunakan API OpenStreetMap (Nominatim)
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -- - - - - - - - - - -- - - - - - -\

    // private function getLocationName($latitude, $longitude)
    // {
    //     try {
    //         $url = "https://nominatim.openstreetmap.org/reverse?lat={$latitude}&lon={$longitude}&format=json&addressdetails=1";
    
    //         $response = Http::timeout(5)->withHeaders([
    //             'User-Agent' => 'YourAppName/1.0 (your-email@example.com)',
    //             'Accept-Language' => 'en' // atau 'en' untuk Inggris
    //         ])->get($url);
    
    //         if ($response->successful()) {
    //             $data = $response->json();
    
    //             if (isset($data['address'])) {
    //                 $address = $data['address'];
    
    //                 $jalan = $address['road'] ?? ($address['neighbourhood'] ?? '');
    //                 $desa = $address['village'] ?? ($address['town'] ?? '');
    //                 $kabupaten = $address['city'] ?? ($address['county'] ?? '');
    //                 $provinsi = $address['state'] ?? '';
    
    //                 $lokasi = [];
    
    //                 if ($jalan)     $lokasi[] = $jalan;
    //                 if ($desa)      $lokasi[] = 'Desa/Kel. ' . $desa;
    //                 if ($kabupaten) $lokasi[] = $kabupaten;
    //                 if ($provinsi)  $lokasi[] = $provinsi;
    
    //                 return implode(', ', $lokasi);
    //             }
    //         }
    //     } catch (\Exception $e) {
    //         return "Lokasi tidak tersedia";
    //     }
    
    //     return "Lokasi tidak tersedia";
    // }    
}