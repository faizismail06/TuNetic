<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
    public function JadiPetugasForm()
    {
        // Cek jika user sudah petugas
        if (auth()->user()->is_petugas) {
            return redirect()->route('home')->with('warning', 'Anda sudah terdaftar sebagai petugas.');
        }

        // Cek apakah sudah ada data di tabel petugas
        $petugas = Petugas::where('user_id', auth()->id())->first();
        
        return view('masyarakat.jadipetugas.index', [
            'petugas' => $petugas,
            'is_petugas' => auth()->user()->is_petugas,
        ]);
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
            'alamat' => 'required|string|max:255',
            'ktp' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'sertifikat' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'pengalaman' => 'nullable|string|max:1000',
        ]);

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Upload KTP
            $ktpPath = $this->uploadFile($request->file('ktp'), 'ktp');

            // Upload sertifikat jika ada
            $sertifikatPath = null;
            if ($request->hasFile('sertifikat')) {
                $sertifikatPath = $this->uploadFile($request->file('sertifikat'), 'sertifikat');
            }

            // Buat atau update data petugas
            $petugas = Petugas::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'alamat' => $request->alamat,
                    'ktp_path' => $ktpPath,
                    'sertifikat_path' => $sertifikatPath,
                    'pengalaman' => $request->pengalaman,
                    'status' => 'pending', // status: pending, approved, rejected
                    'tanggal_daftar' => now(),
                ]
            );

            // Commit transaksi
            DB::commit();

            return redirect()->route('user.jadipetugas.form')
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
        $filename = $prefix . '_' . auth()->id() . '_' . time() . '.' . $file->getClientOriginalExtension();
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