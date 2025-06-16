<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DashboardArtikelController extends Controller
{
    /**
     * Menampilkan artikel untuk dashboard
     * Hanya menampilkan artikel dengan status aktif (1)
     */
    public function index()
    {
        // Ambil artikel yang statusnya aktif, diurutkan berdasarkan tanggal publikasi terbaru
        $artikels = Artikel::where('status', 1)
                          ->orderBy('tanggal_publikasi', 'desc')
                          ->get();

        return view('dashboard.artikel.index', compact('artikels'));
    }

    /**
     * Menampilkan artikel terbaru untuk dashboard (limited)
     * Berguna untuk widget atau section artikel terbaru di landing page
     */
    public function recent($limit = 3)
    {
        $artikels = Artikel::where('status', 1)
                          ->orderBy('tanggal_publikasi', 'desc')
                          ->limit($limit)
                          ->get();

        // Format tanggal untuk display
        $artikels->transform(function ($artikel) {
            $artikel->tanggal_formatted = Carbon::parse($artikel->tanggal_publikasi)
                                               ->locale('id')
                                               ->isoFormat('dddd, D MMMM Y');
            $artikel->gambar_url = $this->getImageUrl($artikel);
            return $artikel;
        });

        return view('dashboard.artikel.recent', compact('artikels'));
    }

    /**
     * Menampilkan detail artikel untuk dashboard
     */
    public function show($id)
    {
        $artikel = Artikel::where('id', $id)
                         ->where('status', 1) // Pastikan artikel aktif
                         ->first();

        if (!$artikel) {
            abort(404, 'Artikel tidak ditemukan atau tidak aktif');
        }

        // Format tanggal dan gambar
        $artikel->tanggal_formatted = Carbon::parse($artikel->tanggal_publikasi)
                                           ->locale('id')
                                           ->isoFormat('dddd, D MMMM Y');
        $artikel->gambar_url = $this->getImageUrl($artikel);

        return view('dashboard.artikel.show', compact('artikel'));
    }

    /**
     * Menampilkan artikel berdasarkan slug (jika ada kolom slug)
     * Alternatif untuk show dengan parameter yang lebih SEO friendly
     */
    public function showBySlug($slug)
    {
        // Jika model Artikel memiliki kolom slug
        $artikel = Artikel::where('slug', $slug)
                         ->where('status', 1)
                         ->first();

        if (!$artikel) {
            abort(404, 'Artikel tidak ditemukan');
        }

        // Format tanggal dan gambar
        $artikel->tanggal_formatted = Carbon::parse($artikel->tanggal_publikasi)
                                           ->locale('id')
                                           ->isoFormat('dddd, D MMMM Y');
        $artikel->gambar_url = $this->getImageUrl($artikel);

        return view('dashboard.artikel.show', compact('artikel'));
    }

    /**
     * API untuk mendapatkan data artikel (JSON response)
     * Berguna untuk AJAX requests atau API calls
     */
    public function api(Request $request)
    {
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 6);
        $skip = ($page - 1) * $perPage;

        $artikels = Artikel::where('status', 1)
                          ->orderBy('tanggal_publikasi', 'desc')
                          ->skip($skip)
                          ->take($perPage)
                          ->select([
                              'id',
                              'judul_artikel',
                              'deskripsi_singkat',
                              'gambar',
                              'link_artikel',
                              'tanggal_publikasi'
                          ])
                          ->get();

        // Transform data untuk API response
        $artikels = $artikels->map(function ($artikel) {
            return [
                'id' => $artikel->id,
                'judul' => $artikel->judul_artikel,
                'deskripsi' => $artikel->deskripsi_singkat,
                'gambar_url' => $this->getImageUrl($artikel),
                'link' => $artikel->link_artikel,
                'tanggal_publikasi' => $artikel->tanggal_publikasi,
                'tanggal_formatted' => Carbon::parse($artikel->tanggal_publikasi)
                                            ->locale('id')
                                            ->isoFormat('dddd, D MMMM Y')
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $artikels,
            'has_more' => $artikels->count() === $perPage
        ]);
    }

    /**
     * Pencarian artikel untuk dashboard
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (empty($query)) {
            return redirect()->route('dashboard.artikel.index');
        }

        $artikels = Artikel::where('status', 1)
                          ->where(function($q) use ($query) {
                              $q->where('judul_artikel', 'LIKE', "%{$query}%")
                                ->orWhere('deskripsi_singkat', 'LIKE', "%{$query}%");
                          })
                          ->orderBy('tanggal_publikasi', 'desc')
                          ->get();

        // Format data untuk display
        $artikels->transform(function ($artikel) {
            $artikel->tanggal_formatted = Carbon::parse($artikel->tanggal_publikasi)
                                               ->locale('id')
                                               ->isoFormat('dddd, D MMMM Y');
            $artikel->gambar_url = $this->getImageUrl($artikel);
            return $artikel;
        });

        return view('dashboard.artikel.index', compact('artikels', 'query'));
    }

    /**
     * Widget artikel untuk dashboard
     * Menampilkan artikel dalam format widget/card untuk landing page
     */
    public function widget($count = 3)
    {
        $artikels = Artikel::where('status', 1)
                          ->orderBy('tanggal_publikasi', 'desc')
                          ->limit($count)
                          ->get();

        // Format data untuk widget
        $artikels->transform(function ($artikel) {
            $artikel->tanggal_formatted = Carbon::parse($artikel->tanggal_publikasi)
                                               ->locale('id')
                                               ->isoFormat('dddd, D MMMM Y');
            $artikel->gambar_url = $this->getImageUrl($artikel);
            return $artikel;
        });

        return view('dashboard.artikel.widget', compact('artikels'));
    }

    /**
     * Helper method untuk mendapatkan URL gambar artikel
     */
    public function getImageUrl($artikel)
    {
        if ($artikel->gambar && Storage::exists('public/' . $artikel->gambar)) {
            return asset('storage/' . $artikel->gambar);
        }
        
        // Return default image jika gambar tidak ada
        return asset('assets/images/default-article.jpg');
    }

    /**
     * Menampilkan artikel yang dipaginasi untuk dashboard
     */
    public function paginated(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        
        $artikels = Artikel::where('status', 1)
                          ->orderBy('tanggal_publikasi', 'desc')
                          ->paginate($perPage);

        // Format data untuk setiap item
        $artikels->getCollection()->transform(function ($artikel) {
            $artikel->tanggal_formatted = Carbon::parse($artikel->tanggal_publikasi)
                                               ->locale('id')
                                               ->isoFormat('dddd, D MMMM Y');
            $artikel->gambar_url = $this->getImageUrl($artikel);
            return $artikel;
        });

        return view('dashboard.artikel.paginated', compact('artikels'));
    }

    /**
     * Method untuk mendapatkan artikel terbaru untuk landing page
     * Dipanggil dari route utama
     */
    public static function getRecentForLanding($limit = 3)
    {
        $artikels = Artikel::where('status', 1)
                          ->orderBy('tanggal_publikasi', 'desc')
                          ->limit($limit)
                          ->get();

        // Format data
        $artikels->transform(function ($artikel) {
            $artikel->tanggal_formatted = Carbon::parse($artikel->tanggal_publikasi)
                                               ->locale('id')
                                               ->isoFormat('dddd, D MMMM Y');
            
            // Get image URL
            if ($artikel->gambar && Storage::exists('public/' . $artikel->gambar)) {
                $artikel->gambar_url = asset('storage/' . $artikel->gambar);
            } else {
                $artikel->gambar_url = asset('assets/images/default-article.jpg');
            }
            
            return $artikel;
        });

        return $artikels;
    }
}