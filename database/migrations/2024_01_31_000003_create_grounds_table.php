<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grounds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('sport_type_id')->constrained('sports_types')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('location');
            $table->string('address')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('rate_per_hour', 10, 2);
            $table->json('images')->nullable();
            $table->boolean('is_active')->default(true);
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->integer('total_bookings')->default(0);
            $table->integer('total_reviews')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['owner_id', 'is_active']);
            $table->index(['sport_type_id', 'is_active']);
            $table->index(['location', 'is_active']);
            $table->index('average_rating');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grounds');
    }
};
