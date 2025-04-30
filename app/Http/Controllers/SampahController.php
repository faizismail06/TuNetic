<?php

namespace App\Http\Controllers;

use App\Models\Sampah;
use Illuminate\Http\Request;

class SampahController extends Controller
{
    /**
     * Menampilkan semua data sampah
     */
    public function index()
    {
        // Menggunakan eager loading untuk mengambil data sampah beserta data laporan_tps
        $sampah = Sampah::with('laporantps')->get();

        return response()->json($sampah);
    }

    /**
     * Menyimpan data sampah baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_laporan_tps' => 'required|exists:laporan_tps,id', // Validasi untuk relasi ke laporan_tps
            'berat' => 'required|numeric|min:0',
            'tanggal_pengangkutan' => 'required|date',
            'status' => 'required|in:Belum Diangkut,Sedang Diangkut,Telah Diangkut',
        ]);

        // Menyimpan data sampah baru
        $sampah = Sampah::create($request->all());

        return response()->json([
            'message' => 'Data sampah berhasil ditambahkan!',
            'data' => $sampah
        ], 201);
    }

    /**
     * Menampilkan detail sampah berdasarkan ID
     */
    public function show($id)
    {
        // Menampilkan data sampah berdasarkan ID dengan eager loading untuk relasi laporantps
        $sampah = Sampah::with('laporantps')->findOrFail($id);

        return response()->json($sampah);
    }

    /**
     * Memperbarui data sampah
     */
    public function update(Request $request, $id)
    {
        $sampah = Sampah::findOrFail($id);

        // Validasi input yang akan diperbarui
        $request->validate([
            'id_laporan_tps' => 'sometimes|exists:laporan_tps,id', // Validasi untuk relasi ke laporan_tps
            'berat' => 'sometimes|numeric|min:0',
            'tanggal_pengangkutan' => 'sometimes|date',
            'status' => 'sometimes|in:Belum Diangkut,Sedang Diangkut,Telah Diangkut',
        ]);

        // Memperbarui data sampah
        $sampah->update($request->all());

        return response()->json([
            'message' => 'Data sampah berhasil diperbarui!',
            'data' => $sampah
        ]);
    }

    /**
     * Menghapus data sampah
     */
    public function destroy($id)
    {
        $sampah = Sampah::findOrFail($id);

        // Menghapus data sampah
        $sampah->delete();

        return response()->json([
            'message' => 'Data sampah berhasil dihapus!'
        ]);
    }
}
