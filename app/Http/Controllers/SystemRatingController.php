<?php

namespace App\Http\Controllers;

use App\Models\SystemRating;
use Illuminate\Http\Request;

class SystemRatingController extends Controller
{
    public function index()
    {
        $averageRating = SystemRating::getAverageRating();
        $totalRatings = SystemRating::getTotalCount();
        $ratings = SystemRating::with('user')->latest()->paginate(10);
        
        $userRating = null;
        if (auth()->check()) {
            $userRating = auth()->user()->systemRating;
        }

        return view('system-ratings.index', compact('averageRating', 'totalRatings', 'ratings', 'userRating'));
    }

    public function store(Request $request)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to submit a rating');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        try {
            // Check if user has already rated the system
            $existingRating = SystemRating::where('user_id', auth()->id())->first();

            if ($existingRating) {
                return back()->with('error', 'You have already rated the system. Delete your previous rating to submit a new one.');
            }

            SystemRating::create([
                'user_id' => auth()->id(),
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
            ]);

            return redirect()
                ->route('system-ratings.index')
                ->with('success', 'Thank you for rating our system!');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to submit rating: ' . $e->getMessage());
        }
    }

    public function destroy(SystemRating $systemRating)
    {
        // Only allow users to delete their own ratings
        if ($systemRating->user_id !== auth()->id()) {
            return back()->with('error', 'You can only delete your own ratings.');
        }

        $systemRating->delete();

        return redirect()
            ->route('system-ratings.index')
            ->with('success', 'Rating deleted successfully. You can now submit a new rating if you wish.');
    }
}
