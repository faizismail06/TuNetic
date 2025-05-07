<?php

namespace App\Http\Controllers;

use App\Models\Profil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;

class ProfilController extends Controller
{
    /**
     * Tampilkan profil admin.
     */
    public function index()
    {
        $user = Auth::user();
        $provinces = Province::all();

        return view('profile.index', compact('user', 'provinces'));
    }


    /**
     * Tampilkan profil user biasa.
     */
    public function userIndex()
    {
        $user = Auth::user();
        $provinces = Province::all();

        return view('masyarakat.profils.index', compact('user', 'provinces'));
    }

    /**
     * Tampilkan profil petugas.
     */
    // Tambahkan method ini:
    public function petugasIndex()
    {
        if (auth()->user()->role !== 'petugas') {
            return redirect()->back()->with('error', 'Akses ditolak');
        }    
        
        $user = Auth::user();
        $provinces = Province::all();
    
        return view('petugas.profile.index', [
            'user' => $user,
            'provinces' => $provinces,
            'petugas' => $user->petugas // Pastikan relasi ini ada di model User
        ]);
    }

public function petugasUpdate(Request $request)
{
    $user = Auth::user();
    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'email' => 'required|email',
        'no_telepon' => 'required',
        // tambahkan validasi lainnya
    ]);

    // Update data user
    $user = Auth::user();
    $user->update($request->only(['name', 'email']));

    // Update data petugas
    $user = Auth::user();
    $petugas = $user->petugas;
    $petugas->update($request->except(['name', 'email', '_token']));

    return redirect()->route('petugas.profile.index')->with('success', 'Profil berhasil diperbarui');
}

    /**
     * Ambil kabupaten berdasarkan ID provinsi.
     */
    public function getRegencies($province_id)
    {
        $regencies = Regency::where('province_id', $province_id)->orderBy('name', 'asc')->get();
        return response()->json($regencies);
    }

    /**
     * Ambil kecamatan berdasarkan ID kabupaten.
     */
    public function getDistricts($regency_id)
    {
        $districts = District::where('regency_id', $regency_id)->orderBy('name', 'asc')->get();
        return response()->json($districts);
    }

    /**
     * Ambil desa berdasarkan ID kecamatan.
     */
    public function getVillages($district_id)
    {
        $villages = Village::where('district_id', $district_id)->orderBy('name', 'asc')->get();
        return response()->json($villages);
    }

    /**
     * Update data profil.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_telepon' => 'required|string|max:15',
            'province_id' => 'required|exists:reg_provinces,id',
            'regency_id' => 'required|exists:reg_regencies,id',
            'district_id' => 'required|exists:reg_districts,id',
            'village_id' => 'required|exists:reg_villages,id',
            'detail_alamat' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Add 'status_petugas' validation rule only if the user is a petugas
        if ($user->role === 'petugas') {
            $rules['status_petugas'] = 'required|in:aktif,tidak aktif';
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->no_telepon = $request->no_telepon;
        $user->province_id = $request->province_id;
        $user->regency_id = $request->regency_id;
        $user->district_id = $request->district_id;
        $user->village_id = $request->village_id;

        // Handle status_petugas update if it exists in the request.
        if ($request->has('status_petugas')) {
            $user->status_petugas = $request->status_petugas;
        }
        
        // Upload foto jika ada
        if ($request->hasFile('foto')) {
            $uploadPath = 'public/profil';
            if (!Storage::exists($uploadPath)) {
                Storage::makeDirectory($uploadPath);
            }

            if ($user->foto && Storage::exists('public/profil/' . $user->foto)) {
                Storage::delete('public/profil/' . $user->foto);
            }

            $file = $request->file('foto');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/profil', $filename);
            $user->foto = $filename;
        }

        $user->save();

        // Cek peran untuk redirect
        if ($user->role === 'admin') {
            return redirect()->route('admin.profile.index')->with('success', 'Profil berhasil diperbarui.');
        } elseif ($user->role === 'user') {
            return redirect()->route('user.profile.index')->with('success', 'Profil berhasil diperbarui.');
        } else {
            return redirect()->route('petugas.profils.index')->with('success', 'Profil berhasil diperbarui.');
        }
    }

    /**
     * Upload foto via Ajax.
     */
    public function uploadPhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $user = Auth::user();

        if ($request->hasFile('foto')) {
            $uploadPath = 'public/profil';
            if (!Storage::exists($uploadPath)) {
                Storage::makeDirectory($uploadPath);
            }

            if ($user->foto && Storage::exists('public/profil/' . $user->foto)) {
                Storage::delete('public/profil/' . $user->foto);
            }

            $file = $request->file('foto');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/profil', $filename);
            $user->foto = $filename;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Foto berhasil diupload',
                'foto_url' => asset('storage/profil/' . $filename)
            ]);
        }

        return response()->json(['error' => 'Gagal mengupload foto'], 400);
    }

    /**
     * Form ganti password.
     */
    public function showChangePasswordForm()
    {
        return view('profile.change-password');
    }

    /**
     * Simpan perubahan password.
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Password lama tidak sesuai']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        if ($user->role === 'admin') {
            return redirect()->route('admin.profile.index')->with('success', 'Profil berhasil diperbarui.');
        } elseif ($user->role === 'user') {
            return redirect()->route('user.profile.index')->with('success', 'Profil berhasil diperbarui.');
        } else {
            return redirect()->route('petugas.profils.index')->with('success', 'Profil berhasil diperbarui.');
        }
    }
}
