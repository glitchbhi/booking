<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('grounds', function (Blueprint $table) {
            $table->dateTime('maintenance_start_date')->nullable()->after('is_under_maintenance');
            $table->dateTime('maintenance_end_date')->nullable()->after('maintenance_start_date');
            $table->text('maintenance_reason')->nullable()->after('maintenance_end_date');
            
            $table->index(['is_under_maintenance', 'maintenance_end_date']);
        });
    }

    public function down(): void
    {
        Schema::table('grounds', function (Blueprint $table) {
            $table->dropColumn(['maintenance_start_date', 'maintenance_end_date', 'maintenance_reason']);
            $table->dropIndex(['is_under_maintenance', 'maintenance_end_date']);
        });
    }
};
