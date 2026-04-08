<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'HelpDesk') }} - Register</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white antialiased">
    <div class="flex min-h-screen">
        <div class="hidden min-h-screen w-2/5 flex-col bg-[#1a3faa] px-10 py-10 text-white lg:flex">
            <h1 class="text-4xl font-bold leading-tight">Join Our Support Team</h1>
            <p class="mt-4 max-w-md text-sm leading-6 text-blue-100">
                Create your account to start managing and resolving support tickets efficiently.
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
                <h2 class="text-4xl font-bold text-[#1a2f6e]">Create Account</h2>
                <p class="mt-3 text-sm text-gray-500">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-bold underline">Sign In</a>
                </p>

                <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-5">
                    @csrf

                    <div>
                        <div class="relative">
                            <input
                                id="name"
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                autofocus
                                placeholder="Full Name"
                                class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 pr-11 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                            <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-300">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM4 20a8 8 0 1116 0" />
                                </svg>
                            </span>
                        </div>
                        @error('name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="relative">
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                placeholder="Email Address"
                                class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 pr-11 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                            <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-300">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16v10H4z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 7l8 6 8-6" />
                                </svg>
                            </span>
                        </div>
                        @error('email')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-data="{show:false}">
                        <div class="relative">
                            <input
                                id="password"
                                :type="show ? 'text' : 'password'"
                                name="password"
                                required
                                placeholder="Password"
                                class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 pr-11 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                            <button type="button" @click="show=!show" class="absolute inset-y-0 right-3 flex items-center text-gray-300">
                                <svg x-show="!show" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6z" />
                                    <circle cx="12" cy="12" r="2.5"></circle>
                                </svg>
                                <svg x-show="show" x-cloak class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.7 10.7A2 2 0 0012 14a2 2 0 001.3-.5" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.2 6.2C3.8 8.1 2.5 10.4 2 12c.9 2.2 4 6 10 6 2.1 0 3.9-.5 5.3-1.2" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.1 4.6A12 12 0 0112 4c6 0 9.1 3.8 10 8-.3.8-.8 1.8-1.5 2.8" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-data="{show:false}">
                        <div class="relative">
                            <input
                                id="password_confirmation"
                                :type="show ? 'text' : 'password'"
                                name="password_confirmation"
                                required
                                placeholder="Confirm Password"
                                class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 pr-11 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                            <button type="button" @click="show=!show" class="absolute inset-y-0 right-3 flex items-center text-gray-300">
                                <svg x-show="!show" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6z" />
                                    <circle cx="12" cy="12" r="2.5"></circle>
                                </svg>
                                <svg x-show="show" x-cloak class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.7 10.7A2 2 0 0012 14a2 2 0 001.3-.5" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.2 6.2C3.8 8.1 2.5 10.4 2 12c.9 2.2 4 6 10 6 2.1 0 3.9-.5 5.3-1.2" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.1 4.6A12 12 0 0112 4c6 0 9.1 3.8 10 8-.3.8-.8 1.8-1.5 2.8" />
                                </svg>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="relative">
                            <select
                                id="role"
                                name="role"
                                required
                                class="w-full appearance-none rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 pr-11 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="" disabled selected>Select Your Role</option>
                                <option value="admin">Admin</option>
                                <option value="agent">Support Agent</option>
                                <option value="supervisor">Supervisor</option>
                                <option value="requester">Requester</option>
                            </select>
                            <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </span>
                        </div>
                        @error('role')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="flex w-full items-center justify-between rounded-lg bg-[#1a3faa] px-5 py-3 text-white transition hover:bg-[#1535a0]"
                    >
                        <span class="font-semibold">Create Account</span>
                        <span class="text-lg leading-none">→</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
