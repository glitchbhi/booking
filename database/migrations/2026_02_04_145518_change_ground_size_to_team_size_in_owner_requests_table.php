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
            $table->dropColumn(['ground_length', 'ground_width']);
            $table->unsignedTinyInteger('team_size')->nullable()->after('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('owner_requests', function (Blueprint $table) {
            $table->dropColumn('team_size');
            $table->decimal('ground_length', 8, 2)->nullable();
            $table->decimal('ground_width', 8, 2)->nullable();
        });
    }
};
