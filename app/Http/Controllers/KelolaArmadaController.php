<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Armada;

class KelolaArmadaController extends Controller
{
    public function index()
    {
        // Menampilkan daftar armada
        $armada = Armada::all();

        return view('adminpusat.manage-armada.index', compact('armada'));
    }

    public function create()
    {
        // Menampilkan form untuk menambah armada
    }

    public function store(Request $request)
    {
        // Menyimpan armada baru
    }

    public function show($id)
    {
        // Menampilkan detail armada
    }

    public function edit($id)
    {
        // Menampilkan form untuk mengedit armada
    }

    public function update(Request $request, $id)
    {
        // Memperbarui data armada
    }

    public function destroy($id)
    {
        // Menghapus armada
    }
}