<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">    <link rel=\"icon\" type=\"image/x-icon\" href=\"{{ asset('favicon.ico') }}\">    <title>{{ config('app.name', 'Thunder Booking') }} - @yield('title', 'Sports Ground Booking')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
</head>
<body class="bg-gray-50">
    @auth
        @include('layouts.navigation')
    @else
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('welcome') }}" class="text-2xl font-bold text-green-600">
                                <i class="fas fa-bolt"></i> Thunder Booking
                            </a>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="flex items-center gap-2 sm:gap-4">
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-green-600 px-3 py-1.5 sm:px-4 sm:py-2 text-sm sm:text-base font-medium rounded-md hover:bg-gray-50 transition">Login</a>
                            <a href="{{ route('register') }}" class="bg-green-600 text-white px-3 py-1.5 sm:px-5 sm:py-2 rounded-md hover:bg-green-700 text-sm sm:text-base font-medium whitespace-nowrap transition shadow-sm">Register</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    @endauth

    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <main class="py-8">
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    <footer class="bg-gray-800 text-white mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="text-center">
                <p>&copy; {{ date('Y') }} Thunder Booking System. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
