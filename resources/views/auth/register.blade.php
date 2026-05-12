<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'HelpDesk') }} – Create Account</title>
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
            padding: 32px 16px;
            position: relative;
            overflow-x: hidden;
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
            max-width: 440px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 24px;
            padding: 40px 36px;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.45), 0 0 0 1px rgba(255, 255, 255, 0.04) inset;
        }

        /* Brand */
        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 24px;
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

        /* Progress Indicator */
        .progress-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 32px;
            position: relative;
        }

        .progress-container::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-50%);
            z-index: 0;
        }

        .progress-bar {
            position: absolute;
            top: 50%;
            left: 0;
            height: 2px;
            background: #2a63c7;
            transform: translateY(-50%);
            z-index: 0;
            transition: width 0.4s ease;
            width: 0%;
        }

        .step-node {
            width: 32px;
            height: 32px;
            background: #1a2235;
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.4);
            font-size: 12px;
            font-weight: 600;
            z-index: 1;
            transition: all 0.3s ease;
            position: relative;
        }

        .step-node.active {
            border-color: #2a63c7;
            color: #ffffff;
            background: #2a63c7;
            box-shadow: 0 0 15px rgba(42, 99, 199, 0.4);
        }

        .step-node.completed {
            background: #2a63c7;
            border-color: #2a63c7;
            color: #ffffff;
        }

        /* Heading */
        .form-title {
            color: #ffffff;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .form-subtitle {
            color: rgba(255, 255, 255, 0.45);
            font-size: 14px;
            margin-bottom: 28px;
        }

        /* Steps */
        .form-step {
            display: none;
            animation: fadeIn 0.4s ease forwards;
        }

        .form-step.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Form fields */
        .field {
            margin-bottom: 20px;
        }

        label {
            display: block;
            color: rgba(255, 255, 255, 0.55);
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            height: 46px;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 12px;
            color: rgba(255, 255, 255, 0.9);
            font-size: 15px;
            font-family: inherit;
            padding: 0 16px;
            outline: none;
            transition: all 0.2s ease;
            -webkit-appearance: none;
        }

        input[type="email"]::placeholder,
        input[type="password"]::placeholder,
        input[type="text"]::placeholder {
            color: rgba(255, 255, 255, 0.2);
        }

        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="text"]:focus {
            border-color: #2a63c7;
            background: rgba(42, 99, 199, 0.1);
            box-shadow: 0 0 0 4px rgba(42, 99, 199, 0.15);
        }

        /* Select */
        select {
            width: 100%;
            height: 46px;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 12px;
            color: rgba(255, 255, 255, 0.8);
            font-size: 15px;
            font-family: inherit;
            padding: 0 16px;
            outline: none;
            appearance: none;
            -webkit-appearance: none;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='rgba(255,255,255,0.35)' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            transition: all 0.2s ease;
        }

        select:focus {
            border-color: #2a63c7;
            background-color: rgba(42, 99, 199, 0.1);
            box-shadow: 0 0 0 4px rgba(42, 99, 199, 0.15);
        }

        select option {
            background: #0d1b38;
            color: rgba(255, 255, 255, 0.9);
        }

        /* Validation errors */
        .error-msg {
            color: #f87171;
            font-size: 12px;
            margin-top: 6px;
            font-weight: 500;
        }

        /* Buttons */
        .btn-group {
            display: flex;
            gap: 12px;
            margin-top: 12px;
        }

        .btn {
            height: 46px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 24px;
        }

        .btn-primary {
            flex: 1;
            background: #2a63c7;
            border: none;
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(42, 99, 199, 0.3);
        }

        .btn-primary:hover {
            background: #3572d4;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(42, 99, 199, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.8);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
        }

        .btn:active {
            transform: scale(0.98);
        }

        /* Divider */
        .divider {
            border: none;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin: 28px 0 20px;
        }

        /* Footer text */
        .card-footer {
            text-align: center;
            color: rgba(255, 255, 255, 0.4);
            font-size: 14px;
        }

        .card-footer a {
            color: #4d9de0;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .card-footer a:hover {
            color: #74b5ea;
        }
    </style>
</head>

