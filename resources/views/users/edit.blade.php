@extends('layouts.app')

@push('css')
@endpush

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 text-uppercase">
                    <h4 class="m-0">Edit Pengguna</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-success card-outline">
                        <div class="card-header">
                            <h5 class="card-title m-0"></h5>
                            <div class="card-tools">
                                <a href="{{ route('manage-user.index') }}" class="btn btn-tool">
                                    <i class="fas fa-arrow-alt-circle-left"></i>
                                </a>
                            </div>
                        </div>

                        <form action="{{ route('manage-user.update', $user->id) }}" method="post">
                            @csrf
                            @method('PUT')

                            <div class="card-body">
                                <div class="form-group">
                                    <label>Nama Lengkap</label>
                                    <input type="text" name="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           placeholder="Nama Lengkap"
                                           value="{{ $user->name }}">
                                    @error('name')
                                        <div class="invalid-feedback" role="alert">
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Alamat Email</label>
                                    <input type="email" name="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           placeholder="Alamat Email"
                                           value="{{ $user->email }}">
                                    @error('email')
                                        <div class="invalid-feedback" role="alert">
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="Password">
                                </div>

                                <div class="form-group">
                                    <label>Role Pengguna</label>
                                    <select name="role" class="form-control @error('role') is-invalid @enderror">
                                        <option value="">-- Pilih Role --</option>
                                        @foreach ($roles as $item)
                                            <option value="{{ strtolower($item->name) }}"
                                                {{ $user->roles->pluck('name')->contains($item->name) ? 'selected' : '' }}>
                                                {{ ucfirst($item->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback" role="alert">
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Verified</label>
                                    <div class="input-group">
                                        <input type="checkbox" name="verified"
                                            data-bootstrap-switch
                                            data-off-color="danger"
                                            data-on-color="success"
                                            {{ !blank($user->email_verified_at) ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-info btn-block btn-flat">
                                    <i class="fa fa-save"></i> Simpan
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('js')
<script src="{{ asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
<script>
    $(function () {
        // Bootstrap Switch init
        $("input[data-bootstrap-switch]").each(function () {
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
        });

        // Role to level mapping
        const roleLevelMap = {
            'superadmin': 1,
            'admin': 2,
            'petugas': 3,
            'user': 4
        };

        const roleSelect = document.getElementById('role-select');
        const levelInput = document.getElementById('level');

        function setLevel() {
            const selectedRole = roleSelect.value;
            levelInput.value = roleLevelMap[selectedRole] ?? '';
        }

        setLevel(); // initial
        roleSelect.addEventListener('change', setLevel);
    });
</script>
@endpush
