<?php

namespace App\Http\Controllers;

use App\Models\Ground;
use Illuminate\Http\Request;

class GroundRatingController extends Controller
{
    public function index(Ground $ground)
    {
        // Get paginated reviews
        $ratings = $ground->reviews()
            ->with('user')
            ->latest()
            ->paginate(5);

        // Get user's rating if authenticated
        $userRating = null;
        if (auth()->check()) {
            $userRating = $ground->reviews()
                ->where('user_id', auth()->id())
                ->first();
        }

        // Calculate average rating
        $averageRating = $ground->average_rating ?? 0;
        $totalRatings = $ground->total_reviews ?? 0;

        // Get rating distribution
        $ratingDistribution = [
            5 => $ground->reviews()->where('rating', 5)->count(),
            4 => $ground->reviews()->where('rating', 4)->count(),
            3 => $ground->reviews()->where('rating', 3)->count(),
            2 => $ground->reviews()->where('rating', 2)->count(),
            1 => $ground->reviews()->where('rating', 1)->count(),
        ];

        return view('grounds.ratings', compact(
            'ground',
            'ratings',
            'userRating',
            'averageRating',
            'totalRatings',
            'ratingDistribution'
        ));
    }

    public function store(Request $request, Ground $ground)
    {
        $this->authorize('review', $ground);

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Check if user already has a review
        $existing = $ground->reviews()
            ->where('user_id', auth()->id())
            ->first();

        if ($existing) {
            return redirect()
                ->back()
                ->with('error', 'You have already reviewed this ground. Delete your previous review to submit a new one.');
        }

        // Create or update review
        $ground->reviews()->create([
            'user_id' => auth()->id(),
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return redirect()
            ->route('ground-ratings.index', $ground)
            ->with('success', 'Thank you for rating! Your review has been submitted.');
    }

    public function destroy(Ground $ground, $review)
    {
        $review = $ground->reviews()->findOrFail($review);

        $this->authorize('delete', $review);

        $review->delete();

        return redirect()
            ->route('ground-ratings.index', $ground)
            ->with('success', 'Your review has been deleted.');
    }
}
