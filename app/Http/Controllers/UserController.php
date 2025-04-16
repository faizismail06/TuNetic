<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:read_user')->only('index', 'show');
        $this->middleware('permission:create_user')->only('create', 'store');
        $this->middleware('permission:update_user')->only('edit', 'update');
        $this->middleware('permission:delete_user')->only('destroy');
    }

    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email:rfc|unique:users',
            'role' => 'nullable',
            'level' => 'required|integer|in:1,2,3,4',
            'alamat' => 'nullable|string',
            'verified' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            toastr()->error('Pengguna gagal ditambah </br> Periksa kembali data anda');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'level' => $request->level,
                'email_verified_at' => !blank($request->verified) ? now() : null
            ]);

            $data->assignRole(!blank($request->role) ? $request->role : []);
            toastr()->success('Pengguna baru berhasil disimpan');
            return redirect()->route('manage-user.index');
        } catch (\Throwable $th) {
            dd($th);
            toastr()->warning('Terdapat masalah di server');
            return redirect()->route('manage-user.index');
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $roles = Role::all();
        $user = User::findOrFail($id);
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email:rfc',
            'role' => 'nullable',
            'level' => 'required|integer|in:1,2,3,4',
            'alamat' => 'nullable|string',
            'verified' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            toastr()->error('Pengguna gagal diperbarui </br> Periksa kembali data anda');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $user = User::findOrFail($id);

            $update_data = [
                'name' => $request->name,
                'email' => $request->email,
                'level' => $request->level,
                'email_verified_at' => !blank($request->verified) ? now() : null
            ];

            if (!empty($request->password)) {
                $update_data['password'] = Hash::make($request->password);
            }

            $user->update($update_data);

            $user->syncRoles(!blank($request->role) ? $request->role : []);
            toastr()->success('Pengguna berhasil diperbarui');
            return redirect()->route('manage-user.index');
        } catch (\Throwable $th) {
            toastr()->warning('Terdapat masalah di server');
            return redirect()->route('manage-user.index');
        }
    }

    public function destroy($id)
    {
        //
    }
}
