<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->foreignId('scanned_by_agent_id')->nullable()->constrained('agents')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropForeign(['scanned_by_agent_id']);
            $table->dropColumn('scanned_by_agent_id');
        });
    }
};
