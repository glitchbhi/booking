<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\AccountCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirect(Request $request)
    {
        // Set redirect URI dynamically based on current request
        $redirectUri = url('/auth/google/callback');
        
        return Socialite::driver('google')
            ->redirectUrl($redirectUri)
            ->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function callback(Request $request)
    {
        try {
            // Set redirect URI dynamically for callback as well
            $redirectUri = url('/auth/google/callback');
            
            $googleUser = Socialite::driver('google')
                ->redirectUrl($redirectUri)
                ->user();

            // Check if user exists with this email
            $user = User::where('email', $googleUser->getEmail())->first();
            $isNewUser = false;

            if ($user) {
                // Update Google ID if not set
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                    ]);
                }
            } else {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => Hash::make(Str::random(24)),
                    'email_verified_at' => now(),
                    'role' => 'user',
                    'wallet_balance' => 0,
                ]);
                $isNewUser = true;

                // Send welcome email notification for new users
                $user->notify(new AccountCreated('google'));
            }

            // Login the user
            Auth::login($user, true);

            return redirect()->route('welcome')->with('success', 'Welcome, ' . $user->name . '!');

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Google OAuth Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Google authentication failed: ' . $e->getMessage());
        }
    }
}
