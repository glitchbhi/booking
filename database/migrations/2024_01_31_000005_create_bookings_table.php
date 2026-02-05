<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('ground_id')->constrained()->onDelete('cascade');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->decimal('duration_hours', 8, 2);
            $table->decimal('rate_per_hour', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('admin_commission', 10, 2)->default(0); // 2% commission
            $table->enum('status', ['booked', 'ongoing', 'completed', 'cancelled'])->default('booked');
            $table->enum('booking_type', ['online', 'offline'])->default('online');
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->boolean('is_refunded')->default(false);
            $table->decimal('refund_amount', 10, 2)->default(0);
            $table->boolean('is_no_show')->default(false);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['user_id', 'status']);
            $table->index(['ground_id', 'status']);
            $table->index(['start_time', 'end_time']);
            $table->index('status');
            $table->index('booking_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
