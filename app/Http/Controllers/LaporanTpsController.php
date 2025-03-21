<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanTps;
use App\Models\Petugas;

class LaporanTpsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $laporan = LaporanTps::with('petugas')->get();
        return response()->json($laporan);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_petugas' => 'required|exists:petugas,id',
            'total_sampah' => 'required|numeric',
            'deskripsi' => 'required|string',
            'tanggal_pengangkutan' => 'nullable|date',
            'status' => 'integer|in:0,1',
        ]);

        $laporan = LaporanTps::create($request->all());
        return response()->json($laporan, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $laporan = LaporanTps::with('petugas')->findOrFail($id);
        return response()->json($laporan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_petugas' => 'exists:petugas,id',
            'total_sampah' => 'numeric',
            'deskripsi' => 'string',
            'tanggal_pengangkutan' => 'nullable|date',
            'status' => 'integer|in:0,1',
        ]);

        $laporan = LaporanTps::findOrFail($id);
        $laporan->update($request->all());

        return response()->json($laporan);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $laporan = LaporanTps::findOrFail($id);
        $laporan->delete();

        return response()->json(['message' => 'Laporan TPS deleted successfully']);
    }
}
