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
        // Update all ground prices by adding 3% admin commission
        DB::statement('UPDATE grounds SET rate_per_hour = ROUND(rate_per_hour * 1.03, 2) WHERE rate_per_hour IS NOT NULL');
        DB::statement('UPDATE grounds SET night_rate_per_hour = ROUND(night_rate_per_hour * 1.03, 2) WHERE night_rate_per_hour IS NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the pricing changes - divide by 1.03 to get original prices
        DB::statement('UPDATE grounds SET rate_per_hour = ROUND(rate_per_hour / 1.03, 2) WHERE rate_per_hour IS NOT NULL');
        DB::statement('UPDATE grounds SET night_rate_per_hour = ROUND(night_rate_per_hour / 1.03, 2) WHERE night_rate_per_hour IS NOT NULL');
    }
};
