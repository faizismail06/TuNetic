<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    /**
     * Menampilkan semua data jadwal.
     */
    public function index()
    {
        try {
            $jadwal = Jadwal::all();
            return response()->json($jadwal, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal mengambil data jadwal',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Menyimpan jadwal baru.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'tanggal' => 'required|date',
                'hari' => 'required|string|max:20',
                'status' => 'required|integer|in:0,1', // 0 = tidak aktif, 1 = aktif
            ]);

            $jadwal = Jadwal::create($validatedData);

            return response()->json([
                'message' => 'Jadwal berhasil ditambahkan',
                'data' => $jadwal
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal menambahkan jadwal',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Menampilkan detail jadwal berdasarkan ID.
     */
    public function show($id)
    {
        try {
            $jadwal = Jadwal::findOrFail($id);
            return response()->json($jadwal, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Jadwal tidak ditemukan',
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Mengupdate data jadwal berdasarkan ID.
     */
    public function update(Request $request, $id)
    {
        try {
            $jadwal = Jadwal::findOrFail($id);

            $validatedData = $request->validate([
                'tanggal' => 'sometimes|date',
                'hari' => 'sometimes|string|max:20',
                'status' => 'sometimes|integer|in:0,1',
            ]);

            $jadwal->update($validatedData);

            return response()->json([
                'message' => 'Jadwal berhasil diperbarui',
                'data' => $jadwal
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal memperbarui jadwal',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Menghapus jadwal berdasarkan ID.
     */
    public function destroy($id)
    {
        try {
            $jadwal = Jadwal::findOrFail($id);
            $jadwal->delete();

            return response()->json([
                'message' => 'Jadwal berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal menghapus jadwal',
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
