<?php

namespace App\Http\Controllers;
use App\Models\Armada;
use App\Models\LaporanTps;
use App\Models\JadwalOperasional;
use App\Models\Rute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DataTables;

class LaporanTpsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = LaporanTps::with([
                'jadwalOperasional.armada',
                'jadwalOperasional.rute'
            ])->select('laporan_tps.*');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('armada', function ($row) {
                    return $row->jadwalOperasional->armada->jenis_kendaraan . ' - ' .
                        $row->jadwalOperasional->armada->no_polisi;
                })
                ->addColumn('rute', function ($row) {
                    return $row->jadwalOperasional->rute->nama_rute . ' (' .
                        $row->jadwalOperasional->rute->wilayah . ')';
                })
                ->addColumn('tanggal_kirim', function ($row) {
                    return $row->jadwalOperasional->tanggal ?? '-';
                })
                ->addColumn('total_sampah_formatted', function ($row) {
                    return number_format($row->total_sampah, 2) . ' kg';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group" role="group">';
                    $btn .= '<button type="button" class="btn btn-sm btn-info" onclick="showDetail(' . $row->id . ')" title="Detail">
                                <i class="fas fa-eye"></i>
                            </button>';
                    $btn .= '<button type="button" class="btn btn-sm btn-warning" onclick="editData(' . $row->id . ')" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger" onclick="deleteData(' . $row->id . ')" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        // $jadwalOperasional = JadwalOperasional::with(['armada', 'rute'])->get();
        $LaporanTps = LaporanTps::with('jadwalOperasional.armada', 'jadwalOperasional.rute')->get();
        return view('admintpst.perhitungan-sampah.index', compact('LaporanTps'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jadwalOperasional = JadwalOperasional::where('status', '!=', 2)->with(['armada', 'rute'])->get();

        $armadas = $jadwalOperasional->map->armada->unique('id')->values();  // values() agar indeks rapi
        $rutes = $jadwalOperasional->map->rute->unique('id')->values();

        return view('admintpst.perhitungan-sampah.create', compact('jadwalOperasional', 'armadas', 'rutes'));
    }


    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'id_jadwal_operasional' => 'required|exists:jadwal_operasional,id',
    //         'total_sampah' => 'required|numeric|min:0',
    //         'deskripsi' => 'required|string|max:1000',
    //         'tanggal_pengangkutan' => 'nullable|date',
    //     ], [
    //         'id_jadwal_operasional.required' => 'Jadwal operasional harus dipilih',
    //         'id_jadwal_operasional.exists' => 'Jadwal operasional tidak valid',
    //         'total_sampah.required' => 'Total sampah harus diisi',
    //         'total_sampah.numeric' => 'Total sampah harus berupa angka',
    //         'total_sampah.min' => 'Total sampah tidak boleh kurang dari 0',
    //         'deskripsi.required' => 'Deskripsi harus diisi',
    //         'deskripsi.max' => 'Deskripsi maksimal 1000 karakter',
    //         'tanggal_pengangkutan.date' => 'Format tanggal pengangkutan tidak valid',
    //     ]);

    //     // if ($validator->fails()) {
    //     //     return response()->json([
    //     //         'success' => false,
    //     //         'message' => 'Validasi gagal',
    //     //         'errors' => $validator->errors()
    //     //     ], 422);
    //     // }

    //     // try {
    //     //     $laporanTps = LaporanTps::create($request->all());

    //     //     return response()->json([
    //     //         'success' => true,
    //     //         'message' => 'Laporan TPS berhasil ditambahkan',
    //     //         'data' => $laporanTps
    //     //     ]);
    //     // } catch (\Exception $e) {
    //     //     return response()->json([
    //     //         'success' => false,
    //     //         'message' => 'Gagal menambahkan laporan TPS: ' . $e->getMessage()
    //     //     ], 500);
    //     // }
    //     // LaporanTps::create($validated);

    //     // Redirect ke halaman index dengan notifikasi sukses
    //     return redirect()->route('perhitungan-sampah.index')
    //     ->with('success', 'Laporan TPS berhasil ditambahkan!');
    // }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_pengangkutan' => 'required|date',
            'id_armada' => 'required|exists:armada,id',
            'id_rute' => 'required|exists:rute,id',
            'total_sampah' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string|max:1000',
        ]);

        // Cari jadwal_operasional berdasarkan armada, rute, dan tanggal
        $jadwalOperasional = JadwalOperasional::where('id_armada', $request->id_armada)
            ->where('id_rute', $request->id_rute)
            ->where('tanggal', $request->tanggal_pengangkutan)
            ->first();

        if (!$jadwalOperasional) {
            return back()->withErrors(['jadwal_operasional' => 'Jadwal operasional tidak ditemukan untuk armada, rute, dan tanggal tersebut'])->withInput();
        }

        $existingLaporan = LaporanTps::where('id_jadwal_operasional', $jadwalOperasional->id)->first();

        if ($existingLaporan) {
            return back()->withErrors(['duplicate' => 'Laporan untuk jadwal ini sudah pernah dibuat'])->withInput();
        }


        // Simpan laporan TPS
        LaporanTps::create([
            'id_jadwal_operasional' => $jadwalOperasional->id,
            'total_sampah' => $request->total_sampah,
            'deskripsi' => $request->deskripsi ?? '',
            'tanggal_pengangkutan' => $request->tanggal_pengangkutan,
        ]);

        return redirect()->route('perhitungan-sampah.index')->with('success', 'Laporan berhasil disimpan!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $laporanTps = LaporanTps::with([
                'jadwalOperasional.armada',
                'jadwalOperasional.rute',
                'jadwalOperasional.jadwal'
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $laporanTps
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan TPS tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = LaporanTps::with('jadwalOperasional.armada', 'jadwalOperasional.rute')->findOrFail($id);

        $armadas = Armada::all();
        $rutes = Rute::all();

        return view('admintpst.perhitungan-sampah.edit', compact('data', 'armadas', 'rutes'));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // $validator = Validator::make($request->all(), [
        //     'id_jadwal_operasional' => 'required|exists:jadwal_operasional,id',
        //     'total_sampah' => 'required|numeric|min:0',
        //     'deskripsi' => 'required|string|max:1000',
        //     'tanggal_pengangkutan' => 'nullable|date',
        // ], [
        //     'id_jadwal_operasional.required' => 'Jadwal operasional harus dipilih',
        //     'id_jadwal_operasional.exists' => 'Jadwal operasional tidak valid',
        //     'total_sampah.required' => 'Total sampah harus diisi',
        //     'total_sampah.numeric' => 'Total sampah harus berupa angka',
        //     'total_sampah.min' => 'Total sampah tidak boleh kurang dari 0',
        //     'deskripsi.required' => 'Deskripsi harus diisi',
        //     'deskripsi.max' => 'Deskripsi maksimal 1000 karakter',
        //     'tanggal_pengangkutan.date' => 'Format tanggal pengangkutan tidak valid',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Validasi gagal',
        //         'errors' => $validator->errors()
        //     ], 422);
        // }

        // try {
        //     $laporanTps = LaporanTps::findOrFail($id);
        //     $laporanTps->update($request->all());

        //     return response()->json([
        //         'success' => true,
        //         'message' => 'Laporan TPS berhasil diperbarui',
        //         'data' => $laporanTps
        //     ]);
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Gagal memperbarui laporan TPS: ' . $e->getMessage()
        //     ], 500);
        // }

        $request->validate([
            'tanggal_pengangkutan' => 'required|date',
            'id_armada' => 'required',
            'id_rute' => 'required',
            'total_sampah' => 'required|numeric',
        ]);

        $data = LaporanTps::findOrFail($id);
        $data->update([
            'tanggal_pengangkutan' => $request->tanggal_pengangkutan,
            'id_armada' => $request->id_armada,
            'id_rute' => $request->id_rute,
            'total_sampah' => $request->total_sampah,
        ]);

        return redirect()->route('perhitungan-sampah.index')->with('success', 'Data berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $laporan = LaporanTps::findOrFail($id);
            $laporan->delete();

            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data');
        }
    }

    /**
     * Get laporan by jadwal operasional
     */
    public function getByJadwalOperasional($id)
    {
        try {
            $laporan = LaporanTps::where('id_jadwal_operasional', $id)
                ->with(['jadwalOperasional.armada', 'jadwalOperasional.rute'])
                ->get();

            return response()->json([
                'success' => true,
                'data' => $laporan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data laporan'
            ], 500);
        }
    }

    /**
     * Get summary statistics
     */
    public function getSummary()
    {
        try {
            $totalLaporan = LaporanTps::count();
            $totalSampah = LaporanTps::sum('total_sampah');
            $laporanHariIni = LaporanTps::whereDate('created_at', today())->count();
            $rataRataSampah = LaporanTps::avg('total_sampah');

            return response()->json([
                'success' => true,
                'data' => [
                    'total_laporan' => $totalLaporan,
                    'total_sampah' => round($totalSampah, 2),
                    'laporan_hari_ini' => $laporanHariIni,
                    'rata_rata_sampah' => round($rataRataSampah, 2)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil summary data'
            ], 500);
        }
    }


}