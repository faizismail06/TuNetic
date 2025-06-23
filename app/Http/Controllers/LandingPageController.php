<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class LandingPageController extends Controller
{
    public function index()
    {
        // Ambil 3 artikel aktif terbaru
        $articles = Artikel::where('status', 1)
            ->orderBy('tanggal_publikasi', 'desc')
            ->limit(3)
            ->get();

        // Format tanggal dan URL gambar
        $articles->transform(function ($artikel) {
            $artikel->tanggal_formatted = Carbon::parse($artikel->tanggal_publikasi)
                ->locale('id')
                ->isoFormat('dddd, D MMMM Y');
            $artikel->gambar_url = $this->getImageUrl($artikel);
            return $artikel;
        });

        // Tambahan statistik
        $jumlahPengguna = DB::table('users')->count();
        $jumlahSampah = DB::table('laporan_tps')->pluck('total_sampah')->sum(); // ganti 'berat' jika pakai volume
        $jumlahTpsAktif = DB::table('lokasi_tps')->count();
        $namaTpsAktif = DB::table('lokasi_tps')->pluck('nama_lokasi');
        $daftar_tps = DB::table('lokasi_tps')->paginate(6); // atau pakai get() kalau tidak mau paginate


        return view('landing-page', compact(
            'articles',
            'jumlahPengguna',
            'jumlahSampah',
            'jumlahTpsAktif',
            'namaTpsAktif',
            'daftar_tps',
        ));
    }

    public function daftar_tps()
    {
        // Ambil semua TPS yang ada
        $tps = DB::table('lokasi_tps')->paginate(6); // Atau gunakan get() jika tidak perlu paginate

        return view('tps.index', compact('tps'));
    }

    private function getImageUrl($artikel)
    {
        if ($artikel->gambar && Storage::exists('public/' . $artikel->gambar)) {
            return asset($artikel->gambar);
        }
        return asset('assets/images/default-article.jpg');
    }
}
