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
            $table->string('ground_name')->nullable()->after('user_id');
            $table->string('license_number')->nullable()->after('ground_name');
            $table->string('category')->nullable()->after('license_number');
            $table->decimal('price_day', 10, 2)->nullable()->after('category');
            $table->decimal('price_night', 10, 2)->nullable()->after('price_day');
            $table->boolean('available_at_night')->default(false)->after('price_night');
            $table->json('ground_images')->nullable()->after('available_at_night');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('owner_requests', function (Blueprint $table) {
            $table->dropColumn([
                'ground_name',
                'license_number',
                'category',
                'price_day',
                'price_night',
                'available_at_night',
                'ground_images'
            ]);
        });
    }
};
