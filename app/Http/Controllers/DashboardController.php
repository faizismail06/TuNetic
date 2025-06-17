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

        $kategoriList = ['Tumpukan sampah', 'TPS Penuh', 'Bau Menyengat', 'Pembuangan Liar', 'Lainnya'];

        // Query: Hitung jumlah laporan untuk setiap kategori
        $laporanByKategori = DB::table('laporan_warga')
            ->select('kategori', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('kategori')
            ->get();

        $totalLaporan = $laporanByKategori->sum('jumlah');
        $kategoriMasalah = collect();

        // Loop kategoriList agar urutan tetap konsisten
        foreach ($kategoriList as $kategoriName) {
            // Cari data kategori yang sudah ada di query
            $laporan = $laporanByKategori->firstWhere('kategori', $kategoriName);

            $jumlah = $laporan ? $laporan->jumlah : 0;
            $persentase = $totalLaporan > 0 ? round($jumlah * 100 / $totalLaporan, 2) : 0;

            $kategoriMasalah->push((object)[
                'nama' => $kategoriName,
                'jumlah' => $jumlah,
                'persentase' => $persentase,
            ]);
        }

        // Urutkan berdasarkan persentase
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