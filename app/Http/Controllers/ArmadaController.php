<?php

namespace App\Http\Controllers;

use App\Models\Armada;
use Illuminate\Http\Request;

class ArmadaController extends Controller
{
    /**
     * Menampilkan semua data armada.
     */
    public function index()
    {
        return response()->json(Armada::all());
    }

    /**
     * Menyimpan armada baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_kendaraan' => 'required|string|max:255',
            'merk_kendaraan' => 'required|string|max:255',
            'no_polisi' => 'required|string|unique:armada,no_polisi|max:15',
            'kapasitas' => 'required|integer|min:1',
        ]);

        $armada = Armada::create($request->all());

        return response()->json($armada, 201);
    }

    /**
     * Menampilkan armada berdasarkan ID.
     */
    public function show($id)
    {
        $armada = Armada::find($id);

        if (!$armada) {
            return response()->json(['message' => 'Armada tidak ditemukan'], 404);
        }

        return response()->json($armada);
    }

    /**
     * Memperbarui data armada.
     */
    public function update(Request $request, $id)
    {
        $armada = Armada::find($id);

        if (!$armada) {
            return response()->json(['message' => 'Armada tidak ditemukan'], 404);
        }

        $request->validate([
            'jenis_kendaraan' => 'string|max:255',
            'merk_kendaraan' => 'string|max:255',
            'no_polisi' => 'string|max:15|unique:armada,no_polisi,' . $id,
            'kapasitas' => 'integer|min:1',
        ]);

        $armada->update($request->all());

        return response()->json($armada);
    }

    /**
     * Menghapus armada.
     */
    public function destroy($id)
    {
        $armada = Armada::find($id);

        if (!$armada) {
            return response()->json(['message' => 'Armada tidak ditemukan'], 404);
        }

        $armada->delete();

        return response()->json(['message' => 'Armada berhasil dihapus']);
    }
}
