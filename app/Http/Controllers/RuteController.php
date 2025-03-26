<?php

namespace App\Http\Controllers;

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
        try {
            return response()->json(Rute::all(), 200);
        } catch (Exception $e) {
            return response()->json(["error" => "Gagal mengambil data rute", "message" => $e->getMessage()], 500);
        }
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
