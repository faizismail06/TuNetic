<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Petugas; // Model untuk tabel petugas
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;

class JadiPetugasController extends Controller
{
    /**
     * Menampilkan form pendaftaran petugas
     */
    public function JadipetugasForm()
{
    // Cek jika user sudah petugas
    if (auth()->user()->is_petugas) {
        return redirect()->route('home')->with('warning', 'Anda sudah terdaftar sebagai petugas.');
    }

    $provinces = Province::all(); // Ambil semua provinsi

    return view('masyarakat.jadipetugas.index', [
        'provinces' => $provinces,
        'is_petugas' => auth()->user()->is_petugas,
    ]);
}

    /**
     * Mengambil data kabupaten/kota berdasarkan provinsi
     */
    public function getRegencies($province_id)
    {
        $regencies = Regency::where('province_id', $province_id)->get();
        return response()->json($regencies);
    }

    /**
     * Mengambil data kecamatan berdasarkan kabupaten/kota
     */
    public function getDistricts($regency_id)
    {
        $districts = District::where('regency_id', $regency_id)->get();
        return response()->json($districts);
    }

    /**
     * Mengambil data desa/kelurahan berdasarkan kecamatan
     */
    public function getVillages($district_id)
    {
        $villages = Village::where('district_id', $district_id)->get();
        return response()->json($villages);
    }

    /**
     * Proses submit pendaftaran petugas
     */
    public function submitPetugasRequest(Request $request)
    {
        // Validasi user
        $user = auth()->user();
        if ($user->is_petugas) {
            return redirect()->route('home')->with('warning', 'Anda sudah terdaftar sebagai petugas.');
        }

        // Validasi input
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:petugas,username',
            'no_telepon' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'tanggal_lahir' => 'nullable|date',
            'provinsi_id' => 'required|exists:reg_provinces,id',
            'kabupaten_id' => 'required|exists:reg_regencies,id',
            'kecamatan_id' => 'required|exists:reg_districts,id',
            'desa_id' => 'required|exists:reg_villages,id',
            'foto_diri' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'foto_sim' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'alasan_bergabung' => 'required|string|max:1000',
        ]);

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Upload foto diri
            $fotoDiriPath = $this->uploadFile($request->file('foto_diri'), 'foto_diri');

            // Upload foto SIM
            $fotoSimPath = $this->uploadFile($request->file('foto_sim'), 'foto_sim');

            // Update data user
            $user->update([
                'name' => $request->nama_lengkap,
                'username' => $request->username,
                'no_telepon' => $request->no_telepon,
                'email' => $request->email,
                'tanggal_lahir' => $request->tanggal_lahir,
                'province_id' => $request->provinsi_id,
                'regency_id' => $request->kabupaten_id,
                'district_id' => $request->kecamatan_id,
                'village_id' => $request->desa_id,
            ]);
            
            // Buat data petugas
            $petugas = Petugas::create([
                'user_id' => $user->id,
                'email' => $user->email,
                'password' => Hash::make($request->password),
                'name' => $request->nama_lengkap,
                'username' => $request->username,
                'no_telepon' => $request->no_telepon,
                'tanggal_lahir' => $request->tanggal_lahir,
                'foto_diri_path' => $fotoDiriPath,
                'foto_sim_path' => $fotoSimPath,
                'alasan_bergabung' => $request->alasan_bergabung,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Commit transaksi
            DB::commit();

            return redirect()->route('masyarakat.jadi-petugas.form')
                ->with('success', 'Permohonan Anda berhasil dikirim. Tunggu verifikasi dari admin.');

        } catch (\Exception $e) {
            // Rollback transaksi jika ada error
            DB::rollBack();
            
            return back()->withInput()
                ->with('error', 'Gagal mengirim permohonan: ' . $e->getMessage());
        }
    }

    /**
     * Helper untuk upload file
     */
    private function uploadFile($file, $prefix)
    {
        $filename = $prefix . '' . auth()->id() . '' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('petugas_documents', $filename, 'public');
        
        return $path;
    }

    /**
     * Method untuk menampilkan status permohonan
     */
    public function showStatus()
    {
        $petugas = Petugas::where('user_id', auth()->id())->first();

        return view('masyarakat.jadipetugas_status', compact('petugas'));
    }
}