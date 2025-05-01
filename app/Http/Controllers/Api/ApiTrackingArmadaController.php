<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TrackingArmada;
use App\Models\JadwalOperasional;
use Illuminate\Http\Request;

class ApiTrackingArmadaController extends Controller
{
    /**
     * Mendapatkan data tracking armada untuk ditampilkan di peta
     */
    public function getTrackingData(Request $request)
    {
        // Filter berdasarkan id jadwal jika ada
        $filterJadwal = $request->input('filter_jadwal', 'all');

        $query = TrackingArmada::with([
                'jadwalOperasional.armada',
                'jadwalOperasional.jadwal',
                'jadwalOperasional.ruteTps.rute'
            ])
            ->join('jadwal_operasional', 'tracking_armada.id_jadwal_operasional', '=', 'jadwal_operasional.id')
            ->where('jadwal_operasional.status', 1); // Hanya armada yang sedang berjalan

        // Jika filter jadwal diberikan dan bukan 'all'
        if ($filterJadwal !== 'all') {
            $query->whereHas('jadwalOperasional', function($q) use ($filterJadwal) {
                $q->where('id_jadwal', $filterJadwal);
            });
        }

        // Batasi data tracking ke 30 titik terakhir per jadwal operasional untuk efisiensi
        $trackingData = $query->orderBy('tracking_armada.timestamp', 'desc')
            ->get()
            ->groupBy('id_jadwal_operasional')
            ->map(function ($group) {
                return $group->take(30)->sortBy('timestamp')->values();
            })
            ->flatten(1);

        return response()->json($trackingData);
    }

    /**
     * Menyimpan data tracking baru dari perangkat
     */
    public function storeTrackingData(Request $request)
    {
        $request->validate([
            'id_jadwal_operasional' => 'required|exists:jadwal_operasional,id',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'timestamp' => 'sometimes|date',
        ]);

        // Periksa apakah jadwal sedang berjalan
        $jadwal = JadwalOperasional::find($request->id_jadwal_operasional);
        if ($jadwal->status != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal operasional tidak dalam status sedang berjalan'
            ], 400);
        }

        $tracking = TrackingArmada::create([
            'id_jadwal_operasional' => $request->id_jadwal_operasional,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'timestamp' => $request->timestamp ?? now(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $tracking
        ], 201);
    }
}
