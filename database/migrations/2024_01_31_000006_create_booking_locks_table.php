<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_locks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ground_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->timestamp('locked_until');
            $table->timestamps();
            
            $table->index(['ground_id', 'locked_until']);
            $table->index(['user_id', 'locked_until']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_locks');
    }
};
