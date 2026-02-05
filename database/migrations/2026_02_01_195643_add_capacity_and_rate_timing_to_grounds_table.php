<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('grounds', function (Blueprint $table) {
            // Capacity field (e.g., "5-a-side", "7-a-side", "11 players")
            $table->string('capacity')->nullable()->after('night_rate_per_hour');
            $table->string('capacity_description')->nullable()->after('capacity');
            
            // Day rate timing (e.g., 06:00 to 18:00)
            $table->time('day_rate_start')->default('06:00:00')->after('capacity_description');
            $table->time('day_rate_end')->default('18:00:00')->after('day_rate_start');
            
            // Night rate timing (e.g., 18:00 to 22:00)
            $table->time('night_rate_start')->default('18:00:00')->after('day_rate_end');
            $table->time('night_rate_end')->default('22:00:00')->after('night_rate_start');
            
            // Add index for capacity filtering
            $table->index('capacity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grounds', function (Blueprint $table) {
            $table->dropIndex(['capacity']);
            $table->dropColumn([
                'capacity',
                'capacity_description',
                'day_rate_start',
                'day_rate_end',
                'night_rate_start',
                'night_rate_end'
            ]);
        });
    }
};
