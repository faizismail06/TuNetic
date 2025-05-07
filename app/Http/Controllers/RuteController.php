<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
// use App\Http\Controllers\Log;
use App\Models\Rute;
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
        // return view('adminpusat.manage-rute.index', compact('rute'));
        foreach ($rute as $item) {
            if ($item->alamat_laporan) {
                $item->alamat = $item->alamat_laporan;
            } else {
                $koordinat = json_decode($item->map, true);
                if (is_array($koordinat) && count($koordinat) > 0) {
                    $lat = $koordinat[0]['lat'];
                    $lng = $koordinat[0]['lng'];
    
                    try {
                        // Reverse geocoding ke OpenStreetMap
                        $response = Http::get('https://nominatim.openstreetmap.org/reverse', [
                            'format' => 'json',
                            'lat' => $lat,
                            'lon' => $lng,
                            'zoom' => 18,
                            'addressdetails' => 1
                        ]);
        
                        if ($response->ok() && isset($response['display_name'])) {
                            $alamat = $response['display_name'];
                            $item->alamat = $alamat;

                            // Simpan agar tidak panggil API lagi
                            // Log::info('Saving alamat_laporan untuk rute ID: ' . $item->id . ', alamat: ' . $alamat);
                            $item->alamat_laporan = $alamat;
                            $item->save();
                        } else {
                            $item->alamat = '-';
                        }
                    } catch  (\Exception $e) {
                        $item->alamat = '-';
                    }
                } else {
                    $item->alamat = '-';
                }
            }
        }

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
                'map' => 'required|array', // Ubah dari json menjadi array
                'wilayah' => 'required|string', // Pastikan string, bukan json
            ]);

            // Ubah array menjadi JSON string sebelum disimpan ke database
            $validatedData['map'] = json_encode($validatedData['map']);

            $rute = Rute::create($validatedData);

            return response()->json($rute, 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal menyimpan rute',
                'message' => $e->getMessage(),
            ], 400);
        }
    }


    /**
     * Menampilkan detail rute berdasarkan ID.
     */
    public function show($id)
    {
        try {
            $rute = Rute::findOrFail($id);
            return response()->json($rute, 200);
        } catch (Exception $e) {
            return response()->json(["error" => "Rute tidak ditemukan", "message" => $e->getMessage()], 404);
        }
    }

    /**
     * Mengupdate rute berdasarkan ID.
     */
    public function update(Request $request, $id)
    {
        try {
            $rute = Rute::findOrFail($id); // Cari rute berdasarkan ID, jika tidak ditemukan akan return 404

            $validatedData = $request->validate([
                'nama_rute' => 'sometimes|string|max:255',
                'map' => 'sometimes|array', // Pastikan map dikirim sebagai array
                'wilayah' => 'sometimes|string', // Wilayah tetap berupa string
            ]);

            // Ubah array menjadi JSON sebelum menyimpan ke database
            if (isset($validatedData['map'])) {
                $validatedData['map'] = json_encode($validatedData['map']);
            }

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
            $rute->delete();

            return response()->json(["message" => "Rute berhasil dihapus"], 200);
        } catch (Exception $e) {
            return response()->json(["error" => "Gagal menghapus rute", "message" => $e->getMessage()], 500);
        }
    }
}
