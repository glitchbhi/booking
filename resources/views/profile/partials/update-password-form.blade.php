<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            @if(Auth::user()->isGoogleUser() && !Auth::user()->hasSetPassword())
                {{ __('Set Your Password') }}
            @else
                {{ __('Update Password') }}
            @endif
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            @if(Auth::user()->isGoogleUser() && !Auth::user()->hasSetPassword())
                {{ __('You signed up with Google. Set a password to enable login with both Google and email/password.') }}
            @else
                {{ __('Ensure your account is using a long, random password to stay secure.') }}
            @endif
        </p>
        
        @if(Auth::user()->isGoogleUser() && !Auth::user()->hasSetPassword())
            <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-md">
                <p class="text-sm text-blue-800">
                    <i class="fas fa-info-circle"></i> 
                    <strong>Why set a password?</strong> Once you set a password, you can login using either your Google account OR your email and password on the regular login form.
                </p>
            </div>
        @endif
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        @if(!Auth::user()->isGoogleUser() || Auth::user()->hasSetPassword())
            <div>
                <x-input-label for="update_password_current_password" :value="__('Current Password')" />
                <div class="relative mt-1">
                    <x-text-input id="update_password_current_password" name="current_password" type="password" class="block w-full pr-10" autocomplete="current-password" />
                    <button type="button" onclick="togglePassword('update_password_current_password', 'eye-icon-current-password')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <i id="eye-icon-current-password" class="fas fa-eye text-gray-400 hover:text-gray-600 text-sm"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>
        @endif

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <div class="relative mt-1">
                <x-text-input id="update_password_password" name="password" type="password" class="block w-full pr-10" autocomplete="new-password" />
                <button type="button" onclick="togglePassword('update_password_password', 'eye-icon-new-password')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <i id="eye-icon-new-password" class="fas fa-eye text-gray-400 hover:text-gray-600 text-sm"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <div class="relative mt-1">
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="block w-full pr-10" autocomplete="new-password" />
                <button type="button" onclick="togglePassword('update_password_password_confirmation', 'eye-icon-confirm-password')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <i id="eye-icon-confirm-password" class="fas fa-eye text-gray-400 hover:text-gray-600 text-sm"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @elseif (session('status') === 'password-set')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm text-green-600"
                >{{ __('Password set! You can now login with email and password.') }}</p>
            @endif
        </div>
    </form>

    <script>
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</section>
