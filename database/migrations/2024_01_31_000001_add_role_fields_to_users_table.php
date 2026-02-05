<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['user', 'owner', 'admin'])->default('user')->after('email');
            $table->enum('owner_status', ['none', 'pending', 'approved', 'rejected'])->default('none')->after('role');
            $table->boolean('is_suspended')->default(false)->after('owner_status');
            $table->timestamp('suspended_until')->nullable()->after('is_suspended');
            $table->integer('late_cancel_count')->default(0)->after('suspended_until');
            $table->decimal('wallet_balance', 10, 2)->default(0)->after('late_cancel_count');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role', 
                'owner_status', 
                'is_suspended', 
                'suspended_until', 
                'late_cancel_count',
                'wallet_balance'
            ]);
            $table->dropSoftDeletes();
        });
    }
};
