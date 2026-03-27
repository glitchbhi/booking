<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SetPasswordController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the set password form.
     */
    public function create()
    {
        $user = Auth::user();

        // If user already has a password set, redirect to profile
        if ($user->password !== null) {
            return redirect()->route('profile.edit')
                ->with('info', 'You already have a password set.');
        }

        return view('auth.set-password');
    }

    /**
     * Store the password.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // If user already has a password set, reject
        if ($user->password !== null) {
            return redirect()->route('profile.edit')
                ->with('error', 'You already have a password set.');
        }

        // Validate the password
        $validated = $request->validate([
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ], [
            'password.required' => 'Password is required',
            'password.confirmed' => 'Password confirmation does not match',
            'password.min' => 'Password must be at least 8 characters',
            'password.mixed_case' => 'Password must contain both uppercase and lowercase letters',
            'password.numbers' => 'Password must contain at least one number',
            'password.symbols' => 'Password must contain at least one symbol (!@#$%^&*)',
        ]);

        // Update user password
        $user->update([
            'password' => Hash::make($validated['password']),
            'password_set_at' => now(),
        ]);

        return redirect()->route('profile.edit')
            ->with('success', 'Password set successfully! You can now log in with your email and password.');
    }
}
