<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Request;

class ArtikelController extends Controller
{
    // Menampilkan semua artikel
    public function index()
    {
        $artikel = Artikel::all();
        return response()->json($artikel);
    }

    // Menampilkan satu artikel berdasarkan ID
    public function show($id)
    {
        $artikel = Artikel::find($id);
        if (!$artikel) {
            return response()->json(['message' => 'Artikel tidak ditemukan'], 404);
        }
        return response()->json($artikel);
    }

    // Menambahkan artikel baru
    public function store(Request $request)
    {
        $request->validate([
            'judul_artikel' => 'required|string|max:255',
            'tanggal_publikasi' => 'required|date',
            'deskripsi_singkat' => 'required|string|max:255',
            'gambar' => 'required|string|max:255',
            'link_artikel' => 'required|string|max:255',
            'status' => 'integer|in:0,1',
        ]);

        $artikel = Artikel::create($request->all());

        return response()->json(['message' => 'Artikel berhasil ditambahkan', 'data' => $artikel], 201);
    }

    // Mengupdate artikel
    public function update(Request $request, $id)
    {
        $artikel = Artikel::find($id);
        if (!$artikel) {
            return response()->json(['message' => 'Artikel tidak ditemukan'], 404);
        }

        $request->validate([
            'judul_artikel' => 'string|max:255',
            'tanggal_publikasi' => 'date',
            'deskripsi_singkat' => 'string|max:255',
            'gambar' => 'string|max:255',
            'link_artikel' => 'string|max:255',
            'status' => 'integer|in:0,1',
        ]);

        $artikel->update($request->all());

        return response()->json(['message' => 'Artikel berhasil diperbarui', 'data' => $artikel]);
    }

    // Menghapus artikel
    public function destroy($id)
    {
        $artikel = Artikel::find($id);
        if (!$artikel) {
            return response()->json(['message' => 'Artikel tidak ditemukan'], 404);
        }

        $artikel->delete();
        return response()->json(['message' => 'Artikel berhasil dihapus']);
    }
}
