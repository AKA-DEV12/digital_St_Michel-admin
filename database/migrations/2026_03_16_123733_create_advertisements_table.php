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
        Schema::create('advertisements', function (Blueprint $結構) {
            $結構->id();
            $結構->string('title');
            $結構->string('image');
            $結構->string('link_url')->nullable();
            $結構->boolean('is_active')->default(true);
            $結構->integer('order')->default(0);
            $結構->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};
