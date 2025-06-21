<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\CustomVerifyUser;

class VerifyUserController extends Controller
{
    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->notify(new CustomVerifyUser());

        return redirect()->route('manage-petugas.index')->with('success', 'Berhasil menyetujui petugas.');
    }
}
