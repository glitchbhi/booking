<?php

namespace App\Console\Commands;

use App\Services\BookingService;
use Illuminate\Console\Command;

class ReleaseExpiredLocks extends Command
{
    protected $signature = 'bookings:release-locks';
    protected $description = 'Release expired booking locks';

    public function __construct(
        protected BookingService $bookingService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Releasing expired booking locks...');

        $released = $this->bookingService->releaseExpiredLocks();

        $this->info("Released {$released} expired locks");

        return Command::SUCCESS;
    }
}
