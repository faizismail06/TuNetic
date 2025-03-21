<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PetugasController extends Controller
{
    /**
     * Menampilkan semua driver.
     */
    public function index()
    {
        return response()->json(Petugas::all());
    }

    /**
     * Menyimpan driver baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:petugas,email',
            'nama' => 'required|string|max:255',
            'password' => 'required|min:6',
            'alamat' => 'nullable|string',
            'role' => 'nullable|in:Petugas',
            'nomor' => 'nullable|numeric',
            'sim_image' => 'nullable|string',
        ]);

        $petugas = Petugas::create([
            'email' => $request->email,
            'nama' => $request->nama,
            'password' => Hash::make($request->password),
            'alamat' => $request->alamat,
            'role' => $request->role,
            'nomor' => $request->nomor,
            'sim_image' => $request->sim_image,
        ]);

        return response()->json($petugas, 201);
    }

    /**
     * Menampilkan detail driver berdasarkan ID.
     */
    public function show($id)
    {
        $petugas = Petugas::find($id);

        if (!$petugas) {
            return response()->json(['message' => 'Driver tidak ditemukan'], 404);
        }

        return response()->json($petugas);
    }

    /**
     * Memperbarui data driver.
     */
    public function update(Request $request, $id)
    {
        $petugas = Petugas::find($id);

        if (!$petugas) {
            return response()->json(['message' => 'Driver tidak ditemukan'], 404);
        }

        $request->validate([
            'email' => 'email|unique:petugas,email,' . $id,
            'nama' => 'string|max:255',
            'password' => 'nullable|min:6',
            'alamat' => 'nullable|string',
            'role' => 'nullable|in:Petugas',
            'nomor' => 'nullable|numeric',
            'sim_image' => 'nullable|string',
        ]);

        if ($request->has('password')) {
            $request->merge(['password' => Hash::make($request->password)]);
        }

        $petugas->update($request->all());

        return response()->json($petugas);
    }

    /**
     * Menghapus driver.
     */
    public function destroy($id)
    {
        $petugas = Petugas::find($id);

        if (!$petugas) {
            return response()->json(['message' => 'Driver tidak ditemukan'], 404);
        }

        $petugas->delete();

        return response()->json(['message' => 'Driver berhasil dihapus']);
    }
}
