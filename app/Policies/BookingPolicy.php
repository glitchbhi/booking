<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    /**
     * Determine if the user can create bookings
     */
    public function create(User $user): bool
    {
        return $user->canBook();
    }

    /**
     * Determine if the user can view the booking
     */
    public function view(User $user, Booking $booking): bool
    {
        // User can view their own bookings
        if ($user->id === $booking->user_id) {
            return true;
        }

        // Owner can view bookings for their grounds
        if ($user->isOwner() && $booking->ground->owner_id === $user->id) {
            return true;
        }

        // Admin can view all bookings
        return $user->isAdmin();
    }

    /**
     * Determine if the user can cancel the booking
     */
    public function cancel(User $user, Booking $booking): bool
    {
        // Only the booking owner can cancel
        if ($user->id !== $booking->user_id) {
            return false;
        }

        // Check if booking can be cancelled
        return $booking->canBeCancelled();
    }

    /**
     * Determine if the user can review the booking
     */
    public function review(User $user, Booking $booking): bool
    {
        // Only the booking owner can review
        if ($user->id !== $booking->user_id) {
            return false;
        }

        // Can only review completed bookings
        return $booking->canBeReviewed();
    }
}
