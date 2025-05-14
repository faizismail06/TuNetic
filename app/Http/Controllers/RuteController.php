<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Models\Rute;
use App\Models\LokasiTps;
use App\Models\RuteTps;
use Illuminate\Http\Request;
use Exception;

class RuteController extends Controller
{
    /**
     * Menampilkan semua rute.
     */
    public function index()
    {
        $rute = Rute::all();

        // foreach ($rute as $item) {
        //     if ($item->latitude && $item->longitude) {
        //         $item->alamat = $this->getLocationName($item->latitude, $item->longitude);
        //     } else {
        //         $item->alamat = '-';
        //     }

            // // Ambil tanggal dari relasi jadwal paling awal atau terakhir (tergantung kebutuhan)
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
                'tps.*' => 'required|exists:lokasi_tps,id', // validasi dropdown TPS
            ]);

            $rute = Rute::create([
                'nama_rute' => $request->nama_rute,
                'wilayah' => $request->wilayah,
            ]);

            foreach ($request->tps as $id_lokasi_tps) {
                RuteTps::create([
                    'id_rute' => $rute->id,
                    'id_lokasi_tps' => $id_lokasi_tps,
                ]);
            }
            // return response()->json($rute, 201);
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
            // $rute->alamat = $this->getLocationName($rute->latitude, $rute->longitude);
            // return response()->json($rute, 200);

            return view('adminpusat.manage-rute.detail', compact('id'));
        } catch (Exception $e) {
            return response()->json(["error" => "Rute tidak ditemukan", "message" => $e->getMessage()], 404);
        }
    }

    public function getDetailJson($id)
    {
        $rute = Rute::with(['ruteTps.lokasi_tps'])->findOrFail($id);
        $lokasiList = $rute->ruteTps->pluck('lokasi_tps.nama_lokasi')->toArray();

        $response = [
            'TPS1' => $lokasiList[0] ?? '-',
            'TPS2' => $lokasiList[1] ?? '-',
            'TPS3' => $lokasiList[2] ?? '-',
            'TPST' => $lokasiList[3] ?? '-',
            'TPA'  => $lokasiList[4] ?? '-',
        ];

        return response()->json($response);
    }

    public function edit($id)
    {
        $rute = Rute::findOrFail($id);
        return view('adminpusat.manage-rute.edit', compact('rute'));
    }

    /**
     * Mengupdate rute berdasarkan ID.
     */
    public function update(Request $request, $id)
    {
        try {
            $rute = Rute::findOrFail($id);

            $validatedData = $request->validate([
                'nama_rute' => 'sometimes|string|max:255',
                'latitude' => 'sometimes|numeric|between:-90,90',
                'longitude' => 'sometimes|numeric|between:-180,180',
                'wilayah' => 'sometimes|string',
            ]);

            $rute->update($validatedData);

            return response()->json([
                'message' => 'Rute berhasil diperbarui',
                'data' => $rute
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal mengupdate rute',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

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

    /**
     * Mengubah latitude dan longitude menjadi alamat jalan.
     */
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