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
        Schema::table('reservations', function (Blueprint $table) {
            if (!Schema::hasColumn('reservations', 'payment_email')) {
                $table->string('payment_email')->nullable()->after('pricing_type');
            }
            if (!Schema::hasColumn('reservations', 'payment_operator')) {
                $table->string('payment_operator')->nullable()->after('payment_email');
            }
            if (!Schema::hasColumn('reservations', 'payment_receipt')) {
                $table->string('payment_receipt')->nullable()->after('payment_operator');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            if (Schema::hasColumn('reservations', 'payment_email')) {
                $table->dropColumn('payment_email');
            }
            if (Schema::hasColumn('reservations', 'payment_operator')) {
                $table->dropColumn('payment_operator');
            }
            if (Schema::hasColumn('reservations', 'payment_receipt')) {
                $table->dropColumn('payment_receipt');
            }
        });
    }
};
