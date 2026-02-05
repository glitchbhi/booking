<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Console\Command;

class TransitionBookingStatus extends Command
{
    protected $signature = 'bookings:transition';
    protected $description = 'Transition booking statuses based on time';

    public function __construct(
        protected BookingService $bookingService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Starting booking status transitions...');

        // Get all active bookings
        $bookings = Booking::whereIn('status', ['booked', 'ongoing'])->get();

        $transitioned = 0;
        foreach ($bookings as $booking) {
            if ($this->bookingService->transitionBookingStatus($booking)) {
                $transitioned++;
                $this->info("Booking #{$booking->booking_number} transitioned to {$booking->status}");
            }
        }

        $this->info("Transitioned {$transitioned} bookings");

        return Command::SUCCESS;
    }
}
