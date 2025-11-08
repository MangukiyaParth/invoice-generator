<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            .gradient-text { background: linear-gradient(135deg, #3498db 0%, #2c3e50 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
            .animate-fadeInUp { animation: fadeInUp 1s ease-out; }
            @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        </style>
    </head>
    <body class="bg-white text-gray-900">
        {{-- Header --}}
        <header class="fixed w-full top-0 bg-white/90 backdrop-blur-sm border-b border-gray-200 z-50">
            <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold gradient-text">{{ config('app.name', 'Account Manager') }}</h1>
                @if (Route::has('login'))
                    <nav class="flex items-center space-x-6">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-gray-700 hover:text-blue-500 font-medium transition">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-500 font-medium transition">Login</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold shadow-lg transition transform hover:scale-105">Register</a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </div>
        </header>

        {{-- Hero Section --}}
        <section class="pt-32 pb-32 px-6 bg-gradient-to-br from-blue-50 via-white to-gray-50 min-h-screen flex items-center">
            <div class="max-w-6xl mx-auto text-center animate-fadeInUp">
                <h2 class="text-5xl md:text-7xl font-bold leading-tight gradient-text mb-8">
                    Secure Account<br>Management
                </h2>
                <p class="text-xl md:text-2xl text-gray-600 mb-12 max-w-3xl mx-auto">
                    Safely store and manage your account credentials. Update emails, change passwords, and keep your personal information secure in one centralized platform.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('register') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-8 py-4 rounded-lg font-semibold shadow-xl transition transform hover:scale-105">Start Managing Today</a>
                </div>
            </div>
        </section>

        {{-- Footer --}}
        <footer class="py-12 px-6" style="background-color: #2c3e50; color: #ecf0f1;">
            <div class="max-w-6xl mx-auto text-center">
                <h4 class="text-2xl font-bold gradient-text mb-4">{{ config('app.name', 'Account Manager') }}</h4>
                <p class="mb-6">Keep your account details safe and organized.</p>
                <div class="flex justify-center space-x-6 text-sm">
                    <a href="#" class="hover:text-white transition">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition">Terms of Service</a>
                    <a href="#" class="hover:text-white transition">Contact</a>
                </div>
                <p class="mt-8 text-sm text-gray-500">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            </div>
        </footer>
    </body>
</html>