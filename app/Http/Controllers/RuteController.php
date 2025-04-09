<?php

namespace App\Http\Controllers;

use App\Models\Rute;
use Illuminate\Http\Request;

class RuteController extends Controller
{
    /**
     * Menampilkan semua rute.
     */
    public function index()
    {
        return response()->json(Rute::all(), 200);
    }

    /**
     * Menyimpan rute baru.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_rute' => 'required|string|max:255',
            'map' => 'required|json', // Pastikan map dalam format JSON
            'wilayah' => 'required|json', // Pastikan wilayah dalam format GeoJSON
        ]);

        $rute = Rute::create($validatedData);

        return response()->json($rute, 201);
    }

    /**
     * Menampilkan detail rute berdasarkan ID.
     */
    public function show($id)
    {
        $rute = Rute::findOrFail($id);
        return response()->json($rute, 200);
    }

    /**
     * Mengupdate rute berdasarkan ID.
     */
    public function update(Request $request, $id)
    {
        $rute = Rute::findOrFail($id);

        $validatedData = $request->validate([
            'nama_rute' => 'sometimes|string|max:255',
            'map' => 'sometimes|json', // Opsional, harus JSON jika dikirim
            'wilayah' => 'sometimes|json', // Opsional, harus GeoJSON jika dikirim
        ]);

        $rute->update($validatedData);

        return response()->json($rute, 200);
    }

    /**
     * Menghapus rute berdasarkan ID.
     */
    public function destroy($id)
    {
        $rute = Rute::findOrFail($id);
        $rute->delete();

        return response()->json(["message" => "Rute berhasil dihapus"], 204);
    }
}