<body>

    <div class="bg-icon" aria-hidden="true">&#x1F3A7;</div>

    <div class="glass-card">

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

        <div class="progress-container">
            <div class="progress-bar" id="progressBar"></div>
            <div class="step-node active" data-step="1">1</div>
            <div class="step-node" data-step="2">2</div>
            <div class="step-node" data-step="3">3</div>
        </div>

        <h1 class="form-title" id="stepTitle">Personal info</h1>
        <p class="form-subtitle" id="stepSubtitle">Tell us who you are</p>

        <form method="POST" action="{{ route('register') }}" id="registerForm">
            @csrf

            <!-- STEP 1: PERSONAL INFO -->
            <div class="form-step active" id="step1">
                <div class="field">
                    <label for="first_name">First name</label>
                    <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required autofocus
                        placeholder="Enter First Name">
                    @error('first_name')
                        <p class="error-msg">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="middle_name">Middle name (Optional)</label>
                    <input id="middle_name" type="text" name="middle_name" value="{{ old('middle_name') }}"
                        placeholder="Enter Middle Name">
                    @error('middle_name')
                        <p class="error-msg">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="last_name">Last name</label>
                    <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required
                        placeholder="Enter Last Name">
                    @error('last_name')
                        <p class="error-msg">{{ $message }}</p>
                    @enderror
                </div>

                <div class="btn-group">
                    <button type="button" class="btn btn-primary" onclick="nextStep(2)">Continue</button>
                </div>
            </div>

            <!-- STEP 2: ACCOUNT INFO -->
            <div class="form-step" id="step2">
                <div class="field">
                    <label for="email">Email address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        placeholder="example@email.com">
                    @error('email')
                        <p class="error-msg">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="role">Role</label>
                    <select id="role" name="role" required>
                        <option value="" disabled {{ old('role') ? '' : 'selected' }}>Select your role</option>
                        <option value="agent" {{ old('role') === 'agent' ? 'selected' : '' }}>Support Agent</option>
                        <option value="supervisor" {{ old('role') === 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                        <option value="requester" {{ old('role') === 'requester' ? 'selected' : '' }}>Requester</option>
                    </select>
                    @error('role')
                        <p class="error-msg">{{ $message }}</p>
                    @enderror
                </div>

                <div class="btn-group">
                    <button type="button" class="btn btn-secondary" onclick="nextStep(1)">Back</button>
                    <button type="button" class="btn btn-primary" onclick="nextStep(3)">Continue</button>
                </div>
            </div>

            <!-- STEP 3: SECURITY -->
            <div class="form-step" id="step3">
                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required
                        placeholder="At least 8 characters">
                    @error('password')
                        <p class="error-msg">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="password_confirmation">Confirm password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        placeholder="Repeat your password">
                    @error('password_confirmation')
                        <p class="error-msg">{{ $message }}</p>
                    @enderror
                </div>

                <div class="btn-group">
                    <button type="button" class="btn btn-secondary" onclick="nextStep(2)">Back</button>
                    <button type="submit" class="btn btn-primary">Create account</button>
                </div>
            </div>
        </form>

        <hr class="divider">

        <p class="card-footer">
            Already have an account?
            <a href="{{ route('login') }}">Sign in</a>
        </p>

    </div>

    <script>
        const titles = {
            1: "Personal info",
            2: "Account setup",
            3: "Security"
        };
        const subtitles = {
            1: "Tell us who you are",
            2: "Your email and platform role",
            3: "Choose a strong password"
        };

        function nextStep(step) {
            // Hide all steps
            document.querySelectorAll('.form-step').forEach(el => el.classList.remove('active'));
            
            // Show target step
            document.getElementById('step' + step).classList.add('active');

            // Update title and subtitle
            document.getElementById('stepTitle').innerText = titles[step];
            document.getElementById('stepSubtitle').innerText = subtitles[step];

            // Update progress nodes
            document.querySelectorAll('.step-node').forEach(node => {
                const nodeStep = parseInt(node.getAttribute('data-step'));
                node.classList.remove('active', 'completed');
                if (nodeStep === step) {
                    node.classList.add('active');
                } else if (nodeStep < step) {
                    node.classList.add('completed');
                    node.innerHTML = '&#10003;'; // Checkmark
                } else {
                    node.innerHTML = nodeStep;
                }
            });

            // Update progress bar
            const progress = ((step - 1) / 2) * 100;
            document.getElementById('progressBar').style.width = progress + '%';
        }

        // Auto-navigate to step with errors
        window.addEventListener('load', () => {
            @if($errors->has('password') || $errors->has('password_confirmation'))
                nextStep(3);
            @elseif($errors->has('email') || $errors->has('role'))
                nextStep(2);
            @elseif($errors->has('first_name') || $errors->has('last_name'))
                nextStep(1);
            @endif
        });
    </script>
</body>

</html>