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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->index();
            $table->foreignId('registration_activity_id')->constrained()->onDelete('cascade');
            $table->string('full_name');
            $table->string('phone_number');
            $table->decimal('amount', 10, 2);
            $table->string('option');
            $table->string('group_name')->nullable();
            $table->string('status')->default('pending');
            $table->string('payment_email')->nullable();
            $table->string('payment_operator')->nullable();
            $table->string('payment_receipt')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
