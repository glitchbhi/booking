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
            // Opening time for the ground (e.g., 06:00)
            $table->time('opening_time')->default('06:00:00')->after('night_rate_end');
            
            // Closing time for the ground (e.g., 22:00)
            $table->time('closing_time')->default('22:00:00')->after('opening_time');
            
            // Slot duration in minutes (default: 60 minutes/1 hour)
            $table->unsignedInteger('slot_duration')->default(60)->after('closing_time')->comment('Duration of each booking slot in minutes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grounds', function (Blueprint $table) {
            $table->dropColumn(['opening_time', 'closing_time', 'slot_duration']);
        });
    }
};
