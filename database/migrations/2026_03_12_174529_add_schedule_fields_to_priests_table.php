<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('priests', function (Blueprint $table) {
            $table->json('available_time_slots')->nullable()->after('audience');
            $table->json('unavailable_dates')->nullable()->after('available_time_slots');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('priests', function (Blueprint $table) {
            $table->dropColumn(['available_time_slots', 'unavailable_dates']);
        });
    }
};
