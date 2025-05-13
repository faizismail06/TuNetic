<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $provinces = Province::all();

        return view('profile.index', compact('user', 'provinces'));
    }

    public function userIndex()
    {
        $user = Auth::user();
        $provinces = Province::all();

        return view('masyarakat.profile.index', compact('user', 'provinces'));
    }

    public function petugasIndex()
    {
        $user = Auth::user();
        if ($user->level !== 3) {
            return redirect()->back()->with('error', 'Akses ditolak');
        }

        $provinces = Province::all();

        return view('petugas.profile.index', [
            'user' => $user,
            'provinces' => $provinces,
            'petugas' => $user->petugas
        ]);
    }

    public function akun()
{
    return view('masyarakat.akun.index', [
        'user' => auth()->user()
    ]);
}

public function updatePassword(Request $request)
{
    $request->validate([
        'new_password' => 'required|string|min:8|confirmed',
    ]);
    
    $user = Auth::user();
    $user->password = Hash::make($request->new_password);
    $user->save();
    
    return redirect()->route('user.akun.index')->with('success', 'Password berhasil diubah!');
}

    // Menambahkan method userUpdate yang hilang
    public function userUpdate(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_telepon' => 'required|string|max:15',
            'province_id' => 'required|exists:reg_provinces,id',
            'regency_id' => 'required|exists:reg_regencies,id',
            'district_id' => 'required|exists:reg_districts,id',
            'village_id' => 'required|exists:reg_villages,id',
            'alamat' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user->fill($request->only([
            'name',
            'email',
            'no_telepon',
            'province_id',
            'regency_id',
            'district_id',
            'village_id',
            'alamat'
        ]));

        if ($request->hasFile('gambar')) {
            $uploadPath = 'public/profile';

            if (!Storage::exists($uploadPath)) {
                Storage::makeDirectory($uploadPath);
            }

            if ($user->gambar && Storage::exists("$uploadPath/{$user->gambar}")) {
                Storage::delete("$uploadPath/{$user->gambar}");
            }

            $file = $request->file('gambar');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs($uploadPath, $filename);
            $user->gambar = $filename;
        }

        $user->save();

        return redirect()->route('user.profile.index')->with('success', 'Profil berhasil diperbarui.');
    }

    public function petugasUpdate(Request $request)
    {
        $user = Auth::user();
        Log::info($user);

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'no_telepon' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user->update($request->only(['name', 'email', 'no_telepon']));

        if ($user->petugas) {
            $user->petugas->update($request->except(['name', 'email', '_token']));
        }

        return redirect()->route('petugas.profile.index')->with('success', 'Profil berhasil diperbarui');
    }

    public function getRegencies($province_id)
    {
        return response()->json(Regency::where('province_id', $province_id)->orderBy('name')->get());
    }

    public function getDistricts($regency_id)
    {
        return response()->json(District::where('regency_id', $regency_id)->orderBy('name')->get());
    }

    public function getVillages($district_id)
    {
        return response()->json(Village::where('district_id', $district_id)->orderBy('name')->get());
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_telepon' => 'required|string|max:15',
            'province_id' => 'required|exists:reg_provinces,id',
            'regency_id' => 'required|exists:reg_regencies,id',
            'district_id' => 'required|exists:reg_districts,id',
            'village_id' => 'required|exists:reg_villages,id',
            'alamat' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];

        if ($user->level === 3) {
            $rules['status_petugas'] = 'nullable|in:aktif,tidak aktif';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user->fill($request->only([
            'name',
            'email',
            'no_telepon',
            'province_id',
            'regency_id',
            'district_id',
            'village_id',
            'alamat'
        ]));

        if ($request->has('status_petugas')) {
            $user->status_petugas = $request->status_petugas;
        }

        if ($request->hasFile('gambar')) {
            $uploadPath = 'public/profile';

            if (!Storage::exists($uploadPath)) {
                Storage::makeDirectory($uploadPath);
            }

            if ($user->gambar && Storage::exists("$uploadPath/{$user->gambar}")) {
                Storage::delete("$uploadPath/{$user->gambar}");
            }

            $file = $request->file('gambar');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs($uploadPath, $filename);
            $user->gambar = $filename;
        }

        $user->save();

        if ($user->level === 1) {
            return redirect()->route('admin.profile.index')->with('success', 'Profil berhasil diperbarui.');
        } elseif ($user->level === 2) {
            return redirect()->route('admintpst.profile.index')->with('success', 'Profil berhasil diperbarui.');
        } elseif ($user->level === 3) {
            return redirect()->route('petugas.profile.index')->with('success', 'Profil berhasil diperbarui.');
        } elseif ($user->level === 4) {
            return redirect()->route('user.profile.index')->with('success', 'Profil berhasil diperbarui.');
        } else {
            return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
        }
    }

    public function uploadPhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $user = Auth::user();

        if ($request->hasFile('gambar')) {
            $uploadPath = 'public/profile';

            if (!Storage::exists($uploadPath)) {
                Storage::makeDirectory($uploadPath);
            }

            if ($user->gambar && Storage::exists("$uploadPath/{$user->gambar}")) {
                Storage::delete("$uploadPath/{$user->gambar}");
            }

            $file = $request->file('gambar');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs($uploadPath, $filename);
            $user->gambar = $filename;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Foto berhasil diupload',
                'foto_url' => asset('storage/profile/' . $filename)
            ]);
        }

        return response()->json(['error' => 'Gagal mengupload foto'], 400);
    }

    public function showChangePasswordForm()
    {
        return view('profile.change-password');
    }

    public function updateAkun(Request $request)
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

        $provinces = Province::all();

        if ($user->level === 1) {
            return redirect()->route('admin.profile.index')->with('success', 'Profil berhasil diperbarui.');
        } elseif ($user->level === 2) {
            return redirect()->route('admintpst.profile.index')->with('success', 'Profil berhasil diperbarui.');
        } elseif ($user->level === 3) {
            return redirect()->route('petugas.profile.index')->with('success', 'Profil berhasil diperbarui.');
        } elseif ($user->level === 4) {
            return redirect()->route('user.profile.index')->with('success', 'Profil berhasil diperbarui.');
        } else {
            return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
        }
    }
}
