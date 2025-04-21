<?php

namespace App\Http\Controllers;

use App\Models\JadwalOperasional;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;

class JadwalOperasionalController extends Controller
{
    /**
     * Menampilkan semua jadwal operasional.
     */
    public function index()
    {
        $jadwal = JadwalOperasional::with(['armada', 'jadwal', 'ruteTps'])->get();
        return response()->json($jadwal, 200);
    }

    /**
     * Menyimpan jadwal operasional baru.
     */
    public function store(Request $request)
    {
        try {
            // Validasi input
            $validatedData = $request->validate([
                'id_armada' => 'required|exists:armada,id',
                'id_jadwal' => 'required|exists:jadwal,id',
                'id_rute_tps' => 'required|exists:rute_tps,id',
                'jam_aktif' => 'required|date_format:H:i:s',
                'status' => 'required|integer|in:0,1,2', // 0 = Belum berjalan, 1 = Sedang Berjalan, 2 = Selesai

            ]);

            // Simpan ke database
            $jadwal = JadwalOperasional::create($validatedData);

            // Response sukses
            return response()->json([
                'message' => 'Jadwal operasional berhasil disimpan!',
                'data' => $jadwal
            ], 201);

        } catch (ValidationException $e) {
            // Jika validasi gagal, tampilkan error
            return response()->json([
                'message' => 'Validasi gagal!',
                'errors' => $e->errors()
            ], 422);

        } catch (Exception $e) {
            // Jika ada error lainnya, tampilkan pesan error
            return response()->json([
                'message' => 'Terjadi kesalahan!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan jadwal operasional berdasarkan ID.
     */
    public function show($id)
    {
        $jadwal = JadwalOperasional::with(['armada', 'jadwal', 'ruteTps'])->findOrFail($id);
        return response()->json($jadwal, 200);
    }

    /**
     * Memperbarui jadwal operasional.
     */
    public function update(Request $request, $id)
    {
        try {
            $jadwal = JadwalOperasional::findOrFail($id);

            $validatedData = $request->validate([
                'id_armada' => 'sometimes|exists:armada,id',
                'id_jadwal' => 'sometimes|exists:jadwal,id',
                'id_rute_tps' => 'sometimes|exists:rute_tps,id',
                'jam_aktif' => 'sometimes|date_format:H:i:s',
                'status' => 'sometimes|integer|in:0,1,2', // 0 = Belum berjalan, 1 = Sedang Berjalan, 2 = Selesai
            ]);

            $jadwal->update($validatedData);

            return response()->json([
                'message' => 'Jadwal operasional berhasil diperbarui!',
                'data' => $jadwal
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal!',
                'errors' => $e->errors()
            ], 422);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menghapus jadwal operasional.
     */
    public function destroy($id)
    {
        try {
            $jadwal = JadwalOperasional::findOrFail($id);
            $jadwal->delete();

            return response()->json(["message" => "Jadwal operasional berhasil dihapus"], 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus!',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
