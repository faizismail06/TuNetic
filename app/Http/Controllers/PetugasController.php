<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;

class PetugasController extends Controller
{
    public function index()
    {
        $petugas = Petugas::all();
        return view('petugas.index', compact('petugas'));
    }

    public function create()
    {
        $users = User::whereDoesntHave('petugas')->get(); // Ambil user level 4
        return view('petugas.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'email' => 'required|email|unique:petugas,email',
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:petugas,username',
            'password' => 'required|string|min:8',
            'nomor' => 'nullable|numeric',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string|max:1000',
            'alasan_bergabung' => 'required|string|max:1000',
            'role' => 'nullable|in:Petugas',
            'sim_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('sim_image')) {
            $file = $request->file('sim_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs(('public/foto_sim'), $filename);
            $validatedData['sim_image'] = 'storage/foto_sim/' . $filename;
        }

        $validatedData['password'] = Hash::make($validatedData['password']);
        $validatedData['role'] = $validatedData['role'] ?? 'Petugas';
        $validatedData['email_verified_at'] = now();

        $user = User::find($validatedData['user_id']);
        if ($user) {
            $user->level = 3;
            $user->save();
        }
        $validatedData['status'] = 'Disetujui'; 
        Petugas::create($validatedData);

        return redirect()->route('manage-petugas.index')->with('success', 'Petugas berhasil ditambahkan!');
    }

    public function show($id)
    {
        $petugas = Petugas::findOrFail($id);
        return response()->json($petugas, 200);
    }

    public function updateStatus(Request $request, $id)
    {
        $petugas = Petugas::findOrFail($id);
        $petugas->status = $request->status;
        $petugas->save();

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diubah'
        ]);
    }

    public function showDetail($id)
    {
        $petugas = Petugas::findOrFail($id);
        return view('petugas.detail', compact('petugas'));
    }

    public function edit($id)
    {
        $petugas = Petugas::findOrFail($id);
        return view('petugas.edit', compact('petugas'));
    }

    public function update(Request $request, $id)
    {
        $petugas = Petugas::findOrFail($id);

        $validatedData = $request->validate([
            'email' => 'sometimes|email|unique:petugas,email,' . $id,
            'name' => 'sometimes|string|max:255',
            'username' => 'sometimes|string|max:255|unique:petugas,username,' . $id,
            'password' => 'nullable|string|min:8',
            'nomor' => 'nullable|numeric',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string|max:1000',
            'alasan_bergabung' => 'nullable|string|max:1000',
            'role' => 'nullable|in:Petugas',
            'sim_image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        if ($request->hasFile('sim_image')) {
            $file = $request->file('sim_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs(('public/foto_sim'), $filename);
            $validatedData['sim_image'] = 'storage/foto_sim/' . $filename;
        }

        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        $petugas->update($validatedData);

        return redirect()->route('manage-petugas.index')->with('success', 'Data Petugas berhasil diupdate!');
    }

    public function destroy($id)
    {
        $petugas = Petugas::findOrFail($id);
        $petugas->delete();
        return redirect()->route('petugas.index')->with('success', 'Petugas berhasil dihapus!');
    }

    public function redirectToDashboard()
    {
        $user = Auth::user();

        if ($user->level == 3 && $user->petugas) {
            return redirect()->route('jadwal-pengambilan.auto-tracking');
        }

        return redirect()->route('home')->with('error', 'Anda tidak memiliki akses.');
    }
    
}
