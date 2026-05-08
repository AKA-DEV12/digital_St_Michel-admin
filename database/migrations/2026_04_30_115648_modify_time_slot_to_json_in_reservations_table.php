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
        // First, convert existing string values to JSON arrays for compatibility
        \DB::statement("UPDATE reservations SET time_slot = JSON_ARRAY(time_slot) WHERE time_slot IS NOT NULL AND JSON_VALID(time_slot) = 0");
        
        Schema::table('reservations', function (Blueprint $table) {
            // Convert time_slot from string to json to support multiple slots
            $table->json('time_slot')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Revert back to string for backward compatibility
            $table->string('time_slot')->change();
        });
    }
};
