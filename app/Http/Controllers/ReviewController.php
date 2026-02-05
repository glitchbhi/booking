<?php

namespace App\Http\Controllers;

use App\Models\Ground;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Ground $ground)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to submit a review');
        }

        // Check if user has verified email
        if (!auth()->user()->hasVerifiedEmail()) {
            return redirect()->back()->with('error', 'Please verify your email address to submit reviews and view ratings');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        try {
            // Check if user has already reviewed this ground
            $existingReview = Review::where('user_id', auth()->id())
                ->where('ground_id', $ground->id)
                ->first();

            if ($existingReview) {
                return back()->with('error', 'You have already reviewed this ground. Delete your previous review to submit a new one.');
            }

            $review = Review::create([
                'user_id' => auth()->id(),
                'ground_id' => $ground->id,
                'booking_id' => null,
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
            ]);

            return redirect()
                ->route('grounds.show', $ground)
                ->with('success', 'Review submitted successfully! Thank you for your feedback.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to submit review: ' . $e->getMessage());
        }
    }

    public function destroy(Review $review)
    {
        // Only allow users to delete their own reviews
        if ($review->user_id !== auth()->id()) {
            return back()->with('error', 'You can only delete your own reviews.');
        }

        $groundId = $review->ground_id;
        $review->delete();

        return redirect()
            ->route('grounds.show', $groundId)
            ->with('success', 'Review deleted successfully. You can now submit a new review if you wish.');
    }
}
