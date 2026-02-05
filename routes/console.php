<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule booking transitions every minute
Schedule::command('bookings:transition')->everyMinute();

// Release expired locks every 5 minutes
Schedule::command('bookings:release-locks')->everyFiveMinutes();

// Remove expired suspensions every hour
Schedule::command('users:remove-suspensions')->hourly();
