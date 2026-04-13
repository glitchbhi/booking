<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

        <title>{{ config('app.name', 'Thunder Booking') }}</title>

        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            .logo-circular {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                overflow: hidden;
                display: flex;
                align-items: center;
                justify-content: center;
                border: 2px solid #0066cc;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
                flex-shrink: 0;
            }
            .logo-circular img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            @media (min-width: 640px) {
                .logo-circular {
                    width: 48px;
                    height: 48px;
                    border-width: 3px;
                }
            }
        </style>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            .auth-bg {
                background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1551958219-acbc608c6377?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');
                background-size: cover;
                background-position: center;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-100">
        <div class="min-h-screen flex items-center justify-center py-4 px-4">
            <!-- Centered Container with max-width -->
            <div class="w-full max-w-4xl bg-white rounded-xl shadow-2xl overflow-hidden">
                <div class="flex">
                    <!-- Left Side - Image with Quote -->
                    <div class="hidden lg:flex lg:w-5/12 auth-bg relative min-h-[520px]">
                        <div class="absolute inset-0 flex flex-col justify-center items-center text-white p-8">
                            <div class="max-w-xs text-center">
                                <h1 class="text-3xl font-bold mb-3 leading-tight">Book. Play.<br>Repeat.</h1>
                                <p class="text-sm text-gray-200">Join the largest community of sports enthusiasts.</p>
                            </div>
                            
                            <!-- Testimonial Card -->
                            
                        </div>
                    </div>
                    
                    <!-- Right Side - Form -->
                    <div class="w-full lg:w-7/12 flex flex-col bg-white">
                        <!-- Header -->
                        <div class="flex justify-between items-center px-5 py-3 border-b">
                            <a href="{{ route('welcome') }}" class="flex items-center space-x-2">
                                <div class="logo-circular" style="width: 40px; height: 40px; border: 2px solid #0066cc;">
                                    <img src="{{ asset('images/logo.png') }}" alt="Thunder Booking">
                                </div>
                                <span class="text-sm sm:text-base font-bold text-gray-900 hidden sm:inline">Thunder Booking</span>
                            </a>
                            <a href="{{ route('welcome') }}" class="text-gray-500 hover:text-blue-600 text-xs">
                                <i class="fas fa-arrow-left mr-1"></i> Back
                            </a>
                        </div>
                        
                        <!-- Form Content -->
                        <div class="flex-1 flex items-center justify-center px-6 py-4">
                            <div class="w-full max-w-sm">
                                {{ $slot }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
