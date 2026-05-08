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
        Schema::table('rooms', function (Blueprint $table) {
            $table->decimal('price_per_hour', 8, 2)->nullable()->after('status');
            $table->decimal('price_half_day', 8, 2)->nullable()->after('price_per_hour');
            $table->decimal('price_full_day', 8, 2)->nullable()->after('price_half_day');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn(['price_per_hour', 'price_half_day', 'price_full_day']);
        });
    }
};
