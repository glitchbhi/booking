<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        // Check if this is a Google user setting password for first time
        if ($user->isGoogleUser() && !$user->hasSetPassword()) {
            // For Google users setting their first password, don't require current password
            $validated = $request->validateWithBag('updatePassword', [
                'password' => ['required', Password::defaults(), 'confirmed'],
            ]);
            
            $user->update([
                'password' => Hash::make($validated['password']),
                'password_set_at' => now(),
            ]);
            
            return back()->with('status', 'password-set');
        }
        
        // For regular users or Google users who already set password
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
            'password_set_at' => now(),
        ]);

        return back()->with('status', 'password-updated');
    }
}
