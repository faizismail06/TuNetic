<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Petugas;
use App\Notifications\CustomVerifyUser;
use Illuminate\Support\Facades\DB;

class VerifyUserController extends Controller
{
    public function approve($id)
    {
        try {
            // Mulai transaksi database
            DB::beginTransaction();

            // Cari user berdasarkan ID
            $user = User::findOrFail($id);

            // Cari data petugas berdasarkan user_id
            $petugas = Petugas::where('user_id', $id)->first();

            if (!$petugas) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Data petugas tidak ditemukan.');
            }

            // Update status petugas menjadi 'Disetujui'
            $petugas->update([
                'status' => 'Disetujui'
            ]);

            // Update user: ubah level menjadi 3 (konsisten dengan PetugasController)
            $user->update([
                'level' => 3,  // Set level menjadi 3 untuk petugas
                'email_verified_at' => $user->email_verified_at ?? now() // Verifikasi email jika belum
            ]);

            // Update role_id di tabel model_has_roles menjadi 3 (petugas)
            DB::table('model_has_roles')
                ->where('model_id', $user->id)
                ->where('model_type', 'App\\Models\\User')
                ->update(['role_id' => 3]);

            // Alternatif menggunakan Spatie Permission (jika model User menggunakan HasRoles trait):
            // $user->syncRoles([3]); // atau $user->syncRoles(['petugas']);

            // Kirim notifikasi ke user
            $user->notify(new CustomVerifyUser());

            // Commit transaksi
            DB::commit();

            // Jika request adalah AJAX, return JSON
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Petugas berhasil disetujui dan diverifikasi.'
                ]);
            }

            return redirect()->route('manage-petugas.index')
                ->with('success', 'Berhasil menyetujui petugas.');
        } catch (\Exception $e) {
            // Rollback transaksi jika ada error
            DB::rollBack();

            // Jika request adalah AJAX, return JSON error
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memverifikasi petugas: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Gagal memverifikasi petugas: ' . $e->getMessage());
        }
    }

    /**
     * Update status petugas (untuk tombol tolak)
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            // Mulai transaksi database
            DB::beginTransaction();

            $petugas = Petugas::findOrFail($id);

            // Update status petugas
            $petugas->update([
                'status' => $request->status
            ]);

            // Jika status disetujui, ubah level user menjadi 3
            if ($request->status === 'Disetujui') {
                $user = User::find($petugas->user_id);
                if ($user) {
                    $user->update([
                        'level' => 3, // Set level menjadi 3 untuk petugas
                        'email_verified_at' => $user->email_verified_at ?? now()
                    ]);

                    // Update role_id di tabel model_has_roles menjadi 3 (petugas)
                    DB::table('model_has_roles')
                        ->where('model_id', $user->id)
                        ->where('model_type', 'App\\Models\\User')
                        ->update(['role_id' => 3]);

                    // Kirim notifikasi jika disetujui
                    $user->notify(new CustomVerifyUser());
                }
            }

            // Jika status ditolak, kembalikan level user ke default
            if ($request->status === 'Ditolak') {
                $user = User::find($petugas->user_id);
                if ($user) {
                    $user->update([
                        'level' => 4 // Kembalikan ke level user biasa (konsisten dengan UserController)
                    ]);

                    // Update role_id di tabel model_has_roles menjadi 4 (masyarakat)
                    DB::table('model_has_roles')
                        ->where('model_id', $user->id)
                        ->where('model_type', 'App\\Models\\User')
                        ->update(['role_id' => 4]);
                }
            }

            // Commit transaksi
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            // Rollback transaksi jika ada error
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject petugas application
     */
    public function reject($id)
    {
        try {
            DB::beginTransaction();

            // Cari user berdasarkan ID
            $user = User::findOrFail($id);

            // Cari data petugas berdasarkan user_id
            $petugas = Petugas::where('user_id', $id)->first();

            if (!$petugas) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Data petugas tidak ditemukan.');
            }

            // Update status petugas menjadi 'Ditolak'
            $petugas->update([
                'status' => 'Ditolak'
            ]);

            // Kembalikan level user ke 4 (user biasa)
            $user->update([
                'level' => 4
            ]);

            // Update role_id di tabel model_has_roles menjadi 4 (masyarakat)
            DB::table('model_has_roles')
                ->where('model_id', $user->id)
                ->where('model_type', 'App\\Models\\User')
                ->update(['role_id' => 4]);

            DB::commit();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Aplikasi petugas berhasil ditolak.'
                ]);
            }

            return redirect()->route('manage-petugas.index')
                ->with('success', 'Aplikasi petugas berhasil ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menolak aplikasi: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Gagal menolak aplikasi: ' . $e->getMessage());
        }
    }
}
