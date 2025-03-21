<?php

namespace App\Http\Controllers;

use App\Models\Sampah;
use App\Models\LokasiTps;
use Illuminate\Http\Request;

class SampahController extends Controller
{
    /**
     * Menampilkan semua data sampah
     */
    public function index()
    {
        $sampah = Sampah::with('lokasi')->get();
        return response()->json($sampah);
    }

    /**
     * Menyimpan data sampah baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_laporan_tps' => 'required|exists:laporan_tps,id',
            'berat' => 'required|numeric|min:0',
            'tanggal_pengangkutan' => 'required|date',
            'status' => 'required|in:Belum Diangkut,Sedang Diangkut,Telah Diangkut',
        ]);

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
        $sampah = Sampah::with('lokasi')->findOrFail($id);
        return response()->json($sampah);
    }

    /**
     * Memperbarui data sampah
     */
    public function update(Request $request, $id)
    {
        $sampah = Sampah::findOrFail($id);

        $request->validate([
            'id_laporan_tps' => 'sometimes|exists:laporan_tps,id',
            'berat' => 'sometimes|numeric|min:0',
            'tanggal_pengangkutan' => 'sometimes|date',
            'status' => 'sometimes|in:Belum Diangkut,Sedang Diangkut,Telah Diangkut',
        ]);

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
        $sampah->delete();

        return response()->json([
            'message' => 'Data sampah berhasil dihapus!'
        ]);
    }
}
