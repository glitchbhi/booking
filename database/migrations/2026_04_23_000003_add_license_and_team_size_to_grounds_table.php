<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('grounds', function (Blueprint $table) {
            $table->string('license_number')->nullable()->after('name');
            $table->integer('team_size')->nullable()->after('capacity');
            $table->text('owner_motivation')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('grounds', function (Blueprint $table) {
            $table->dropColumn(['license_number', 'team_size', 'owner_motivation']);
        });
    }
};
