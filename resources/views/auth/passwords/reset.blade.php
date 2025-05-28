<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(90deg, #e2e2e2, #299e63);
        }
        .container {
            width: 900px;
            max-width: 100%;
            background-color: #fff;
            display: flex;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .form-section {
            flex: 1;
            padding: 50px;
        }
        .form-section h2 {
            margin-bottom: 20px;
            font-weight: 700;
            font-size: 28px;
        }
        .form-section input {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }
        .form-section button {
            width: 100%;
            background-color: #299e63;
            color: #fff;
            font-weight: bold;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 10px;
            font-size: 16px;
        }
        .info-section {
            flex: 1;
            background-color: #299e63;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            text-align: center;
        }
        .info-section h2 {
            font-size: 26px;
            margin-bottom: 10px;
        }
        .info-section p {
            font-size: 16px;
        }
        .info-section a {
            margin-top: 15px;
            padding: 10px 20px;
            background-color: transparent;
            border: 2px solid #fff;
            color: #fff;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-section">
            <h2>Reset Password</h2>
            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">
                
                <input type="email" name="email" value="{{ old('email', $email) }}" required autofocus>
                <input type="password" name="password" placeholder="New Password" required>
                <input type="password" name="password_confirmation" placeholder="Confirm Password">

                <button type="submit">Reset Password</button>
            </form>

        </div>
        <div class="info-section">
            <h2>Welcome Back!</h2>
            <p>Sudah ingat password kamu?</p>
            <a href="{{ route('login') }}">Login</a>
        </div>
    </div>
</body>
</html>
