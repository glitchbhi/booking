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
        Schema::create('ground_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ground_id')->constrained()->onDelete('cascade');
            $table->date('slot_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_available')->default(true)->comment('Whether the slot is still available for booking');
            $table->timestamps();
            
            // Indexes for efficient queries
            $table->index(['ground_id', 'slot_date', 'is_available']);
            $table->index(['slot_date', 'is_available']);
            $table->unique(['ground_id', 'slot_date', 'start_time']); // Prevent duplicate slots
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ground_slots');
    }
};
