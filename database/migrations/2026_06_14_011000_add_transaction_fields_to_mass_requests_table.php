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
        Schema::table('mass_requests', function (Blueprint $table) {
            $table->string('transaction_id')->nullable()->unique()->after('payment_receipt');
            $table->timestamp('validated_at')->nullable()->after('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mass_requests', function (Blueprint $table) {
            $table->dropColumn(['transaction_id', 'validated_at']);
        });
    }
};
