<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArtikelController extends Controller
{
    // Menampilkan semua artikel
    public function index()
    {
        $artikel = Artikel::orderBy('tanggal_publikasi', 'desc')->get(); // gunakan artikel (tunggal)
        return view('adminpusat.artikel.index', compact('artikel')); // compact sesuai variabel
    }


    // Menampilkan satu artikel berdasarkan ID
    public function show($id)
    {
        $artikel = Artikel::find($id);
        if (!$artikel) {
            abort(404); // akan munculkan halaman error Laravel
        }

        return view('adminpusat.artikel.show', compact('artikel'));
    }

    // Menambahkan artikel baru
    public function store(Request $request)
    {
        $request->validate([
            'judul_artikel' => 'required|string|max:255',
            'tanggal_publikasi' => 'required|date',
            'deskripsi_singkat' => 'required|string|max:255',
            'gambar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'link_artikel' => 'required|url|max:255',
            'status' => 'required|integer|in:0,1',
        ]);

        // Simpan gambar
        $gambarPath = $request->file('gambar')->store('artikel', 'public');

        // Simpan data ke database
        Artikel::create([
            'judul_artikel' => $request->judul_artikel,
            'tanggal_publikasi' => $request->tanggal_publikasi,
            'deskripsi_singkat' => $request->deskripsi_singkat,
            'gambar' => $gambarPath,
            'link_artikel' => $request->link_artikel,
            'status' => $request->status,
        ]);

        // Redirect kembali dengan pesan sukses
        return redirect()->route('artikel.index')->with('success', 'Artikel berhasil ditambahkan.');
    }


    // Mengupdate artikel
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul_artikel' => 'required|string|max:255',
            'tanggal_publikasi' => 'required|date',
            'deskripsi_singkat' => 'nullable|string|max:255',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'link_artikel' => 'required|url|max:255',
            'status' => 'required|integer|in:0,1',
        ]);

        $artikel = Artikel::findOrFail($id);

        $artikel->judul_artikel = $request->judul_artikel;
        $artikel->tanggal_publikasi = $request->tanggal_publikasi;
        $artikel->deskripsi_singkat = $request->deskripsi_singkat;
        $artikel->link_artikel = $request->link_artikel;
        $artikel->status = $request->status;

        // Jika user upload gambar baru
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('artikel', 'public');
            $artikel->gambar = $path;
        }

        $artikel->save();

        return redirect()->route('artikel.index')->with('success', 'Artikel berhasil diperbarui.');
    }
    public function create()
    {
        return view('adminpusat.artikel.create'); // ganti path sesuai lokasi view form kamu
    }

    public function edit($id)
    {
        $artikel = Artikel::findOrFail($id);
        return view('adminpusat.artikel.edit', compact('artikel'));
    }


    // Menghapus artikel
    public function destroy($id)
    {
        $artikel = Artikel::findOrFail($id);

        // Hapus gambar jika ada
        if ($artikel->gambar && Storage::exists('public/' . $artikel->gambar)) {
            Storage::delete('public/' . $artikel->gambar);
        }

        $artikel->delete();

        return redirect()->route('artikel.index')->with('success', 'Artikel berhasil dihapus');
    }
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:0,1',
        ]);

        $artikel = Artikel::findOrFail($id);
        $artikel->status = $request->status;
        $artikel->save();

        return back()->with('success', 'Status artikel diperbarui.');
    }

}
