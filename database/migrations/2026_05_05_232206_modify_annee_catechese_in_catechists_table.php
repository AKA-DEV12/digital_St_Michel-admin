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
        Schema::table('catechists', function (Blueprint $table) {
            $table->string('annee_catechese')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catechists', function (Blueprint $table) {
            $table->enum('annee_catechese', ['1ere', '2eme', '3eme', '4eme', '5eme'])->change();
        });
    }
};
