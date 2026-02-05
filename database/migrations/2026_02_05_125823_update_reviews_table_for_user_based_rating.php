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
        Schema::table('reviews', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['booking_id']);
            // Then drop the unique constraint on booking_id
            $table->dropUnique(['booking_id']);
        });
        
        Schema::table('reviews', function (Blueprint $table) {
            // Drop the booking_id column
            $table->dropColumn('booking_id');
        });
        
        Schema::table('reviews', function (Blueprint $table) {
            // Re-add booking_id as nullable without constraints
            $table->foreignId('booking_id')->nullable()->after('ground_id');
            
            // Add unique constraint for one review per user per ground
            $table->unique(['user_id', 'ground_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Drop the new constraint
            $table->dropUnique(['user_id', 'ground_id']);
            
            // Drop the nullable booking_id
            $table->dropColumn('booking_id');
        });
        
        Schema::table('reviews', function (Blueprint $table) {
            // Re-add booking_id with foreign key and unique constraint
            $table->foreignId('booking_id')->after('ground_id')->constrained()->onDelete('cascade');
            $table->unique(['booking_id']);
        });
    }
};
