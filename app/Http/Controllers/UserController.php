<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;

class UserController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:read_user')->only('index', 'show');
        $this->middleware('permission:create_user')->only('create', 'store');
        $this->middleware('permission:update_user')->only('edit', 'update');
        $this->middleware('permission:delete_user')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function showRegistrationForm()
    {
        $provinces = Province::all(); // atau Model yang sesuai
        return view('profile/index', compact('provinces'));
    }
    public function getRegencies($province_id)
    {
        return response()->json(Regency::where('province_id', $province_id)->get());
    }

    public function getDistricts($regency_id)
    {
        return response()->json(District::where('regency_id', $regency_id)->get());
    }

    public function getVillages($district_id)
    {
        return response()->json(Village::where('district_id', $district_id)->get());
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email:rfc|unique:users',
            'role' => 'nullable',
            'no_telepon' => 'required|integer',
            'province_id'  => 'required|exists:reg_provinces,id',
            'regency_id'  => 'required|exists:reg_regencies,id',
            'district_id'  => 'required|exists:reg_districts,id',
            'village_id'  => 'required|exists:reg_villages,id',
            'level' => 'required|integer|in:1,2,3,4',
            'verified' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            toastr()->error('Perngguna gagal ditambah </br> Periksa kembali data anda');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        ;
        try {
            $data = User::create(
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'province_id' => $request->province_id,
                    'regency_id' => $request->regency_id,
                    'district_id' => $request->district_id,
                    'village_id' => $request->village_id,
                    'level' => $request->level,
                    'email_verified_at' => !blank($request->verified) ? now() : null
                ]
            );
            $data->assignRole(!blank($request->role) ? $request->role : array());
            toastr()->success('Pengguna baru berhasil disimpan');
            return redirect()->route('manage-user.index');
        } catch (\Throwable $th) {
            dd($th);
            toastr()->warning('Terdapat masalah diserver');
            return redirect()->route('manage-user.index');
        }
    }

    public function storePublic(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email:rfc|unique:users',
            'role' => 'nullable',
            'province_id'  => 'required|exists:reg_provinces,id',
            'regency_id'  => 'required|exists:reg_regencies,id',
            'district_id'  => 'required|exists:reg_districts,id',
            'village_id'  => 'required|exists:reg_villages,id',
            'verified' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            toastr()->error('Pengguna gagal ditambah </br> Periksa kembali data anda');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        ;
        try {
            $data = User::create(
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'province_id' => $request->province_id,
                    'regency_id' => $request->regency_id,
                    'district_id' => $request->district_id,
                    'village_id' => $request->village_id,
                    'level' => 4,
                    'email_verified_at' => !blank($request->verified) ? now() : null
                ]
            );
            $data->assignRole('user'); // Tambahkan ini agar user mendapat permission-nya
            toastr()->success('Pengguna baru berhasil disimpan');
            return redirect()->route('login');
        } catch (\Throwable $th) {
            dd($th);
            toastr()->warning('Terdapat masalah diserver');
            return redirect()->route('login');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $roles = Role::all();
        $user = User::findorfail($id);
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email:rfc',
            'role' => 'nullable',
            'verified' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            toastr()->error('Perngguna gagal ditambah </br> Periksa kembali data anda');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        ;

        try {
            $user = User::findorfail($id);

            $update_data = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => !blank($request->verified) ? now() : null
            ];
            if (empty($request->password)) {
                unset($update_data['password']);
            }
            $user->update($update_data);

            $user->syncRoles(!blank($request->role) ? $request->role : array());
            toastr()->success('Pengguna berhasil diperbarui');
            return redirect()->route('manage-user.index');
        } catch (\Throwable $th) {
            toastr()->warning('Terdapat masalah diserver');
            return redirect()->route('manage-user.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
