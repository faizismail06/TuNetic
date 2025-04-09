<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(90deg, #e2e2e2, #299e63);
        }
        .welcome-container {
            text-align: center;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }
        .welcome-container h1 {
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            width: 150px;
            padding: 10px;
            margin: 10px;
            background: #299e63;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background: #217a4e;
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <h1>Welcome!</h1>
        <p>Choose an option to continue:</p>
        <button class="btn" onclick="openLogin()">Login</button>
        <button class="btn" onclick="openRegister()">Register</button>
    </div>

    <script>
        function openLogin() {
            window.location.href = "{{ route('login') }}?mode=login";
        }
        function openRegister() {
            window.location.href = "{{ route('login') }}?mode=register";
        }
    </script>
</body>
</html>
