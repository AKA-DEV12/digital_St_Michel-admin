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
        Schema::table('mass_times', function (Blueprint $table) {
            $table->enum('day_type', ['semaine', 'dimanche', 'samedi'])->default('semaine')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mass_times', function (Blueprint $table) {
            $table->enum('day_type', ['semaine', 'dimanche'])->default('semaine')->change();
        });
    }
};
