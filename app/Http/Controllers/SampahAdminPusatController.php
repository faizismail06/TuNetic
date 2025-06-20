<?php

namespace App\Http\Controllers;

use App\Models\LaporanTps;
use Illuminate\Http\Request;

class SampahAdminPusatController extends Controller
{
    /**
     * Menampilkan halaman index untuk admin pusat
     */
    public function index()
    {
        $LaporanTps = LaporanTps::with(['jadwalOperasional.armada', 'jadwalOperasional.rute'])
            ->orderByDesc('tanggal_pengangkutan')
            ->get();

        return view('adminpusat.perhitungan-sampah.index', compact('LaporanTps'));
    }

    /**
     * Tampilkan form tambah data
     */
    public function create()
    {
        return view('adminpusat.perhitungan-sampah.create');
    }

    /**
     * Simpan data laporan sampah
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_jadwal_operasional' => 'required|exists:jadwal_operasional,id',
            'total_sampah' => 'required|numeric|min:0',
            'tanggal_pengangkutan' => 'required|date',
            'deskripsi' => 'nullable|string'
        ]);

        LaporanTps::create($request->all());

        return redirect()->route('adminpusat.perhitungan-sampah.index')
            ->with('success', 'Data sampah berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit
     */
    public function edit($id)
    {
        $laporan = LaporanTps::findOrFail($id);
        return view('adminpusat.perhitungan-sampah.edit', compact('laporan'));
    }

    /**
     * Update data
     */
    public function update(Request $request, $id)
    {
        $laporan = LaporanTps::findOrFail($id);

        $request->validate([
            'id_jadwal_operasional' => 'required|exists:jadwal_operasional,id',
            'total_sampah' => 'required|numeric|min:0',
            'tanggal_pengangkutan' => 'required|date',
            'deskripsi' => 'nullable|string'
        ]);

        $laporan->update($request->all());

        return redirect()->route('adminpusat.perhitungan-sampah.index')
            ->with('success', 'Data sampah berhasil diperbarui.');
    }

    /**
     * Hapus data
     */
    public function destroy($id)
    {
        $laporan = LaporanTps::findOrFail($id);
        $laporan->delete();

        return redirect()->route('adminpusat.perhitungan-sampah.index')
            ->with('success', 'Data sampah berhasil dihapus.');
    }
}
