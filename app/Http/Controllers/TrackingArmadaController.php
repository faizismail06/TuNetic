<?php

namespace App\Http\Controllers;

use App\Models\TrackingArmada;
use Illuminate\Http\Request;

class TrackingArmadaController extends Controller
{
    /**
     * Tampilkan daftar tracking armada.
     */
    public function index()
    {
        $trackingData = TrackingArmada::with('jadwalOperasional')->latest()->get();
        return view('tracking_armada.index', compact('trackingData'));
    }

    /**
     * Simpan data tracking armada baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_jadwal_operasional' => 'required|exists:jadwal_operasional,id',
            'timestamp' => 'required|date',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        TrackingArmada::create($request->all());

        return redirect()->route('tracking-armada.index')->with('success', 'Tracking Armada berhasil ditambahkan.');
    }

    /**
     * Hapus data tracking armada.
     */
    public function destroy(TrackingArmada $trackingArmada)
    {
        $trackingArmada->delete();

        return redirect()->route('tracking-armada.index')->with('success', 'Tracking Armada berhasil dihapus.');
    }
}
