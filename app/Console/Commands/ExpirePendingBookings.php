<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExpirePendingBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:expire-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire pending bookings that have exceeded the 10-minute payment deadline';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expired pending bookings...');

        // Find pending bookings that have expired
        $expiredBookings = Booking::where('status', 'pending')
            ->where('expires_at', '<', now())
            ->get();

        if ($expiredBookings->isEmpty()) {
            $this->info('No expired bookings found.');
            return 0;
        }

        $expiredCount = 0;
        foreach ($expiredBookings as $booking) {
            try {
                $booking->expireBooking();
                $expiredCount++;
                
                $this->line("Expired booking #{$booking->booking_number} for ground: {$booking->ground->name}");
                
                // Log the expiration
                Log::info("Booking expired", [
                    'booking_id' => $booking->id,
                    'booking_number' => $booking->booking_number,
                    'ground_id' => $booking->ground_id,
                    'user_id' => $booking->user_id,
                    'expired_at' => now(),
                ]);
                
            } catch (\Exception $e) {
                $this->error("Failed to expire booking #{$booking->booking_number}: {$e->getMessage()}");
                Log::error("Failed to expire booking", [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Successfully expired {$expiredCount} bookings.");
        return $expiredCount;
    }
}
