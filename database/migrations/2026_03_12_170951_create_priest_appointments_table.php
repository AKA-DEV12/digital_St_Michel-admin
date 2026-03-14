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
        Schema::create('priest_appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('priest_id')->constrained()->onDelete('cascade');
            $table->date('appointment_date');
            $table->string('time_slot'); // e.g. 09:00 - 10:00
            $table->string('full_name');
            $table->string('phone');
            $table->string('email');
            $table->text('object');
            $table->string('status')->default('pending'); // pending, confirmed, cancelled
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('priest_appointments');
    }
};
