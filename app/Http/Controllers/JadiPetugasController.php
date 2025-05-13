<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class JadiPetugasController extends Controller
{
    /**
     * Menampilkan daftar semua pendaftaran petugas (hanya untuk admin)
     */
    public function index()
    {
        // Mengambil semua data petugas
        $petugasList = Petugas::latest()->paginate(10);

        return view('jadi-petugas.index', compact('petugasList'));
    }

    /**
     * Menampilkan form pendaftaran petugas baru
     */
    public function create()
    {
        return view('jadi-petugas.create');
    }

    /**
     * Menyimpan data petugas baru
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:petugas',
            'email' => 'required|string|email|max:255|unique:petugas',
            'password' => 'required|string|min:8|confirmed',
            'nomor' => 'nullable|numeric',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'sim_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'alasan_bergabung' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Upload gambar SIM jika ada
        $simImagePath = null;
        if ($request->hasFile('sim_image')) {
            $simImagePath = $request->file('sim_image')->store('sim_images', 'public');
        }

        // Buat data petugas baru
        $petugas = Petugas::create([
            'user_id' => Auth::id(), // ID pengguna yang sedang login
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nomor' => $request->nomor,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'sim_image' => $simImagePath,
            'alasan_bergabung' => $request->alasan_bergabung,
            'role' => 'Petugas',
        ]);

        // Redirect dengan pesan sukses
        return redirect()->route('jadi-petugas.index')
            ->with('success', 'Pendaftaran petugas berhasil disimpan!');
    }

    /**
     * Menampilkan detail petugas
     */
    public function show($id)
    {
        $petugas = Petugas::findOrFail($id);
        return view('jadi-petugas.show', compact('petugas'));
    }

    /**
     * Menampilkan form untuk mengedit data petugas
     */
    public function edit($id)
    {
        $petugas = Petugas::findOrFail($id);
        return view('jadi-petugas.edit', compact('petugas'));
    }

    /**
     * Menyimpan perubahan data petugas
     */
    public function update(Request $request, $id)
    {
        $petugas = Petugas::findOrFail($id);

        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:petugas,username,' . $id,
            'email' => 'required|string|email|max:255|unique:petugas,email,' . $id,
            'nomor' => 'nullable|numeric',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'sim_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'alasan_bergabung' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Upload gambar SIM baru jika ada
        if ($request->hasFile('sim_image')) {
            // Hapus gambar lama jika ada
            if ($petugas->sim_image) {
                Storage::disk('public')->delete($petugas->sim_image);
            }
            $simImagePath = $request->file('sim_image')->store('sim_images', 'public');
            $petugas->sim_image = $simImagePath;
        }

        // Update data petugas
        $petugas->name = $request->name;
        $petugas->username = $request->username;
        $petugas->email = $request->email;
        // Update password hanya jika diinput
        if ($request->filled('password')) {
            $validator = Validator::make($request->all(), [
                'password' => 'required|string|min:8|confirmed',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $petugas->password = Hash::make($request->password);
        }
        $petugas->nomor = $request->nomor;
        $petugas->tanggal_lahir = $request->tanggal_lahir;
        $petugas->alamat = $request->alamat;
        $petugas->alasan_bergabung = $request->alasan_bergabung;
        $petugas->save();

        // Redirect dengan pesan sukses
        return redirect()->route('jadi-petugas.index')
            ->with('success', 'Data petugas berhasil diperbarui!');
    }

    /**
     * Menghapus data petugas
     */
    public function destroy($id)
    {
        $petugas = Petugas::findOrFail($id);

        // Hapus gambar SIM jika ada
        if ($petugas->sim_image) {
            Storage::disk('public')->delete($petugas->sim_image);
        }

        // Hapus data petugas
        $petugas->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('jadi-petugas.index')
            ->with('success', 'Data petugas berhasil dihapus!');
    }

    /**
     * Form untuk user biasa yang ingin mendaftar sebagai petugas
     */
    public function showForm()
    {
        return view('jadi-petugas.form');
    }

    /**
     * Memproses pendaftaran user biasa menjadi petugas
     */
    public function submit(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:petugas',
            'email' => 'required|string|email|max:255|unique:petugas',
            'password' => 'required|string|min:8|confirmed',
            'nomor' => 'nullable|numeric',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'sim_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'alasan_bergabung' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Upload gambar SIM jika ada
        $simImagePath = null;
        if ($request->hasFile('sim_image')) {
            $simImagePath = $request->file('sim_image')->store('sim_images', 'public');
        }

        // Buat data petugas baru
        $petugas = Petugas::create([
            'user_id' => Auth::id(), // ID pengguna yang sedang login
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nomor' => $request->nomor,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'sim_image' => $simImagePath,
            'alasan_bergabung' => $request->alasan_bergabung,
            'role' => 'Petugas',
        ]);

        // Redirect dengan pesan sukses
        return redirect()->route('jadi-petugas.success')
            ->with('success', 'Pendaftaran petugas berhasil dikirim. Silakan tunggu verifikasi dari admin.');
    }

    /**
     * Menampilkan halaman sukses setelah pendaftaran
     */
    public function success()
    {
        return view('jadi-petugas.success');
    }
}
