<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon; // Import Carbon for date handling clarity

class DashboardController extends Controller
{
    public function index()
    {
        $countWarga = DB::table('users')->where('level', '4')->count();
        $countPetugas = DB::table('petugas')->count();
        $countArmada = DB::table('armada')->count();
        $countLaporan = DB::table('laporan_warga')->count();

        // Updated query for $pengangkutanData
        $pengangkutanData = DB::table('jadwal_operasional') // Base table is now jadwal_operasional
            ->select(
                'armada.no_polisi',
                'lokasi_tps.id as tps_id',
                DB::raw('COUNT(jadwal_operasional.id) as jumlah_pengangkutan') // Count completed operations
            )
            ->join('armada', 'jadwal_operasional.id_armada', '=', 'armada.id')
            ->join('rute', 'jadwal_operasional.id_rute', '=', 'rute.id')
            ->join('rute_tps', 'rute.id', '=', 'rute_tps.id_rute') // Links a route to potentially multiple TPS
            ->join('lokasi_tps', 'rute_tps.id_lokasi_tps', '=', 'lokasi_tps.id')
            ->where('jadwal_operasional.status', 1) // Filter for 'Selesai' status
            // Filter for the last 7 days (today and the 6 previous days)
            // Assumes 'jadwal_operasional.tanggal' is a DATE or DATETIME column
            ->where('jadwal_operasional.tanggal', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->where('jadwal_operasional.tanggal', '<=', Carbon::now()->endOfDay())
            ->groupBy('armada.no_polisi', 'lokasi_tps.id')
            ->get();

        $nomorKendaraan = $pengangkutanData->pluck('no_polisi')->unique()->values()->take(5);
        // Fetching TPS locations for chart labels and iteration
        // 1. Tambahkan ->where('tipe', 'TPS') pada query Anda
        $tpsLocations = DB::table('lokasi_tps')
                        ->where('tipe', 'TPST') // <-- Tambahkan baris ini untuk filter
                        ->select('id', 'nama_lokasi', 'tipe') // Ambil kolom yang relevan
                        ->take(5) // Ambil 5 data pertama (jika masih diperlukan)
                        ->get();

        $chartData = [];
        // Pastikan $nomorKendaraan dan $pengangkutanData sudah didefinisikan sebelumnya

        foreach ($tpsLocations as $tps) {
            // Karena kita sudah memfilter di query, semua $tps di sini
            // PASTI memiliki tipe 'TPS'.

            $tpsData = [];
            foreach ($nomorKendaraan as $kendaraan) {
                $jumlah = $pengangkutanData
                    ->where('no_polisi', $kendaraan)
                    ->where('tps_id', $tps->id)
                    ->first();
                $tpsData[] = $jumlah ? $jumlah->jumlah_pengangkutan : 0;
            }

            // 2. Gunakan $tps->nama_lokasi secara langsung untuk label
            $chartData[] = [
                'label' => $tps->nama_lokasi, 
                'data' => $tpsData,
            ];
        }

        // --- The rest of your controller logic remains the same ---
        $kategoriList = ['Tumpukan sampah', 'TPS Penuh', 'Bau Menyengat', 'Pembuangan Liar', 'Lainnya'];
        $laporanByStatus = DB::table('laporan_warga')
            ->select('status', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('status')
            ->get();

        $totalLaporan = $laporanByStatus->sum('jumlah');
        $kategoriMasalah = collect();
        // Ensure $laporanByStatus is not empty before trying to access $kategoriList with its index
        if ($laporanByStatus->isNotEmpty()) {
            foreach ($laporanByStatus as $index => $laporan) {
                 // Make sure index for kategoriList is within bounds
                $kategoriIndex = $laporan->status % count($kategoriList); // Assuming status maps to kategoriList indices
                // Or, if status values are not direct indices, you might need a mapping:
                // Example mapping: $statusToKategori = [0 => 'Tumpukan Sampah', 1 => 'TPS Penuh', ...];
                // $namaKategori = $statusToKategori[$laporan->status] ?? 'Lainnya';

                $kategoriMasalah->push((object)[
                    // Ensure this mapping is correct for your 'laporan_warga.status' values
                    'nama' => $kategoriList[$kategoriIndex],
                    'jumlah' => $laporan->jumlah,
                    'persentase' => round($laporan->jumlah * 100 / max($totalLaporan, 1), 2),
                ]);
            }
        }


        // This logic for filling up kategoriMasalah might need review based on how status maps to kategoriList
        // The original loop might not populate all categories if some statuses have no reports.
        $existingKategoriNames = $kategoriMasalah->pluck('nama');
        foreach($kategoriList as $kategoriName) {
            if (!$existingKategoriNames->contains($kategoriName)) {
                $kategoriMasalah->push((object)[
                    'nama' => $kategoriName,
                    'jumlah' => 0,
                    'persentase' => 100,
                ]);
            }
        }
        
        // Recalculate totalLaporan if you added new categories with 0, though for persentase it's based on reported counts.
        // The persentase adjustment logic might need re-evaluation if categories are dynamically added.
        // It's generally better to ensure all percentages are derived consistently.
        // Forcing sum to 100 by adjusting one category can be misleading. Consider if this is required.
        // if ($kategoriMasalah->sum('persentase') < 100 && $kategoriMasalah->count() > 0 && $totalLaporan > 0) {
        //    $diff = 100 - $kategoriMasalah->sum('persentase');
        //    // Find a suitable item to adjust, or distribute the difference. Adjusting first item:
        //    $kategoriMasalah = $kategoriMasalah->map(function($item, $idx) use ($diff) {
        //        if ($idx === 0) {
        //            $item->persentase += $diff;
        //        }
        //        return $item;
        //    });
        // }


        $kategoriMasalah = $kategoriMasalah->sortByDesc('persentase')->values();

        $daftarTPS = DB::table('lokasi_tps')
                        ->where('tipe', 'TPS') // <-- Tambahkan baris ini untuk filter
                        ->select('id', 'nama_lokasi', 'tipe') // Ambil kolom yang relevan
                        // ->take(5) // Ambil 5 data pertama (jika masih diperlukan)
                        ->get();
        $halfCount = ceil($daftarTPS->count() / 2);
        $tpsKolom1 = $daftarTPS->take($halfCount);
        $tpsKolom2 = $daftarTPS->slice($halfCount);

        return view('adminpusat.dashboard.index', compact(
            'countWarga', 'countPetugas', 'countArmada', 'countLaporan',
            'nomorKendaraan', 'chartData', // chartData now uses nama_lokasi for labels
            'kategoriMasalah', 'tpsKolom1', 'tpsKolom2'
        ));
    }

    public function indexTpst() {

        $daftarTPST = DB::table('lokasi_tps')
                        ->where('tipe', 'TPST') // <-- Tambahkan baris ini untuk filter
                        ->select('id', 'nama_lokasi', 'tipe') // Ambil kolom yang relevan
                        // ->take(5) // Ambil 5 data pertama (jika masih diperlukan)
                        ->get();
        $halfCount = ceil($daftarTPST->count() / 2);
        $tpsKolom1 = $daftarTPST->take($halfCount);
        $tpsKolom2 = $daftarTPST->slice($halfCount);

        return view('admintpst.dashboard.index', compact(
            'tpsKolom1', 'tpsKolom2'
        ));

    }
}