<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TuNetic</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    @if(auth()->check())
        @php
            $level = auth()->user()->level;
            $redirect = match ($level) {
                4 => '/masyarakat',
                1 => '/admin',
                default => '/home'
            };
        @endphp
        <script>window.location.href = "{{ $redirect }}";</script>
        @exit
    @endif

    <div class="container">
        <!-- Login Form -->
        <div class="form-box login">
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <h1>Login</h1>
                <div class="input-box">
                    <input type="email" name="email" placeholder="Alamat Email" required value="{{ old('email') }}">
                    <i class='bx bxs-user'></i>
                    @error('email')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="input-box">
                    <input type="password" name="password" placeholder="Password" required>
                    <i class='bx bxs-lock-alt'></i>
                    @error('password')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="forgot-link">
                    <a href="{{ route('password.request') }}">Lupa Password?</a>
                </div>
                <button type="submit" class="btn">Login</button>
                <p>or login with social platforms</p>
                <div class="social-icons">
                    <a href="#"><i class='bx bxl-google'></i></a>
                    <a href="#"><i class='bx bxl-facebook'></i></a>
                    <a href="#"><i class='bx bxl-github'></i></a>
                    <a href="#"><i class='bx bxl-linkedin'></i></a>
                </div>
            </form>
        </div>

        <!-- Register Form -->
        <div class="form-box register">
            <form action="{{ route('register') }}" method="POST">
                @csrf
                <h1>Registration</h1>
                <div class="input-box">
                    <input type="text" name="name" placeholder="Username" required value="{{ old('name') }}">
                    <i class='bx bxs-user'></i>
                    @error('name')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="input-box">
                    <input type="email" name="email" placeholder="Email" required value="{{ old('email') }}">
                    <i class='bx bxs-envelope'></i>
                    @error('email')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="input-box">
                    <input type="password" name="password" placeholder="Password" required>
                    <i class='bx bxs-lock-alt'></i>
                    @error('password')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="input-box">
                    <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <button type="submit" class="btn">Register</button>
                <p>or register with social platforms</p>
                <div class="social-icons">
                    <a href="#"><i class='bx bxl-google'></i></a>
                    <a href="#"><i class='bx bxl-facebook'></i></a>
                    <a href="#"><i class='bx bxl-github'></i></a>
                    <a href="#"><i class='bx bxl-linkedin'></i></a>
                </div>
                <p class="switch-login">Already have an account? <a href="{{ route('login') }}">Login</a></p>
            </form>
        </div>

        <!-- Toggle Box -->
        <div class="toggle-box">
            <div class="toggle-panel toggle-left">
                <h1>Hello, Welcome!</h1>
                <p>Don't have an account?</p>
                <button class="btn register-btn">Register</button>
            </div>
            <div class="toggle-panel toggle-right">
                <h1>Welcome Back!</h1>
                <p>Already have an account?</p>
                <button class="btn login-btn">Login</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const params = new URLSearchParams(window.location.search);
            const mode = params.get("mode");

            if (mode === "register") {
                document.querySelector('.container').classList.add('active');
            } else {
                document.querySelector('.container').classList.remove('active');
            }
        });
    </script>

</body>

</html>