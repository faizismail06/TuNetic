<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\LaporanWarga;

class PetugasController extends Controller
{
    public function index()
    {
        $petugas = Petugas::all();
        return view('petugas.index', compact('petugas'));
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id', // Validasi bahwa user_id ada di tabel users
            'email' => 'required|email|unique:petugas,email',
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:petugas,username',
            'password' => 'required|string|min:8',
            'nomor' => 'nullable|numeric',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string|max:1000',
            'alasan_bergabung' => 'required|string|max:1000',
            'role' => 'nullable|in:Petugas',
            'foto_diri' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // input name tetap foto_diri
        ]);

        // Upload foto jika ada, simpan ke kolom sim_image
        if ($request->hasFile('foto_diri')) {
            $file = $request->file('foto_diri');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/foto_petugas'), $filename);
            $validatedData['sim_image'] = $filename; // simpan ke kolom sim_image
        }

        // Enkripsi password
        $validatedData['password'] = Hash::make($validatedData['password']);

        // Default role jika tidak dikirim
        $validatedData['role'] = $validatedData['role'] ?? 'Petugas';

        // Tambahkan user_id dan email_verified_at
        // Dapatkan user_id dari input yang dipilih.
        $user = User::find($validatedData['user_id']); //ambil data user berdasarkan id
        if($user){
           $user->level = 3; //ubah level user
           $user->save();
        }

        $validatedData['email_verified_at'] = now(); // anggap email terverifikasi


        // Hapus field 'foto_diri' agar tidak error saat insert (karena bukan kolom di tabel)
        unset($validatedData['foto_diri']);

        // Simpan data
        Petugas::create($validatedData);

        return redirect()->route('petugas.index')->with('success', 'Petugas berhasil ditambahkan!');
    }


    public function create()
    {
        $users = User::where('level', '=', 4)->get(); // Hanya ambil user yang levelnya bukan 3
        return view('petugas.create', compact('users')); // Kirim data users ke view
    }


    public function show($id)
    {
        $petugas = Petugas::findOrFail($id);
        return response()->json($petugas, 200);
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
            'name' => 'sometimes|string',
            'username' => 'sometimes|string',
            'password' => 'sometimes|string|min:8|nullable',
            'nomor' => 'nullable|numeric',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'sim_image' => 'nullable|string',
            'alasan_bergabung' => 'sometimes|string',
            'role' => 'nullable|in:Petugas',
        ]);

        $petugas->name = $validatedData['name'] ?? $petugas->name;
        $petugas->email = $validatedData['email'] ?? $petugas->email;
        $petugas->username = $validatedData['username'] ?? $petugas->username;
        $petugas->nomor = $validatedData['nomor'] ?? $petugas->nomor;
        $petugas->tanggal_lahir = $validatedData['tanggal_lahir'] ?? $petugas->tanggal_lahir;
        $petugas->alamat = $validatedData['alamat'] ?? $petugas->alamat;
        $petugas->sim_image = $validatedData['sim_image'] ?? $petugas->sim_image;
        $petugas->alasan_bergabung = $validatedData['alasan_bergabung'] ?? $petugas->alasan_bergabung;
        $petugas->role = $validatedData['role'] ?? $petugas->role;

        if (!empty($validatedData['password'])) {
            $petugas->password = Hash::make($validatedData['password']);
        }

        $petugas->save();

        return redirect()->route('petugas.index')->with('success', 'Data Petugas berhasil diupdate!');
    }
        public function showDetail($id)
    {
        $petugas = Petugas::findOrFail($id);
        return view('petugas.detail', compact('petugas'));
    }
    //     public function create()
    // {
    //     return view('petugas.create'); // Pastikan ini mengarah ke view yang benar
    // }

    public function destroy($id)
    {
        $petugas = Petugas::findOrFail($id);
        $petugas->delete();
        return redirect()->route('petugas.index')->with('success', 'Petugas berhasil dihapus!');
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

