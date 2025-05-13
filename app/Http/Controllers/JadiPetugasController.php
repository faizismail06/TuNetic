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

class JadiPetugasController extends Controller
{
    public function index()
    {
        $petugas = Petugas::all(); // Mengambil semua data dari tabel petugas
        return view('masyarakat.jadipetugas.index', compact('jadi petugas')); // Mengirim data ke view 'petugas.index'
}}