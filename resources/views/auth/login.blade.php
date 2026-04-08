<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'HelpDesk') }} - Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white antialiased">
    <div class="flex min-h-screen">
        <div class="hidden min-h-screen w-2/5 flex-col bg-[#1a3faa] px-10 py-10 text-white lg:flex">
            <h1 class="text-4xl font-bold leading-tight">Ticket Support System</h1>
            <p class="mt-4 max-w-md text-sm leading-6 text-blue-100">
                An email ticketing system helps you convert customer emails to tickets, and compiles and organizes
                them in a single place so no customer complaint goes unnoticed.
            </p>
            <p class="mt-6 text-2xl tracking-[0.5em] text-blue-100">--</p>

            <div class="mt-auto pb-3">
                <svg class="h-auto w-full max-w-md" viewBox="0 0 520 360" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="110" y="260" width="300" height="14" rx="3" fill="#DDE9FF"/>
                    <rect x="175" y="230" width="170" height="35" rx="6" fill="#BFD5FF"/>
                    <circle cx="260" cy="180" r="28" fill="#E9F1FF"/>
                    <rect x="230" y="208" width="60" height="56" rx="10" fill="#D6E5FF"/>
                    <rect x="205" y="248" width="28" height="12" rx="3" fill="#E9F1FF"/>
                    <rect x="287" y="248" width="28" height="12" rx="3" fill="#E9F1FF"/>
                    <rect x="70" y="120" width="82" height="54" rx="12" fill="#EDF4FF"/>
                    <text x="111" y="154" text-anchor="middle" font-size="30" fill="#1a3faa" font-weight="700">?</text>
                    <rect x="360" y="90" width="96" height="60" rx="12" fill="#EDF4FF"/>
                    <text x="408" y="128" text-anchor="middle" font-size="28" fill="#1a3faa" font-weight="700">✉</text>
                    <rect x="390" y="190" width="80" height="46" rx="12" fill="#D6E5FF"/>
                    <rect x="48" y="206" width="88" height="50" rx="12" fill="#D6E5FF"/>
                </svg>
            </div>
        </div>

        <div class="relative flex min-h-screen w-full items-start justify-center overflow-hidden bg-white px-6 py-8 lg:w-3/5">
            <div class="absolute inset-0 overflow-hidden pointer-events-none select-none">
                <span class="absolute left-12 top-8 text-2xl text-gray-200">+</span>
                <span class="absolute right-20 top-16 text-2xl text-gray-200">+</span>
                <span class="absolute bottom-24 left-8 text-2xl text-gray-200">+</span>
                <span class="absolute bottom-12 right-16 text-2xl text-gray-200">+</span>
                <div class="absolute left-6 top-20 h-10 w-16 rounded border-2 border-gray-200 opacity-40"></div>
                <div class="absolute right-8 top-32 h-12 w-20 rounded border-2 border-gray-200 opacity-40"></div>
                <div class="absolute bottom-32 right-10 h-10 w-16 rounded border-2 border-gray-200 opacity-40"></div>
            </div>

            <div class="relative z-10 mt-10 w-full max-w-md rounded-2xl bg-white px-10 py-12 shadow-xl lg:mt-24">
                <h2 class="text-4xl font-bold text-[#1a2f6e]">Hello, Ticket Support</h2>
                <p class="mt-3 text-sm text-gray-500">
                    Login to your account to get started. Or new user
                    <a href="{{ route('register') }}" class="font-bold underline">Sign Up</a>
                </p>

                @if (session('status'))
                    <div class="mt-6 rounded-lg bg-green-50 p-4 text-sm font-medium text-green-700 mx-auto border border-green-200 flex items-center gap-3">
                        <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
                    @csrf

                    <div>
                        <div class="relative">
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                placeholder="User Name"
                                class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 pr-11 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                            <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-300">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM4 20a8 8 0 1116 0" />
                                </svg>
                            </span>
                        </div>
                        @error('email')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="relative">
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                placeholder="Password"
                                class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 pr-11 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                            <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-300">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <rect x="5" y="10" width="14" height="10" rx="2"></rect>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 10V7a4 4 0 118 0v3" />
                                </svg>
                            </span>
                        </div>
                        @error('password')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="flex items-center gap-2 text-sm text-gray-600">
                            <input
                                id="remember_me"
                                type="checkbox"
                                name="remember"
                                class="h-4 w-4 accent-[#00c896]"
                            >
                            Remember Me
                        </label>
                        <a href="{{ route('password.request') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">
                            Forgot Password
                        </a>
                    </div>

                    <button
                        type="submit"
                        class="flex w-full items-center justify-between rounded-lg bg-[#1a3faa] px-5 py-3 text-white transition hover:bg-[#1535a0]"
                    >
                        <span class="font-semibold">Login</span>
                        <span class="text-lg leading-none">→</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
