<?php

namespace App\Http\Controllers;

use App\Models\LaporanTps;
use Illuminate\Http\Request;

class SampahPusatController extends Controller
{
    public function index()
    {
        $LaporanTps = LaporanTps::with(['jadwalOperasional.armada', 'jadwalOperasional.rute'])
            ->orderByDesc('tanggal_pengangkutan')
            ->get();

        return view('adminpusat.perhitungan-sampah.index', compact('LaporanTps'));
    }
}

