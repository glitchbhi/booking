<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingLock;
use App\Models\Ground;
use App\Models\User;
use App\Notifications\BookingCancelled;
use App\Notifications\BookingCancelledForOwner;
use App\Notifications\BookingConfirmation;
use App\Notifications\NewBookingForOwner;
use App\Notifications\AccountSuspended;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingService
{
    public function __construct(
        protected WalletService $walletService
    ) {}

    /**
     * Check if time slot is available
     */
    public function isSlotAvailable(Ground $ground, Carbon $startTime, Carbon $endTime, ?int $excludeBookingId = null): bool
    {
        // Check for conflicting bookings
        $conflictingBookings = Booking::where('ground_id', $ground->id)
            ->whereIn('status', ['booked', 'ongoing'])
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                          ->where('end_time', '>=', $endTime);
                    });
            });

        if ($excludeBookingId) {
            $conflictingBookings->where('id', '!=', $excludeBookingId);
        }

        if ($conflictingBookings->exists()) {
            return false;
        }

        // Check for active locks
        $activeLocks = BookingLock::where('ground_id', $ground->id)
            ->where('locked_until', '>', now())
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                          ->where('end_time', '>=', $endTime);
                    });
            })
            ->exists();

        return !$activeLocks;
    }

    /**
     * Lock a time slot for booking
     */
    public function lockSlot(Ground $ground, User $user, Carbon $startTime, Carbon $endTime, int $minutes = 10): BookingLock
    {
        // Release any expired locks first
        $this->releaseExpiredLocks();

        // Check if slot is available
        if (!$this->isSlotAvailable($ground, $startTime, $endTime)) {
            throw new \Exception('Time slot is not available');
        }

        return BookingLock::create([
            'ground_id' => $ground->id,
            'user_id' => $user->id,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'locked_until' => now()->addMinutes($minutes),
        ]);
    }

    /**
     * Release expired locks
     */
    public function releaseExpiredLocks(): int
    {
        return BookingLock::where('locked_until', '<=', now())->delete();
    }

    /**
     * Create a booking
     */
    public function createBooking(
        Ground $ground,
        User $user,
        Carbon $startTime,
        Carbon $endTime,
        string $bookingType = 'online',
        ?BookingLock $lock = null
    ): Booking {
        return DB::transaction(function () use ($ground, $user, $startTime, $endTime, $bookingType, $lock) {
            // Validate user can book
            if ($bookingType === 'online' && !$user->canBook()) {
                throw new \Exception('Your account is suspended from booking');
            }

            // Check past booking
            if ($startTime->isPast()) {
                throw new \Exception('Cannot book past dates');
            }
            
            // Check minimum advance booking time (5 minutes)
            $minutesUntilStart = now()->diffInMinutes($startTime, false);
            if ($minutesUntilStart < 5) {
                throw new \Exception('Bookings must be made at least 5 minutes in advance');
            }

            // Calculate duration
            $durationHours = $endTime->diffInHours($startTime, true);
            
            // Check max duration (7 days = 168 hours)
            if ($durationHours > 168) {
                throw new \Exception('Maximum booking duration is 7 days');
            }

            // Check slot availability (double booking prevention)
            if (!$this->isSlotAvailable($ground, $startTime, $endTime)) {
                throw new \Exception('This time slot is already booked. Please choose a different time.');
            }

            // Calculate total amount based on day/night rates
            $baseAmount = $this->calculateBookingAmount($ground, $startTime, $endTime);
            
            // Apply 10% discount for full-day bookings (17 hours)
            $discount = 0;
            if ($durationHours >= 17) {
                $discount = $baseAmount * 0.10; // 10% discount
                $baseAmount = $baseAmount - $discount;
            }
            
            // Add 3% platform fee
            $platformFee = $baseAmount * 0.03;
            $totalAmount = $baseAmount + $platformFee;
            
            $effectiveRate = $durationHours > 0 ? $totalAmount / $durationHours : $ground->rate_per_hour;

            // Deduct from wallet for online bookings
            if ($bookingType === 'online') {
                if ($user->wallet_balance < $totalAmount) {
                    throw new \Exception('Insufficient wallet balance');
                }
            }

            // Create booking
            $booking = Booking::create([
                'user_id' => $user->id,
                'ground_id' => $ground->id,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'duration_hours' => $durationHours,
                'rate_per_hour' => $ground->rate_per_hour,
                'total_amount' => $totalAmount,
                'status' => 'booked',
                'booking_type' => $bookingType,
            ]);

            // Process payment for online bookings
            if ($bookingType === 'online') {
                $this->walletService->deductCoins(
                    $user,
                    $totalAmount,
                    "Booking payment for {$ground->name}",
                    $booking->id
                );
            }

            // Increment ground booking count
            $ground->incrementBookingCount();

            // Release the lock if provided
            if ($lock) {
                $lock->delete();
            }

            return $booking;
        });

        // Send notifications after transaction completes (non-blocking, after response)
        if ($bookingType === 'online') {
            dispatch(function () use ($user, $ground, $booking) {
                try {
                    $user->notify(new BookingConfirmation($booking));
                } catch (\Exception $e) {
                    \Log::warning('Booking confirmation email failed: ' . $e->getMessage());
                }
            })->afterResponse();

            dispatch(function () use ($ground, $booking) {
                try {
                    $ground->owner->notify(new NewBookingForOwner($booking));
                } catch (\Exception $e) {
                    \Log::warning('Owner booking notification email failed: ' . $e->getMessage());
                }
            })->afterResponse();
        }

        return $booking;
    }

    /**
     * Cancel a booking
     */
    public function cancelBooking(Booking $booking, string $reason = null): bool
    {
        return DB::transaction(function () use ($booking, $reason) {
            // Check if booking can be cancelled (must be at least 3 hours before start time)
            $hoursDiff = now()->diffInHours($booking->start_time, false);
            
            if ($hoursDiff < 3) {
                throw new \Exception('Bookings can only be cancelled at least 3 hours before the start time');
            }
            
            if (!$booking->canBeCancelled()) {
                throw new \Exception('Booking cannot be cancelled at this time');
            }

            // Calculate refund with 3% cancellation fee
            $cancellationFee = $booking->total_amount * 0.03;
            $refundAmount = $booking->total_amount - $cancellationFee;

            // Update booking
            $booking->update([
                'status' => 'cancelled',
                'cancellation_reason' => $reason,
                'cancelled_at' => now(),
                'is_refunded' => true,
                'refund_amount' => $refundAmount,
            ]);

            // Process refund for online bookings
            if ($booking->booking_type === 'online') {
                $this->walletService->refundCoins(
                    $booking->user,
                    $refundAmount,
                    "Refund for cancelled booking #{$booking->booking_number}",
                    $booking->id
                );
            }
            
            return true;
        });

        // Send cancellation notifications after transaction (non-blocking)
        dispatch(function () use ($booking) {
            try {
                $booking->user->notify(new BookingCancelled($booking));
            } catch (\Exception $e) {
                \Log::warning('Booking cancellation email to user failed: ' . $e->getMessage());
            }
        })->afterResponse();

        dispatch(function () use ($booking, $reason) {
            try {
                $booking->ground->owner->notify(new BookingCancelledForOwner($booking, $reason));
            } catch (\Exception $e) {
                \Log::warning('Booking cancellation email to owner failed: ' . $e->getMessage());
            }
        })->afterResponse();

        return true;
    }

    /**
     * Transition booking status
     */
    public function transitionBookingStatus(Booking $booking): bool
    {
        $now = now();

        // Booked -> Ongoing (at start time)
        if ($booking->status === 'booked' && $now->greaterThanOrEqualTo($booking->start_time)) {
            $booking->update(['status' => 'ongoing']);
            return true;
        }

        // Ongoing -> Completed (at end time)
        if ($booking->status === 'ongoing' && $now->greaterThanOrEqualTo($booking->end_time)) {
            $booking->update(['status' => 'completed']);
            return true;
        }

        return false;
    }

    /**
     * Get available time slots for a ground on a specific date
     */
    public function getAvailableSlots(Ground $ground, Carbon $date): array
    {
        $dayOfWeek = strtolower($date->format('l'));
        
        $availabilities = $ground->availabilities()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->get();

        if ($availabilities->isEmpty()) {
            return [];
        }

        $slots = [];
        foreach ($availabilities as $availability) {
            // This is a simplified version - you would generate hourly slots here
            $slots[] = [
                'start' => $availability->start_time,
                'end' => $availability->end_time,
            ];
        }

        return $slots;
    }

    /**
     * Calculate booking amount based on day/night rates
     * 
     * If the ground has day/night rate timing configured, this calculates
     * the total based on which hours fall in day vs night period.
     * Otherwise, it uses the standard rate_per_hour for all hours.
     */
    public function calculateBookingAmount(Ground $ground, Carbon $startTime, Carbon $endTime): float
    {
        // If no night rate or timing is configured, use standard rate
        if (!$ground->night_rate_per_hour || !$ground->day_rate_start || !$ground->night_rate_start) {
            $durationHours = $endTime->diffInHours($startTime, true);
            return $durationHours * $ground->rate_per_hour;
        }

        $totalAmount = 0.0;
        $currentTime = $startTime->copy();

        // Get the time strings for comparison (just H:i format)
        $dayStart = $ground->day_rate_start instanceof Carbon 
            ? $ground->day_rate_start->format('H:i') 
            : (is_string($ground->day_rate_start) ? $ground->day_rate_start : '06:00');
        
        $dayEnd = $ground->day_rate_end instanceof Carbon 
            ? $ground->day_rate_end->format('H:i') 
            : (is_string($ground->day_rate_end) ? $ground->day_rate_end : '18:00');
        
        $nightStart = $ground->night_rate_start instanceof Carbon 
            ? $ground->night_rate_start->format('H:i') 
            : (is_string($ground->night_rate_start) ? $ground->night_rate_start : '18:00');
        
        $nightEnd = $ground->night_rate_end instanceof Carbon 
            ? $ground->night_rate_end->format('H:i') 
            : (is_string($ground->night_rate_end) ? $ground->night_rate_end : '06:00');

        // Iterate through each hour of the booking
        while ($currentTime->lt($endTime)) {
            $currentHour = $currentTime->format('H:i');
            
            // Determine if current hour is in day or night period
            $isNightTime = $this->isNightTime($currentHour, $dayStart, $dayEnd, $nightStart, $nightEnd);
            
            // Apply appropriate rate
            $rate = $isNightTime ? $ground->night_rate_per_hour : $ground->rate_per_hour;
            $totalAmount += $rate;
            
            // Move to next hour
            $currentTime->addHour();
        }

        return $totalAmount;
    }

    /**
     * Determine if a given time falls within night hours
     */
    protected function isNightTime(string $currentHour, string $dayStart, string $dayEnd, string $nightStart, string $nightEnd): bool
    {
        // Convert to minutes for easier comparison
        $currentMinutes = $this->timeToMinutes($currentHour);
        $dayStartMinutes = $this->timeToMinutes($dayStart);
        $dayEndMinutes = $this->timeToMinutes($dayEnd);
        $nightStartMinutes = $this->timeToMinutes($nightStart);
        $nightEndMinutes = $this->timeToMinutes($nightEnd);

        // Night time typically spans midnight (e.g., 18:00 to 06:00)
        if ($nightStartMinutes > $nightEndMinutes) {
            // Night crosses midnight
            return $currentMinutes >= $nightStartMinutes || $currentMinutes < $nightEndMinutes;
        } else {
            // Night is within same day
            return $currentMinutes >= $nightStartMinutes && $currentMinutes < $nightEndMinutes;
        }
    }

    /**
     * Convert time string (H:i) to minutes since midnight
     */
    protected function timeToMinutes(string $time): int
    {
        $parts = explode(':', $time);
        return (int)$parts[0] * 60 + (int)($parts[1] ?? 0);
    }
}
