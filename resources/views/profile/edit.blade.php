@extends('layouts.app')

@section('title', 'Profile Settings')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Profile Settings</h1>

    @if(Auth::user()->isGoogleUser() && !Auth::user()->hasSetPassword())
        <div class="mb-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 p-4 rounded-r-lg shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-lightbulb text-blue-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">
                        Enable Email Login
                    </h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>You signed up with Google. Set a password below to also login using your email <strong>{{ Auth::user()->email }}</strong> and password on the regular login form.</p>
                    </div>
                    <div class="mt-3">
                        <a href="#password-section" class="text-sm font-medium text-blue-800 hover:text-blue-600">
                            Set Password Now <i class="fas fa-arrow-down ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg" id="password-section">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection
