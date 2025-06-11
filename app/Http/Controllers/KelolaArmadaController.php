<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Armada;
use Exception;

class KelolaArmadaController extends Controller
{
    public function index()
    {
        // Menampilkan daftar armada
        $armada = Armada::all();

        return view('adminpusat.manage-armada.index', compact('armada'));
    }

    public function create()
    {
        // Menampilkan form untuk menambah armada
        $armada = Armada::all();

        return view('adminpusat.manage-armada.create', compact('armada')); // Pastikan ini mengarah ke view yang benar
    }

    public function store(Request $request)
    {
        // Menyimpan armada baru
        try {
            $validatedData = $request->validate([
                'jenis_kendaraan' => 'required|string|max:255',
                'no_polisi' => 'required|string|max:255',
                'merk_kendaraan' => 'required|string|max:255',
                'kapasitas' => 'required|integer|min:1',
            ]);

            $armada = Armada::create([
                'jenis_kendaraan' => $request->jenis_kendaraan,
                'no_polisi' => $request->no_polisi,
                'merk_kendaraan' => $request->merk_kendaraan,
                'kapasitas' => $request->kapasitas,
            ]);

            $armada->save();
            // return response()->json($rute, 201);
            return redirect()->route('manage-armada.index')->with('success', 'Armada berhasil ditambahkan');
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal menyimpan rute',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function show($id)
    {
        // Menampilkan detail armada
    }

    public function edit($id)
    {
        // Menampilkan form untuk mengedit armada
        $armada = Armada::findOrFail($id);
        return view('adminpusat.manage-armada.edit', compact('armada'));
    }

    public function update(Request $request, $id)
    {
        // Memperbarui data armada
        $request->validate([
            'jenis_kendaraan' => 'required|string|max:255',
            'no_polisi' => 'required|string|max:255',
            'merk_kendaraan' => 'required|string|max:255',
            'kapasitas' => 'required|numeric|min:1',
        ]);

        $armada = Armada::findOrFail($id);
        $armada->jenis_kendaraan = $request->jenis_kendaraan;
        $armada->no_polisi = $request->no_polisi;
        $armada->merk_kendaraan = $request->merk_kendaraan;
        $armada->kapasitas = $request->kapasitas;
        $armada->save();

        return redirect()->route('manage-armada.index')->with('success', 'Data armada berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Menghapus armada
        try {
            $armada = Armada::findOrFail($id);
            $armada->delete();

            return redirect()->route('manage-armada.index')->with('success', 'Armada berhasil dihapus');
        } catch (Exception $e) {
            return redirect()->route('manage-armada.index')->with('error', 'Gagal menghapus Armada: ' . $e->getMessage());
        }
    }
}