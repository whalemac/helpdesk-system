<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'HelpDesk') }} – Sign In</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background-color: #0d1117;
            background-image:
                radial-gradient(circle at 20% 30%, #0a1628 0%, transparent 55%),
                radial-gradient(circle at 80% 70%, #0d2044 0%, transparent 55%),
                radial-gradient(rgba(255, 255, 255, 0.045) 1px, transparent 1px);
            background-size: auto, auto, 28px 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Decorative background headset icon */
        .bg-icon {
            position: fixed;
            right: -60px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 580px;
            line-height: 1;
            opacity: 0.04;
            color: #ffffff;
            pointer-events: none;
            user-select: none;
            z-index: 0;
        }

        /* Glass card */
        .glass-card {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 380px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 20px;
            padding: 40px 36px;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.45), 0 0 0 1px rgba(255, 255, 255, 0.04) inset;
        }

        /* Brand */
        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 28px;
        }

        .brand-icon {
            width: 36px;
            height: 36px;
            background: #2a63c7;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .brand-icon svg {
            width: 20px;
            height: 20px;
            color: #fff;
        }

        .brand-text-group {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .brand-name {
            color: #ffffff;
            font-weight: 600;
            font-size: 17px;
        }

        .brand-sub {
            color: rgba(255, 255, 255, 0.4);
            font-size: 11px;
            margin-top: 1px;
        }

        /* Heading */
        .form-title {
            color: #ffffff;
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .form-subtitle {
            color: rgba(255, 255, 255, 0.45);
            font-size: 13px;
            margin-bottom: 28px;
        }

        /* Status message */
        .status-msg {
            background: rgba(34, 197, 94, 0.12);
            border: 1px solid rgba(34, 197, 94, 0.3);
            border-radius: 10px;
            color: #4ade80;
            font-size: 13px;
            padding: 10px 14px;
            margin-bottom: 20px;
        }

        /* Form fields */
        .field {
            margin-bottom: 16px;
        }

        .field-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 6px;
        }

        label {
            color: rgba(255, 255, 255, 0.55);
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .forgot-link {
            color: #4d9de0;
            font-size: 12px;
            text-decoration: none;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: #74b5ea;
        }

        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            height: 42px;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 10px;
            color: rgba(255, 255, 255, 0.85);
            font-size: 14px;
            font-family: inherit;
            padding: 0 14px;
            outline: none;
            transition: border-color 0.2s, background 0.2s;
            -webkit-appearance: none;
        }

        input[type="email"]::placeholder,
        input[type="password"]::placeholder,
        input[type="text"]::placeholder {
            color: rgba(255, 255, 255, 0.25);
        }

        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="text"]:focus {
            border-color: #2a63c7;
            background: rgba(42, 99, 199, 0.1);
        }

        /* Chrome autofill dark override */
        input:-webkit-autofill,
        input:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 1000px #0d1b38 inset !important;
            -webkit-text-fill-color: rgba(255, 255, 255, 0.85) !important;
            caret-color: rgba(255, 255, 255, 0.85);
        }

        /* Validation errors */
        .error-msg {
            color: #f87171;
            font-size: 12px;
            margin-top: 4px;
        }

        /* Remember + forgot row */
        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 7px;
            color: rgba(255, 255, 255, 0.5);
            font-size: 13px;
            cursor: pointer;
        }

        input[type="checkbox"] {
            width: 15px;
            height: 15px;
            accent-color: #2a63c7;
            cursor: pointer;
        }

        /* Submit button */
        .btn-primary {
            width: 100%;
            height: 42px;
            background: #2a63c7;
            border: none;
            border-radius: 10px;
            color: #ffffff;
            font-size: 14px;
            font-weight: 500;
            font-family: inherit;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
        }

        .btn-primary:hover {
            background: #3572d4;
        }

        .btn-primary:active {
            transform: scale(0.99);
        }

        /* Divider */
        .divider {
            border: none;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin: 24px 0 18px;
        }

        /* Footer text */
        .card-footer {
            text-align: center;
            color: rgba(255, 255, 255, 0.4);
            font-size: 13px;
        }

        .card-footer a {
            color: #4d9de0;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .card-footer a:hover {
            color: #74b5ea;
        }
    </style>
</head>

<body>

    <!-- Decorative background headset symbol -->
    <div class="bg-icon" aria-hidden="true">&#x1F3A7;</div>

    <div class="glass-card">

        <!-- Brand -->
        <div class="brand">
            <div class="brand-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M3 18v-6a9 9 0 0 1 18 0v6" />
                    <path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3z" />
                    <path d="M3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z" />
                </svg>
            </div>
            <div class="brand-text-group">
                <span class="brand-name">HelpDesk</span>
                <span class="brand-sub">Support portal</span>
            </div>
        </div>

        <!-- Heading -->
        <h1 class="form-title">Welcome back</h1>
        <p class="form-subtitle">Sign in to your account to continue</p>

        <!-- Session status -->
        @if (session('status'))
            <div class="status-msg">{{ session('status') }}</div>
        @endif

        <!-- Login form -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="field">
                <label for="email">Email address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    autocomplete="username" placeholder="">
                @error('email')
                    <p class="error-msg">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="field">
                <div class="field-row">
                    <label for="password">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                    @endif
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    placeholder="••••••••">
                @error('password')
                    <p class="error-msg">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember me -->
            <div class="remember-row">
                <label class="remember-label" for="remember_me">
                    <input id="remember_me" type="checkbox" name="remember">
                    Remember me
                </label>
            </div>

            <button type="submit" class="btn-primary">Sign in</button>
        </form>

        <hr class="divider">

        <p class="card-footer">
            Don't have an account?
            <a href="{{ route('register') }}">Register</a>
        </p>

    </div>

</body>

</html>