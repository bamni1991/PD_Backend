<!DOCTYPE html>
<html lang="en">

<head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Multipurpose, super flexible, powerful, clean modern responsive bootstrap 5 admin template"
        name="description">
    <meta
        content="admin template, axelit admin template, dashboard template, flat admin template, responsive admin template, web app"
        name="keywords">
    <meta content="la-themes" name="author">

    <link href="{{ asset('assets/images/logo/favicon.png') }}" rel="icon" type="image/x-icon">
    <link href="{{ asset('assets/images/logo/favicon.png') }}" rel="shortcut icon" type="image/x-icon">

    <title>Login - My Drive App</title>

    <!-- Font Awesome -->
    <link href="{{ asset('assets/vendor/fontawesome/css/all.css') }}" rel="stylesheet">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap css-->
    <link href="{{ asset('assets/vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet" type="text/css">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --glass-bg: rgba(255, 255, 255, 0.95);
            --glass-border: rgba(255, 255, 255, 0.4);
            --input-bg: #f8fafc;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: #ffffff;
            background-image:
                radial-gradient(at 0% 0%, hsla(253, 16%, 7%, 1) 0, transparent 50%),
                radial-gradient(at 50% 0%, hsla(225, 39%, 30%, 1) 0, transparent 50%),
                radial-gradient(at 100% 0%, hsla(339, 49%, 30%, 1) 0, transparent 50%);
            background-color: #0f172a;
            /* Fallback for dark theme feel */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
            position: relative;
        }

        /* Ambient background shapes for premium feel */
        body::before,
        body::after {
            content: '';
            position: absolute;
            filter: blur(80px);
            z-index: -1;
            opacity: 0.6;
        }

        body::before {
            background: #764ba2;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            top: -100px;
            left: -100px;
        }

        body::after {
            background: #667eea;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            bottom: -50px;
            right: -50px;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            animation: fadeIn 0.8s ease-out;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-container img {
            height: 48px;
            width: auto;
            margin-bottom: 1rem;
        }

        .welcome-text {
            color: #1e293b;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-align: center;
        }

        .subtitle-text {
            color: #64748b;
            font-size: 0.95rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .form-label {
            font-weight: 500;
            color: #334155;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .form-control {
            background-color: var(--input-bg);
            border: 2px solid transparent;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            background-color: #fff;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .btn-primary-custom {
            background: var(--primary-gradient);
            border: none;
            border-radius: 12px;
            padding: 0.75rem;
            font-weight: 600;
            font-size: 1rem;
            letter-spacing: 0.5px;
            width: 100%;
            transition: transform 0.2s, box-shadow 0.2s;
            color: white;
            margin-top: 1rem;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-primary-custom:active {
            transform: translateY(0);
        }

        .invalid-feedback-custom {
            color: #ef4444;
            font-size: 0.85rem;
            margin-top: 0.25rem;
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

    <div class="login-card">
        <div class="logo-container">
            <!-- Logo from the original file -->
            <a class="logo d-inline-block" href="{{ route('home') }}">
                <img alt="#" src="{{ asset('assets') }}/images/logo/1.png">
            </a>
            <h1 class="welcome-text">Welcome Back 1</h1>
            <p class="subtitle-text">Sign in to access your dashboard</p>
        </div>

        <form method="POST" action="{{ route('authenticate') }}">
            @csrf

            <div class="mb-4">
                <label class="form-label" for="email">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-0 ps-0">
                        <!-- Optional icon -->
                    </span>
                </div>
                <input class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                    value="{{ old('email') }}" placeholder="name@company.com" type="email" required autofocus>
                @error('email')
                    <span class="invalid-feedback-custom">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="form-label mb-0" for="password">Password</label>
                </div>
                <input class="form-control @error('password') is-invalid @enderror" id="password" name="password"
                    placeholder="••••••••" type="password" required>
                @error('password')
                    <span class="invalid-feedback-custom">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary-custom">
                Sign In
            </button>
        </form>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/jquery-3.6.3.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>

</body>

</html>
