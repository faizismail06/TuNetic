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
     * Display the profile form.
     */
    public function index()
    {
        $user = Auth::user();
        $provinces = Province::all();

        return view('profile.index', compact('user', 'provinces'));
    }

    /**
     * Get regencies by province id
     */
    public function getRegencies($province_id)
    {
        $regencies = Regency::where('province_id', $province_id)
                          ->orderBy('name', 'asc')
                          ->get();
        
        return response()->json($regencies);
    }

    /**
     * Get districts by regency id
     */
    public function getDistricts($regency_id)
    {
        $districts = District::where('regency_id', $regency_id)
                           ->orderBy('name', 'asc')
                           ->get();
        
        return response()->json($districts);
    }

    /**
     * Get villages by district id
     */
    public function getVillages($district_id)
    {
        $villages = Village::where('district_id', $district_id)
                         ->orderBy('name', 'asc')
                         ->get();
        
        return response()->json($villages);
    }

    /**
     * Update profile information.
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
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Mengambil data user yang sedang login
        $user = Auth::user();

        // Update data user
        $user->name = $request->name;
        $user->email = $request->email;
        
        // Tambahkan kolom no_telepon jika belum ada di tabel users
        if (isset($request->no_telepon)) {
            $user->no_telepon = $request->no_telepon;
        }
        
        $user->province_id = $request->province_id;
        $user->regency_id = $request->regency_id;
        $user->district_id = $request->district_id;
        $user->village_id = $request->village_id;

        // Handle foto upload
        if ($request->hasFile('foto')) {
            // Buat directory jika belum ada
            $uploadPath = 'public/profil';
            if (!Storage::exists($uploadPath)) {
                Storage::makeDirectory($uploadPath);
            }
            
            // Hapus foto lama jika ada
            if ($user->foto && Storage::exists('public/profil/' . $user->foto)) {
                Storage::delete('public/profil/' . $user->foto);
            }
            
            $file = $request->file('foto');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/profil', $filename);
            $user->foto = $filename;
        }

        $user->save();

        return redirect()->route('admin.profile.index')->with('success', 'Profil berhasil diperbarui.');
    }
    
    /**
     * Upload profile photo through Ajax.
     */
    public function uploadPhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        // Mengambil data user yang sedang login
        $user = Auth::user();

        // Handle photo upload
        if ($request->hasFile('foto')) {
            // Buat directory jika belum ada
            $uploadPath = 'public/profil';
            if (!Storage::exists($uploadPath)) {
                Storage::makeDirectory($uploadPath);
            }
            
            // Hapus foto lama jika ada
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
     * Show the form for changing password.
     */
    public function showChangePasswordForm()
    {
        return view('profile.change-password');
    }
    
    /**
     * Update the user's password.
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
        
        // Verifikasi password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Password lama tidak sesuai']);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('profile.index')->with('success', 'Password berhasil diubah');
    }
    
    /**
     * Get regencies based on province ID.
     */
    // public function getRegencies($provinceId)
    // {
    //     $regencies = Regency::where('province_id', $provinceId)->get();
    //     return response()->json($regencies);
    // }
    
    // /**
    //  * Get districts based on regency ID.
    //  */
    // public function getDistricts($regencyId)
    // {
    //     $districts = District::where('regency_id', $regencyId)->get();
    //     return response()->json($districts);
    // }
    
    // /**
    //  * Get villages based on district ID.
    //  */
    // public function getVillages($districtId)
    // {
    //     $villages = Village::where('district_id', $districtId)->get();
    //     return response()->json($villages);
    // }
}