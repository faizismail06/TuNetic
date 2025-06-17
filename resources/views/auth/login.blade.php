<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TuNetic</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
            text-decoration: none;
            list-style: none;
        }

        html, body {
            width: 100%;
            min-height: 100vh;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(45deg, #e2e2e2, #299e63, #34a86b, #3eb371);
            background-size: 400% 400%;
            animation: gradientShift 8s ease infinite;
            overflow: hidden;
            position: relative;
        }

        /* Animated background particles */
        .bg-particles {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }
        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }
        .particle:nth-child(1) { width: 80px; height: 80px; left: 10%; animation-delay: 0s; }
        .particle:nth-child(2) { width: 120px; height: 120px; left: 20%; animation-delay: 2s; }
        .particle:nth-child(3) { width: 60px; height: 60px; left: 30%; animation-delay: 4s; }
        .particle:nth-child(4) { width: 100px; height: 100px; left: 70%; animation-delay: 1s; }
        .particle:nth-child(5) { width: 90px; height: 90px; left: 80%; animation-delay: 3s; }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        @keyframes float {
            0%, 100% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-100px) rotate(360deg); opacity: 0; }
        }

        .container {
            position: relative;
            width: 850px;
            max-width: 98vw;
            height: 580px;
            max-height: 98vh;
            background: rgba(255, 255, 255, 0.95);
            margin: 20px;
            border-radius: 30px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: containerPulse 4s ease-in-out infinite;
            z-index: 1;
            display: flex;
            flex-direction: row;
            justify-content: flex-end;
        }
        @keyframes containerPulse {
            0%, 100% { transform: scale(1); box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25); }
            50% { transform: scale(1.02); box-shadow: 0 30px 60px rgba(0, 0, 0, 0.3); }
        }

        .container h1 {
            font-size: 32px;
            margin-bottom: 10px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 100%;
            background: linear-gradient(45deg, #299e63, #34a86b);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: titleGlow 3s ease-in-out infinite;
        }
        @keyframes titleGlow {
            0%, 100% { filter: brightness(1); }
            50% { filter: brightness(1.2); }
        }

        .form-box {
            position: absolute;
            right: 0;
            width: 50%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.7));
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #333;
            text-align: center;
            padding: 50px;
            z-index: 1;
            transition: all 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            backdrop-filter: blur(10px);
        }

        .container.active .form-box {
            right: 0;
            left: 0;
            width: 50%;
        }
        .form-box.register {
            visibility: hidden;
            opacity: 0;
            transform: translateX(50px);
        }
        .container.active .form-box.register {
            visibility: visible;
            opacity: 1;
            transform: translateX(0);
            transition-delay: 0.3s;
        }

        .input-box {
            position: relative;
            margin: 20px 0;
            width: 100%;
            animation: slideInUp 0.6s ease forwards;
            opacity: 0;
            transform: translateY(30px);
        }
        .input-box:nth-child(2) { animation-delay: 0.1s; }
        .input-box:nth-child(3) { animation-delay: 0.2s; }
        .input-box:nth-child(4) { animation-delay: 0.3s; }
        .input-box:nth-child(5) { animation-delay: 0.4s; }
        @keyframes slideInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .input-box input {
            width: 100%;
            padding: 14px 55px 14px 20px;
            background: rgba(238, 238, 238, 0.8);
            border-radius: 15px;
            border: 2px solid transparent;
            outline: none;
            font-size: 16px;
            color: #333;
            font-weight: 500;
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            backdrop-filter: blur(5px);
        }
        .input-box input:focus {
            background: rgba(255, 255, 255, 0.9);
            border-color: #299e63;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(41, 158, 99, 0.2);
        }
        .input-box input::placeholder {
            color: #888;
            font-weight: 400;
            transition: all 0.3s ease;
        }
        .input-box input:focus::placeholder {
            color: #299e63;
            transform: translateY(-2px);
        }
        .input-box i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            color: #777;
            transition: all 0.3s ease;
            animation: iconBounce 2s ease-in-out infinite;
        }
        .input-box input:focus + i {
            color: #299e63;
            transform: translateY(-50%) scale(1.1);
        }
        @keyframes iconBounce {
            0%, 100% { transform: translateY(-50%) scale(1); }
            50% { transform: translateY(-50%) scale(1.05); }
        }

        .btn-google {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: #fff;
            color: #333;
            border: 1px solid #ccc;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            padding: 12px;
            margin: 18px 0 12px 0;
            cursor: pointer;
            transition: background 0.2s;
            }

        .btn-google:hover {
            background: #f7f7f7;
            border-color: #299e63;
        }

        .btn {
            width: 100%;
            height: 50px;
            background: linear-gradient(45deg, #299e63, #34a86b);
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(41, 158, 99, 0.3);
            border: none;
            cursor: pointer;
            font-size: 16px;
            color: #fff;
            font-weight: 600;
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            position: relative;
            overflow: hidden;
        }
        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        .btn:hover::before {
            left: 100%;
        }
        .btn:hover {
            background: linear-gradient(45deg, #34a86b, #299e63);
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(41, 158, 99, 0.4);
        }
        .btn:active {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(41, 158, 99, 0.3);
        }

        .forgot-link {
            margin: -10px 0 15px;
            animation: fadeIn 0.8s ease 0.5s both;
        }
        .forgot-link a {
            font-size: 14.5px;
            color: #333;
            transition: all 0.3s ease;
            position: relative;
        }
        .forgot-link a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: #299e63;
            transition: width 0.3s ease;
        }
        .forgot-link a:hover::after {
            width: 100%;
        }
        .forgot-link a:hover {
            color: #299e63;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .toggle-box {
            position: absolute;
            width: 100%;
            height: 100%;
        }
        .toggle-box::before {
            content: '';
            position: absolute;
            left: -250%;
            width: 300%;
            height: 100%;
            background: linear-gradient(45deg, #299e63, #34a86b, #3eb371);
            border-radius: 150px;
            z-index: 2;
            transition: all 1.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            animation: pulseGlow 4s ease-in-out infinite;
        }
        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 20px rgba(41, 158, 99, 0.3); }
            50% { box-shadow: 0 0 40px rgba(41, 158, 99, 0.6); }
        }
        .container.active .toggle-box::before {
            left: 50%;
        }

        .toggle-panel {
            position: absolute;
            width: 50%;
            height: 100%;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            z-index: 2;
            transition: all 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }
        .toggle-panel h1 {
            background: linear-gradient(45deg, #fff, #f0f0f0);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: textShimmer 3s ease-in-out infinite;
        }
        @keyframes textShimmer {
            0%, 100% { filter: brightness(1); }
            50% { filter: brightness(1.3); }
        }
        .toggle-panel.toggle-left {
            left: 0;
            transition-delay: 1.2s;
        }
        .container.active .toggle-panel.toggle-left {
            left: -50%;
            transition-delay: .6s;
        }
        .toggle-panel.toggle-right {
            right: -50%;
            transition-delay: .6s;
        }
        .container.active .toggle-panel.toggle-right {
            right: 0;
            transition-delay: 1.2s;
        }
        .toggle-panel p {
            font-size: 16px;
            margin-top: 5px;
            margin-bottom: 20px;
            text-align: center;
            width: 80%;
            animation: fadeInScale 0.8s ease 1s both;
        }
        @keyframes fadeInScale {
            from { opacity: 0; transform: scale(0.8); }
            to { opacity: 1; transform: scale(1); }
        }
        .toggle-panel .btn {
            width: 160px;
            height: 46px;
            background: transparent;
            border: 2px solid #fff;
            box-shadow: none;
            margin-top: 10px;
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        .toggle-panel .btn:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 10px 25px rgba(255, 255, 255, 0.2);
        }

        .error {
            color: #ff4757;
            font-size: 12px;
            margin-top: 5px;
            animation: errorShake 0.5s ease-in-out;
        }
        @keyframes errorShake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        .switch-login {
            margin-top: 15px;
            animation: fadeIn 0.8s ease 0.7s both;
        }
        .switch-login a {
            color: #299e63;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .switch-login a:hover {
            text-shadow: 0 0 8px rgba(41, 158, 99, 0.5);
        }

        /* ==========================
           RESPONSIVE DESIGN STARTS
        ========================== */
        @media screen and (max-width: 900px) {
            .container {
                width: 98vw;
                min-width: unset;
                height: 98vh;
                min-height: unset;
            }
            .form-box {
                padding: 30px;
            }
        }

        @media screen and (max-width: 650px) {
            .container {
                width: auto;
                height: auto;
                flex-direction: column;
                border-radius: 16px;
                margin: 0;
                min-height: 50vh;
                box-shadow: 0 0 0 0;
                animation: none;
            }
            .form-box, .form-box.login, .form-box.register {
                position: static !important;
                width: 100vw !important;
                min-width: unset;
                min-height: unset;
                height: auto !important;
                border-radius: 0;
                padding: 18vw 7vw 7vw 7vw;
                background: rgba(255,255,255,0.97);
                box-shadow: none;
                z-index: 2;
            }
            .container h1 {
                font-size: 23px;
            }
            .input-box input {
                font-size: 16px;
                padding: 16px 55px 16px 18px;
            }
            .btn, .toggle-panel .btn {
                font-size: 18px;
                min-height: 44px;
            }
            .toggle-box, .toggle-panel {
                display: none !important;
                pointer-events: none;
                height: 0;
                width: 0;
            }
            .form-box.register, .container.active .form-box.register {
                display: none;
            }
            .form-box.login, .container:not(.active) .form-box.login {
                display: block;
            }
            .container.active .form-box.register {
                display: block !important;
            }
            .container.active .form-box.login {
                display: none !important;
            }
            .switch-login {
                margin-top: 8vw;
            }
        }

        @media screen and (max-width: 400px) {
            .form-box {
                padding: 18vw 4vw 8vw 4vw;
            }
            .input-box input {
                font-size: 15px;
            }
            .toggle-panel h1 {
                font-size: 19px;
            }
        }
        /* ==========================
           RESPONSIVE DESIGN ENDS
        ========================== */

    </style>
</head>
<body>
    @if(auth()->check())
        @php
            $level = auth()->user()->level;
            $redirect = match ($level) {
                1 => '/pusat/home',
                2 => '/tpst/home',
                3 => '/petugas',
                4 => '/masyarakat'
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
                <p class="switch-login" style="display:none;">Belum punya akun? <a href="#" id="toRegister">Daftar</a></p>
                <button type="button" class="btn-google" onclick="window.location.href='/auth/google'">
                    <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google" style="width:20px;vertical-align:middle;margin-right:8px;">
                    Login dengan Google
                </button>
            </form>
        </div>
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
                <p class="switch-login" style="display:none;">Sudah punya akun? <a href="#" id="toLogin">Login</a></p>
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

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const container = document.querySelector('.container');
            const registerBtn = document.querySelector('.register-btn');
            const loginBtn = document.querySelector('.login-btn');
            // For mobile, switch-login link
            const toRegister = document.getElementById("toRegister");
            const toLogin = document.getElementById("toLogin");
            const loginFormBox = document.querySelector('.form-box.login');
            const regFormBox = document.querySelector('.form-box.register');
            const isMobile = () => window.innerWidth <= 650;

            // Show mobile links
            function updateSwitchLinks() {
                if (isMobile()) {
                    document.querySelectorAll('.switch-login').forEach(el => el.style.display = 'block');
                } else {
                    document.querySelectorAll('.switch-login').forEach(el => el.style.display = 'none');
                }
            }
            updateSwitchLinks();
            window.addEventListener('resize', updateSwitchLinks);

            // Mobile - click link to switch form
            if (toRegister) toRegister.onclick = function(e){
                e.preventDefault();
                container.classList.add('active');
                loginFormBox.style.display = "none";
                regFormBox.style.display = "block";
            }
            if (toLogin) toLogin.onclick = function(e){
                e.preventDefault();
                container.classList.remove('active');
                regFormBox.style.display = "none";
                loginFormBox.style.display = "block";
            }

            // Desktop: Handle toggle buttons
            if (registerBtn) registerBtn.addEventListener('click', () => {
                container.classList.add('active');
            });
            if (loginBtn) loginBtn.addEventListener('click', () => {
                container.classList.remove('active');
            });

            // Handle URL parameters for direct registration
            const params = new URLSearchParams(window.location.search);
            const mode = params.get("mode");
            if (mode === "register") {
                container.classList.add('active');
                if(isMobile()) {
                    loginFormBox.style.display = "none";
                    regFormBox.style.display = "block";
                }
            } else {
                container.classList.remove('active');
                if(isMobile()) {
                    regFormBox.style.display = "none";
                    loginFormBox.style.display = "block";
                }
            }

            // Add input animation effects
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                });
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
                // Add typing effect
                input.addEventListener('input', function() {
                    if (this.value.length > 0) {
                        this.style.background = 'rgba(255, 255, 255, 0.95)';
                    } else {
                        this.style.background = 'rgba(238, 238, 238, 0.8)';
                    }
                });
            });
            // Add button click effects
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    // Create ripple effect
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.classList.add('ripple');
                    this.appendChild(ripple);
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
            // Add particle animation reset
            setInterval(() => {
                const particles = document.querySelectorAll('.particle');
                particles.forEach(particle => {
                    const randomDelay = Math.random() * 2;
                    particle.style.animationDelay = randomDelay + 's';
                });
            }, 12000);
        });
        // Add CSS for ripple effect
        const style = document.createElement('style');
        style.textContent = `
            .ripple {
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.6);
                transform: scale(0);
                animation: ripple-animation 0.6s linear;
                pointer-events: none;
            }
            @keyframes ripple-animation {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
