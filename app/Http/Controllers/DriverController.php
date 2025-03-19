<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class DriverController extends Controller
{
    /**
     * Menampilkan semua driver.
     */
    public function index()
    {
        return response()->json(Driver::all());
    }

    /**
     * Menyimpan driver baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:drivers,email',
            'nama' => 'required|string|max:255',
            'password' => 'required|min:6',
            'alamat' => 'nullable|string',
            'role' => 'nullable|in:Petugas',
            'nomor' => 'nullable|numeric',
            'sim_image' => 'nullable|string',
        ]);

        $driver = Driver::create([
            'email' => $request->email,
            'nama' => $request->nama,
            'password' => Hash::make($request->password),
            'alamat' => $request->alamat,
            'role' => $request->role,
            'nomor' => $request->nomor,
            'sim_image' => $request->sim_image,
        ]);

        return response()->json($driver, 201);
    }

    /**
     * Menampilkan detail driver berdasarkan ID.
     */
    public function show($id)
    {
        $driver = Driver::find($id);

        if (!$driver) {
            return response()->json(['message' => 'Driver tidak ditemukan'], 404);
        }

        return response()->json($driver);
    }

    /**
     * Memperbarui data driver.
     */
    public function update(Request $request, $id)
    {
        $driver = Driver::find($id);

        if (!$driver) {
            return response()->json(['message' => 'Driver tidak ditemukan'], 404);
        }

        $request->validate([
            'email' => 'email|unique:drivers,email,' . $id,
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

        $driver->update($request->all());

        return response()->json($driver);
    }

    /**
     * Menghapus driver.
     */
    public function destroy($id)
    {
        $driver = Driver::find($id);

        if (!$driver) {
            return response()->json(['message' => 'Driver tidak ditemukan'], 404);
        }

        $driver->delete();

        return response()->json(['message' => 'Driver berhasil dihapus']);
    }
}
