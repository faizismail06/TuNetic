<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $countWarga = DB::table('users')->where('level','4')->count();
        $countPetugas = DB::table('petugas')->count();
        $countArmada = DB::table('armada')->count();
        $countLaporan = DB::table('laporan_warga')->count();

        $pengangkutanData = DB::table('tracking_armada')
        ->select(
            'armada.no_polisi',
            'lokasi_tps.id as tps_id',
            DB::raw('COUNT(*) as jumlah_pengangkutan')
        )
        ->join('jadwal_operasional', 'tracking_armada.id_jadwal_operasional', '=', 'jadwal_operasional.id')
        ->join('armada', 'jadwal_operasional.id_armada', '=', 'armada.id')
        ->join('rute', 'jadwal_operasional.id_rute', '=', 'rute.id')
        ->join('rute_tps', 'rute.id', '=', 'rute_tps.id_rute')
        ->join('lokasi_tps', 'rute_tps.id_lokasi_tps', '=', 'lokasi_tps.id')
        ->where('tracking_armada.created_at', '>=', now()->subDays(7))
        ->groupBy('armada.no_polisi', 'lokasi_tps.id')
        ->get();

        $nomorKendaraan = $pengangkutanData->pluck('no_polisi')->unique()->values()->take(5);
        $tpsLocations = DB::table('lokasi_tps')->select('id', 'nama_lokasi')->take(5)->get();

        $chartData = [];
        foreach ($tpsLocations as $index => $tps) {
            $tpsData = [];
            foreach ($nomorKendaraan as $kendaraan) {
                $jumlah = $pengangkutanData
                    ->where('no_polisi', $kendaraan)
                    ->where('tps_id', $tps->id)
                    ->first();
                $tpsData[] = $jumlah ? $jumlah->jumlah_pengangkutan : 0;
            }
            $chartData[] = [
                'label' => 'TPST ' . ($index + 1),
                'data' => $tpsData,
            ];
        }

        $kategoriList = ['Tumpukan sampah', 'TPS Penuh', 'Bau Menyengat', 'Pembuangan Liar', 'Lainnya'];
        $laporanByStatus = DB::table('laporan_warga')
            ->select('status', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('status')
            ->get();

        $totalLaporan = $laporanByStatus->sum('jumlah');
        $kategoriMasalah = collect();
        foreach ($laporanByStatus as $index => $laporan) {
            $kategoriMasalah->push((object)[
                'nama' => $kategoriList[$index % count($kategoriList)],
                'jumlah' => $laporan->jumlah,
                'persentase' => round($laporan->jumlah * 100 / max($totalLaporan, 1), 2),
            ]);
        }

        for ($i = $kategoriMasalah->count(); $i < 5; $i++) {
            $kategoriMasalah->push((object)[
                'nama' => $kategoriList[$i],
                'jumlah' => 0,
                'persentase' => 0
            ]);
        }

        if ($kategoriMasalah->sum('persentase') < 100 && $kategoriMasalah->count() > 0) {
            $kategoriMasalah[0]->persentase += 100 - $kategoriMasalah->sum('persentase');
        }

        $kategoriMasalah = $kategoriMasalah->sortByDesc('persentase')->values();

        $daftarTPS = DB::table('lokasi_tps')->select('nama_lokasi')->orderBy('nama_lokasi')->get();
        $halfCount = ceil($daftarTPS->count() / 2);
        $tpsKolom1 = $daftarTPS->take($halfCount);
        $tpsKolom2 = $daftarTPS->slice($halfCount);

        return view('adminpusat.dashboard.index', compact(
            'countWarga', 'countPetugas', 'countArmada', 'countLaporan',
            'nomorKendaraan', 'chartData',
            'kategoriMasalah', 'tpsKolom1', 'tpsKolom2'
        ));
    }
}