<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="{{ asset('') }}assets/images/favicon-32x32.png" type="image/png" />
    <!-- loader-->
    <link href="{{ asset('') }}assets/css/pace.min.css" rel="stylesheet" />
    <script src="{{ asset('') }}assets/js/pace.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="{{ asset('') }}assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('') }}assets/css/bootstrap-extended.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="{{ asset('') }}assets/css/app.css" rel="stylesheet">
    <link href="{{ asset('') }}assets/css/icons.css" rel="stylesheet">
    <title>{{ env('APP_NAME', 'PBL IK-TI') }}</title>
</head>

<body class="">
    <!--wrapper-->
    <div class="wrapper">
        <div class="section-authentication-cover">
            <div class="">
                <div class="row g-0">

                    <div
                        class="col-12 col-xl-7 col-xxl-8 auth-cover-left align-items-center justify-content-center d-none d-xl-flex">

                        <div class="card shadow-none bg-transparent shadow-none rounded-0 mb-0">
                            <div class="card-body">
                                <img src="{{ asset('') }}assets/images/login-images/register-cover.svg"
                                    class="img-fluid auth-img-cover-login" width="500" alt="" />
                            </div>
                        </div>

                    </div>

                    <div class="col-12 col-xl-5 col-xxl-4 auth-cover-right align-items-center justify-content-center">
                        <div class="card rounded-0 m-3 shadow-none bg-transparent mb-0">
                            <div class="card-body p-sm-5">
                                <div class="">
                                    <div class="text-center mb-2">
                                        <h3 class="">PENDAFTARAN AKUN</h3>
                                    </div>
                                    <div class="form-body">
                                        <form class="row g-3" action="{{ route('register.store') }}" method="POST">
                                            @csrf
                                            <div class="col-12">
                                                <label for="inputUsername" class="form-label">Nama Lengkap</label>
                                                <input type="text" name="name"
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    value="{{ old('name') }}" id="inputUsername"
                                                    placeholder="Nama Lengkap Sesuai Ijazah">
                                                @error('name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-12">
                                                <label for="inputEmailAddress" class="form-label">Alamat Email</label>
                                                <input type="email" name="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    id="inputEmailAddress" placeholder="Alamat Email Aktif"
                                                    value="{{ old('email') }}">
                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-12">
                                                <label for="inputChoosePassword" class="form-label">Password</label>
                                                <div class="input-group" id="show_hide_password">
                                                    <input type="password" name="password"
                                                        class="form-control border-end-0 @error('password') is-invalid @enderror"
                                                        id="inputChoosePassword" value=""
                                                        placeholder="Enter Password"> <a href="javascript:;"
                                                        class="input-group-text bg-transparent"><i
                                                            class='bx bx-hide'></i></a>
                                                    @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label for="inputChoosePassword" class="form-label">Ulangi
                                                    Password</label>
                                                <div class="input-group" id="show_hide_password1">
                                                    <input type="password" name="password_confirmation"
                                                        id="password-confirm" class="form-control border-end-0"
                                                        id="inputChoosePassword" value=""
                                                        placeholder="Enter Password" autocomplete="new-password"> <a
                                                        href="javascript:;" class="input-group-text bg-transparent"><i
                                                            class='bx bx-hide'></i></a>
                                                </div>
                                            </div>

                                            {{-- START Wilayah --}}
                                            {{-- Script ada dibawah --}}
                                            <div class="form-group">
                                                <label for="province">Provinsi</label>
                                                <select name="province_id" id="province" class="form-control">
                                                    <option>Pilih Provinsi</option>
                                                    @foreach($provinces as $province)
                                                        <option value="{{ $province->id }}">{{ $province->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="regency">Kabupaten/Kota</label>
                                                <select name="regency_id" id="regency" class="form-control">
                                                    <option>Pilih Kabupaten</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="district">Kecamatan</label>
                                                <select name="district_id" id="district" class="form-control">
                                                    <option>Pilih Kecamatan</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="village">Desa/Kelurahan</label>
                                                <select name="village_id" id="village" class="form-control">
                                                    <option>Pilih Desa</option>
                                                </select>
                                            </div>

                                            {{-- END Wilayah --}}

                                            <div class="col-12">
                                                <label for="alamat" class="form-label">Alamat Lengkap</label>
                                                <textarea name="alamat" id="alamat" class="form-control" placeholder="Masukan Alamat Lengkap" rows="5"></textarea>
                                            </div>
                                            <div class="col-12">
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-primary btn-sm">Daftar</button>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="text-center ">
                                                    <p class="mb-0">Sudah memiliki akun ? <a
                                                            href="{{ route('login') }}">Masuk</a></p>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!--end row-->
            </div>
        </div>
    </div>
    <!--end wrapper-->
    <!-- Bootstrap JS -->
    <script src="{{ asset('') }}assets/js/bootstrap.bundle.min.js"></script>
    <!--plugins-->
    <script src="{{ asset('') }}assets/js/jquery.min.js"></script>
    <script src="{{ asset('') }}assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="{{ asset('') }}assets/plugins/metismenu/js/metisMenu.min.js"></script>
    <script src="{{ asset('') }}assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <!--Password show & hide js -->
    <script>
        $(document).ready(function() {
            $("#show_hide_password a").on('click', function(event) {
                event.preventDefault();
                if ($('#show_hide_password input').attr("type") == "text") {
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass("bx-hide");
                    $('#show_hide_password i').removeClass("bx-show");
                } else if ($('#show_hide_password input').attr("type") == "password") {
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass("bx-hide");
                    $('#show_hide_password i').addClass("bx-show");
                }
            });
            $("#show_hide_password1 a").on('click', function(event) {
                event.preventDefault();
                if ($('#show_hide_password1 input').attr("type") == "text") {
                    $('#show_hide_password1 input').attr('type', 'password');
                    $('#show_hide_password1 i').addClass("bx-hide");
                    $('#show_hide_password1 i').removeClass("bx-show");
                } else if ($('#show_hide_password1 input').attr("type") == "password") {
                    $('#show_hide_password1 input').attr('type', 'text');
                    $('#show_hide_password1 i').removeClass("bx-hide");
                    $('#show_hide_password1 i').addClass("bx-show");
                }
            });
        });
    </script>
    <!--app JS-->
    <script src="{{ asset('') }}assets/js/app.js"></script>

    {{-- START Wilayah --}}

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#province').change(function () {
            var id = $(this).val();
            $.get('/get-regencies/' + id, function (data) {
                $('#regency').empty().append('<option>Pilih Kabupaten</option>');
                $('#district').empty().append('<option>Pilih Kecamatan</option>');
                $('#village').empty().append('<option>Pilih Desa</option>');
                $.each(data, function (index, item) {
                    $('#regency').append('<option value="' + item.id + '">' + item.name + '</option>');
                });
            });
        });

        $('#regency').change(function () {
            var id = $(this).val();
            $.get('/get-districts/' + id, function (data) {
                $('#district').empty().append('<option>Pilih Kecamatan</option>');
                $('#village').empty().append('<option>Pilih Desa</option>');
                $.each(data, function (index, item) {
                    $('#district').append('<option value="' + item.id + '">' + item.name + '</option>');
                });
            });
        });

        $('#district').change(function () {
            var id = $(this).val();
            $.get('/get-villages/' + id, function (data) {
                $('#village').empty().append('<option>Pilih Desa</option>');
                $.each(data, function (index, item) {
                    $('#village').append('<option value="' + item.id + '">' + item.name + '</option>');
                });
            });
        });
    </script>

    {{-- END Wilayah --}}

</body>

</html>
