<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing bookings to include 3% admin fee in total_amount
        // Since the ground rates have already been increased by 3% in database,
        // we need to also increase the stored booking total_amount by 3%
        DB::statement('UPDATE bookings SET total_amount = ROUND(total_amount * 1.03, 2) WHERE total_amount IS NOT NULL');
        
        // Also update rate_per_hour in bookings table to reflect the 3% fee
        DB::statement('UPDATE bookings SET rate_per_hour = ROUND(rate_per_hour * 1.03, 2) WHERE rate_per_hour IS NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse: divide by 1.03 to get original amounts
        DB::statement('UPDATE bookings SET total_amount = ROUND(total_amount / 1.03, 2) WHERE total_amount IS NOT NULL');
        DB::statement('UPDATE bookings SET rate_per_hour = ROUND(rate_per_hour / 1.03, 2) WHERE rate_per_hour IS NOT NULL');
    }
};
