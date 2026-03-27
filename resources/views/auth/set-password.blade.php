@extends('layouts.app')

@section('title', 'Set Your Password')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <!-- Card -->
        <div class="bg-white rounded-xl shadow-2xl p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-lock text-white text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Set Your Password</h1>
                <p class="text-gray-600 mt-2">Secure your account by setting a password</p>
            </div>

            <!-- Info Alert -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                    <div>
                        <p class="text-sm text-blue-800">
                            <strong>Why set a password?</strong> Having a password allows you to log in directly without relying on Google authentication. Your Google account will still be linked.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('password.set.store') }}" class="space-y-6">
                @csrf

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-key mr-2 text-gray-500"></i> Password
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                            class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('password') border-red-500 @enderror"
                            placeholder="Enter your password"
                        >
                        <button type="button" onclick="togglePassword('password', 'eye-icon-password')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i id="eye-icon-password" class="fas fa-eye text-gray-400 hover:text-gray-600 text-sm"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password Field -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-check-circle mr-2 text-gray-500"></i> Confirm Password
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            required
                            class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('password') border-red-500 @enderror"
                            placeholder="Confirm your password"
                        >
                        <button type="button" onclick="togglePassword('password_confirmation', 'eye-icon-password-confirmation')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i id="eye-icon-password-confirmation" class="fas fa-eye text-gray-400 hover:text-gray-600 text-sm"></i>
                        </button>
                    </div>
                </div>

                <!-- Password Requirements -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <p class="text-sm font-medium text-gray-700 mb-3">Password Requirements:</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center">
                            <span class="w-5 h-5 rounded-full bg-gray-300 flex items-center justify-center mr-3" id="length-check">
                                <i class="fas fa-times text-white text-xs"></i>
                            </span>
                            <span>At least 8 characters</span>
                        </li>
                        <li class="flex items-center">
                            <span class="w-5 h-5 rounded-full bg-gray-300 flex items-center justify-center mr-3" id="uppercase-check">
                                <i class="fas fa-times text-white text-xs"></i>
                            </span>
                            <span>One uppercase letter (A-Z)</span>
                        </li>
                        <li class="flex items-center">
                            <span class="w-5 h-5 rounded-full bg-gray-300 flex items-center justify-center mr-3" id="lowercase-check">
                                <i class="fas fa-times text-white text-xs"></i>
                            </span>
                            <span>One lowercase letter (a-z)</span>
                        </li>
                        <li class="flex items-center">
                            <span class="w-5 h-5 rounded-full bg-gray-300 flex items-center justify-center mr-3" id="number-check">
                                <i class="fas fa-times text-white text-xs"></i>
                            </span>
                            <span>One number (0-9)</span>
                        </li>
                        <li class="flex items-center">
                            <span class="w-5 h-5 rounded-full bg-gray-300 flex items-center justify-center mr-3" id="symbol-check">
                                <i class="fas fa-times text-white text-xs"></i>
                            </span>
                            <span>One symbol (!@#$%^&*)</span>
                        </li>
                    </ul>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="w-full bg-gradient-to-r from-green-500 to-blue-600 hover:from-green-600 hover:to-blue-700 text-white font-bold py-3 rounded-lg transition transform hover:scale-105 flex items-center justify-center"
                >
                    <i class="fas fa-check-circle mr-2"></i> Set Password
                </button>

                <!-- Skip Link -->
                <p class="text-center text-gray-600">
                    <a href="{{ route('welcome') }}" class="text-blue-600 hover:text-blue-700 underline">
                        Skip for now (use Google login)
                    </a>
                </p>
            </form>
        </div>

        <!-- Footer -->
        <p class="text-center text-gray-600 text-sm mt-6">
            <i class="fas fa-shield-alt text-green-600 mr-1"></i> Your password is encrypted and secure
        </p>
    </div>
</div>

<!-- Password Validation JavaScript -->
<script>
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');

    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Check requirements
    const checks = {
        length: { regex: /.{8,}/, id: 'length-check' },
        uppercase: { regex: /[A-Z]/, id: 'uppercase-check' },
        lowercase: { regex: /[a-z]/, id: 'lowercase-check' },
        number: { regex: /[0-9]/, id: 'number-check' },
        symbol: { regex: /[!@#$%^&*]/, id: 'symbol-check' },
    };

    passwordInput.addEventListener('input', function() {
        Object.entries(checks).forEach(([key, check]) => {
            const element = document.getElementById(check.id);
            if (check.regex.test(this.value)) {
                element.classList.remove('bg-gray-300');
                element.classList.add('bg-green-500');
                element.innerHTML = '<i class="fas fa-check text-white text-xs"></i>';
            } else {
                element.classList.remove('bg-green-500');
                element.classList.add('bg-gray-300');
                element.innerHTML = '<i class="fas fa-times text-white text-xs"></i>';
            }
        });
    });

    // Show password match indicator
    confirmInput.addEventListener('input', function() {
        if (this.value && this.value === passwordInput.value) {
            this.classList.remove('border-gray-300', 'border-red-500');
            this.classList.add('border-green-500', 'bg-green-50');
        } else if (this.value) {
            this.classList.remove('border-gray-300', 'border-green-500', 'bg-green-50');
            this.classList.add('border-red-500');
        } else {
            this.classList.remove('border-red-500', 'border-green-500', 'bg-green-50');
            this.classList.add('border-gray-300');
        }
    });
</script>
@endsection
