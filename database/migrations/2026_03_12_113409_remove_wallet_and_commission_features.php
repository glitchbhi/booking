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
        // Drop wallet_transactions table
        Schema::dropIfExists('wallet_transactions');
        
        // Drop booking_locks table
        Schema::dropIfExists('booking_locks');
        
        // Remove wallet_balance from users table
        if (Schema::hasColumn('users', 'wallet_balance')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('wallet_balance');
            });
        }
        
        // Remove commission and refund fields from bookings table
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'admin_commission')) {
                $table->dropColumn('admin_commission');
            }
            if (Schema::hasColumn('bookings', 'is_refunded')) {
                $table->dropColumn('is_refunded');
            }
            if (Schema::hasColumn('bookings', 'refund_amount')) {
                $table->dropColumn('refund_amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate wallet_transactions table
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('booking_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['credit', 'debit', 'refund']);
            $table->decimal('amount', 10, 2);
            $table->decimal('balance_before', 10, 2);
            $table->decimal('balance_after', 10, 2);
            $table->string('description');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
        
        // Recreate booking_locks table
        Schema::create('booking_locks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ground_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->dateTime('locked_until');
            $table->timestamps();
        });
        
        // Add wallet_balance back to users table
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('wallet_balance', 10, 2)->default(0);
        });
        
        // Add commission and refund fields back to bookings table
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('admin_commission', 10, 2)->default(0);
            $table->boolean('is_refunded')->default(false);
            $table->decimal('refund_amount', 10, 2)->nullable();
        });
    }
};
