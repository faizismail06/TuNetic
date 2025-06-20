@extends('layouts.app')
@push('css')
@endpush
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 text-uppercase">
                    <h4 class="m-0">Tambah Pengguna</h4>
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
                                <a href="{{ route('manage-user.index') }}" class="btn btn-tool"><i
                                        class="fas fa-arrow-alt-circle-left"></i></a>
                            </div>
                        </div>
                        <form action="{{ route('manage-user.store') }}" method="post">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Nama Lengkap</label>
                                    <input type="text" name="name"
                                        class="form-control @error('name')is-invalid @enderror" placeholder="Nama Lengkap">
                                    @error('name')
                                        <div class="invalid-feedback" role="alert">
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Alamat Email</label>
                                    <input type="email" name="email"
                                        class="form-control @error('email')is-invalid @enderror "
                                        placeholder="Alamat Email">
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
                                    <label>Level Pengguna</label>
                                    <select name="level" class="form-control @error('level') is-invalid @enderror" required>
                                        <option value="">-- Pilih Level --</option>
                                        <option value="1">Admin</option>
                                        <option value="2">Staff</option>
                                        <option value="3">User Biasa</option>
                                        <option value="4">Publik</option>
                                    </select>
                                    @error('level')
                                        <div class="invalid-feedback" role="alert">
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Verified</label>
                                    <div class="input-group">
                                        <input type="checkbox" name="verified" data-bootstrap-switch data-off-color="danger"
                                            data-on-color="success">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-info btn-block btn-flat"><i class="fa fa-save"></i>
                                    Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ asset('') }}plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
    <script>
        $(function() {
            $("input[data-bootstrap-switch]").each(function() {
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            })
        })
            const roleLevelMap = {
        'admin': 1,
        'staff': 2,
        'user': 3,
        'publik': 4
    };

    $('input[name="role[]"]').on('change', function () {
        let selectedRole = $('input[name="role[]"]:checked').first().val(); // ambil role pertama yang dicentang
        let level = roleLevelMap[selectedRole] || 4; // default level = 4 jika tidak cocok
        $('#level').val(level);
    }); 

    // Set level saat halaman pertama kali dibuka (kalau ada role tercentang)
    $(document).ready(function () {
        $('input[name="role[]"]').trigger('change');
    });

    </script>
@endpush
