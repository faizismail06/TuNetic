<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('') }}assets/images/favicon-32x32.png" type="image/png" />
    <link href="{{ asset('') }}assets/css/pace.min.css" rel="stylesheet" />
    <script src="{{ asset('') }}assets/js/pace.min.js"></script>
    <link href="{{ asset('') }}assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('') }}assets/css/bootstrap-extended.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="{{ asset('') }}assets/css/app.css" rel="stylesheet">
    <link href="{{ asset('') }}assets/css/icons.css" rel="stylesheet">
    <title>TuNetic - Lupa Password</title>

    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(90deg, #e2e2e2, #299e63);
        }

        .wrapper {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .auth-card {
            background-color: #fff;
            border-radius: 25px; /* Rounded corners for the main card */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden; /* Important for rounded corners on children */
            display: flex;
            max-width: 900px; /* Max width for the card */
            width: 90%;
        }

        .auth-left {
            background-color: #299e63; /* Main green color from the image */
            color: #fff;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            border-top-left-radius: 25px;
            border-bottom-left-radius: 25px;
            border-top-right-radius: 150px; /* Creates the curve */
            border-bottom-right-radius: 150px; /* Creates the curve */
            position: relative; /* Needed for the curve effect */
            width: 45%; /* Adjust width as needed */
        }
         /* Simple curve with border-radius */
         @media (max-width: 991.98px) {
            .auth-left {
                 border-top-right-radius: 25px;
                 border-bottom-right-radius: 25px;
                 border-bottom-left-radius: 0;
                 width: 100%;
                 padding: 30px;
            }
            .auth-right{
                border-bottom-left-radius: 25px;
                border-bottom-right-radius: 25px;
                border-top-right-radius: 0;
            }
             .auth-card{
                flex-direction: column;
             }
         }


        .auth-left h2 {
            font-weight: 500;
            margin-bottom: 15px;
        }

        .auth-left p {
            margin-bottom: 30px;
            opacity: 0.9;
        }

        .auth-left .btn-register {
            background-color: #fff;
            color: #299e63;
            border: 2px solid #fff;
            border-radius: 25px;
            padding: 10px 30px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .auth-left .btn-register:hover {
            background-color: transparent;
            color: #fff;
        }

        .auth-right {
            background-color: #fff;
            padding: 50px;
            width: 55%; /* Adjust width as needed */
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

         @media (max-width: 991.98px) {
            .auth-right {
                width: 100%;
                 padding: 30px;
            }
         }


        .auth-right h3 {
            color: #333;
            font-weight: 500;
            margin-bottom: 10px;
            text-align: center;
        }
         .auth-right .form-label {
            color: #555;
            font-weight: 500;
         }

        .auth-right .form-control {
            border-radius: 25px;
            border: 1px solid #ddd;
            padding: 10px 20px;
            height: 50px;
        }
        .auth-right .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(0, 137, 123, 0.25);
            border-color: #299e63;
        }

        .auth-right .btn-submit {
            background-color: #299e63;
            color: #fff;
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 500;
            width: 100%;
            transition: all 0.3s ease;
            margin-top: 15px; /* Add some space above the button */
        }

        .auth-right .btn-submit:hover {
            background-color: #006a60; /* Darker shade on hover */
        }
        .auth-right .back-link {
            text-align: center;
            margin-top: 20px;
        }
        .auth-right .back-link a {
            color: #299e63;
            text-decoration: none;
            font-weight: 500;
        }
         .auth-right .back-link a:hover {
             text-decoration: underline;
         }
        .auth-right .input-group-text {
             background-color: transparent;
             border: none;
             padding-right: 15px;
        }
        .alert-success {
             background-color: #d4edda;
             color: #155724;
             padding: 10px;
             border-radius: 5px;
             margin-bottom: 15px;
             text-align: center;
        }


    </style>
</head>

<body>
    <div class="wrapper">
        <div class="auth-card">

            <div class="auth-left d-none d-xl-flex">
                <div>
                  <h2>Lupa Password?</h2>
                  <p>Jangan khawatir! Masukkan email Anda dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.</p>
                   <img src="{{ asset('') }}assets/images/login-images/forgot-password-cover.svg"
                         class="img-fluid" width="350" alt="" />
                </div>
            </div>

            <div class="auth-right">
                <div class="form-body">
                    <h3 class="mb-4">RESET PASSWORD</h3>

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="row g-3" action="{{ route('password.email') }}" method="POST">
                        @csrf
                        <div class="col-12">
                            <label for="inputEmailAddress" class="form-label">Alamat Email</label>
                            <div class="input-group">
                                 <input type="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="inputEmailAddress" placeholder="Masukkan Alamat Email"
                                   value="{{ old('email') }}" required>
                                <span class="input-group-text"><i class='bx bx-envelope'></i></span>
                            </div>
                            @error('email')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-12">
                            <div class="d-grid">
                                <button type="submit" class="btn btn-submit">Kirim Tautan Reset</button>
                            </div>
                        </div>

                         <div class="col-12 back-link">
                             <a href="{{ route('login') }}"><i class='bx bx-arrow-back me-1'></i>Kembali ke Login</a>
                         </div>
                    </form>
                </div>
            </div>

        </div></div><script src="{{ asset('') }}assets/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('') }}assets/js/jquery.min.js"></script>
    {{-- <script src="{{ asset('') }}assets/plugins/simplebar/js/simplebar.min.js"></script> --}}
    {{-- <script src="{{ asset('') }}assets/plugins/metismenu/js/metisMenu.min.js"></script> --}}
    {{-- <script src="{{ asset('') }}assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script> --}}
    {{-- <script src="{{ asset('') }}assets/js/app.js"></script> --}}
</body>

</html>