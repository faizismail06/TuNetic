<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class PetugasController extends Controller
{
    public function index()
    {
        return response()->json(Petugas::all(), 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|unique:petugas,email',
            'name' => 'required|string',
            'username' => 'required|string',
            'password' => 'required|string|min:8',
            'nomor' => 'nullable|numeric',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'sim_image' => 'nullable|string',
            'alasan_bergabung' => 'required|string',
            'role' => 'nullable|in:Petugas',
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);

        $petugas = Petugas::create($validatedData);

        return response()->json($petugas, 201);
    }

    public function show($id)
    {
        $petugas = Petugas::findOrFail($id);
        return response()->json($petugas, 200);
    }

    public function update(Request $request, $id)
    {
        $petugas = Petugas::findOrFail($id);

        $validatedData = $request->validate([
            'email' => 'sometimes|email|unique:petugas,email,' . $id,
            'name' => 'sometimes|string',
            'username' => 'sometimes|string',
            'password' => 'sometimes|string|min:8',
            'nomor' => 'nullable|numeric',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'sim_image' => 'nullable|string',
            'alasan_bergabung' => 'sometimes|string',
            'role' => 'nullable|in:Petugas',
        ]);

        if (isset($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

        $petugas->update($validatedData);

        return response()->json($petugas, 200);
    }

    public function destroy($id)
    {
        $petugas = Petugas::findOrFail($id);
        $petugas->delete();
        return response()->json(["message" => "Petugas berhasil dihapus"], 204);
    }

    public function redirectToDashboard()
    {
        $user = Auth::user();

        // Jika user memiliki petugas terkait, cek levelnya
        if ($user->level == 3) {
            // Cek apakah user ada di tabel petugas
            $petugas = $user->petugas;

            if ($petugas) {
                return redirect()->route('jadwal-pengambilan.auto-tracking'); // Petugas yang sudah terdaftar
            }
        }

        // Default route jika level tidak sesuai
        return redirect()->route('home')->with('error', 'Anda tidak memiliki akses.');
    }
}
