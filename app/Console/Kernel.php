<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Schedule booking transitions every minute
        $schedule->command('bookings:transition')->everyMinute();

        // Release expired locks every 5 minutes
        $schedule->command('bookings:release-locks')->everyFiveMinutes();

        // Remove expired suspensions every hour
        $schedule->command('users:remove-suspensions')->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
