<?php

namespace App\Http\Controllers;

use App\Models\JadwalTemplate;
use App\Models\JadwalTemplatePetugas;
use App\Models\Armada;
use App\Models\Rute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JadwalTemplateController extends Controller
{
    public function index()
    {
        $hariList = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
        return view('adminpusat.jadwal-template.index', compact('hariList'));
    }

    // public function index()
    // {
    //     $hariList = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
    //     $templates = JadwalTemplate::with('armada', 'rute', 'petugasTemplate.petugas')
    //         ->where('hari', 'Senin') // default hari Senin
    //         ->get();

    //     return view('adminpusat.jadwal-template.index', compact('hariList', 'templates'));
    // }

    public function filterByDay($hari)
    {
        $templates = JadwalTemplate::with('armada', 'rute', 'petugasTemplate.petugas')
            ->where('hari', $hari)->get();
        return response()->json($templates);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'hari' => 'required',
            'id_armada' => 'required|exists:armada,id',
            'id_rute' => 'required|exists:rute,id',
            'petugas' => 'required|array',
            'petugas.*.id_petugas' => 'required|exists:users,id',
            'petugas.*.tugas' => 'required|in:1,2',
        ]);

        DB::beginTransaction();
        try {
            $template = JadwalTemplate::create([
                'hari' => $data['hari'],
                'id_armada' => $data['id_armada'],
                'id_rute' => $data['id_rute'],
            ]);

            foreach ($data['petugas'] as $p) {
                JadwalTemplatePetugas::create([
                    'id_jadwal_template' => $template->id,
                    'id_petugas' => $p['id_petugas'],
                    'tugas' => $p['tugas'],
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Template berhasil ditambahkan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal menyimpan template: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $template = JadwalTemplate::findOrFail($id);
        $template->petugasTemplate()->delete();
        $template->delete();
        return response()->json(['message' => 'Template berhasil dihapus']);
        // return view('adminpusat.jadwal-template.index');
    }
}
