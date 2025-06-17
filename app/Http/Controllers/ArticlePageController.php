<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Request;

class ArticlePageController extends Controller
{
    // Method ini HANYA untuk menampilkan halaman semua artikel
    public function index()
    {
        // Ambil semua artikel yang statusnya aktif, urutkan dari terbaru, bagi per 6 artikel per halaman
        $articles = Artikel::where('status', 1)->latest('tanggal_publikasi')->paginate(6);

        // Tampilkan view dan kirim data artikelnya
        return view('articles.index', ['articles' => $articles]);
    }
}