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
        Schema::table('owner_requests', function (Blueprint $table) {
            $table->text('business_address')->nullable()->after('ground_images');
            $table->string('contact_number', 20)->nullable()->after('business_address');
            $table->time('opening_time')->nullable()->after('contact_number');
            $table->time('closing_time')->nullable()->after('opening_time');
            $table->text('facilities')->nullable()->after('closing_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('owner_requests', function (Blueprint $table) {
            $table->dropColumn([
                'business_address',
                'contact_number',
                'opening_time',
                'closing_time',
                'facilities'
            ]);
        });
    }
};
