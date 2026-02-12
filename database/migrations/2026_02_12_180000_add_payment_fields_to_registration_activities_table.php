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
        Schema::table('registration_activities', function (Blueprint $blueprint) {
            $blueprint->integer('registration_amount')->nullable()->after('location');
            $blueprint->string('wave_number')->nullable()->after('registration_amount');
            $blueprint->string('mtn_number')->nullable()->after('wave_number');
            $blueprint->string('orange_number')->nullable()->after('mtn_number');
            $blueprint->string('moov_number')->nullable()->after('orange_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registration_activities', function (Blueprint $blueprint) {
            $blueprint->dropColumn([
                'registration_amount',
                'wave_number',
                'mtn_number',
                'orange_number',
                'moov_number'
            ]);
        });
    }
};
