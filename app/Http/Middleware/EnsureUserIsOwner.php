<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsOwner
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Please login to access owner dashboard.');
        }
        
        if (!$user->isOwner()) {
            // Check if user has pending owner request
            if ($user->owner_status === 'pending') {
                return redirect()->route('home')
                    ->with('info', 'Your owner application is pending approval. You will be notified once approved.');
            }
            
            // User is not an owner and hasn't applied
            return redirect()->route('home')
                ->with('error', 'You need to apply and be approved as an owner to access this area.');
        }

        return $next($request);
    }
}
