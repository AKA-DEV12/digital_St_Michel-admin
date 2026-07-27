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
        Schema::create('mass_requests', function (Blueprint $table) {
            $table->id();
            $table->date('requested_date');
            $table->json('time_slots');
            $table->string('name1');
            $table->string('name2')->nullable();
            $table->string('name3')->nullable();
            $table->text('request_object');
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending');
            $table->string('email');
            $table->string('phone');
            $table->string('payment_operator')->nullable();
            $table->string('payment_receipt')->nullable();
            $table->string('transaction_id')->nullable()->unique();
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mass_requests');
    }
};
